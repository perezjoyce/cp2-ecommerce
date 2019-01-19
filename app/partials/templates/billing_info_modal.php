<?php
    require_once '../../../config.php';

    function getPreselectedAddress($addressId, $conn) {
        $statement = $conn->prepare("SELECT a.*, o.address_id FROM  tbl_addresses a JOIN tbl_orders o ON o.address_id=a.id WHERE cart_session = ? ");
        $statement->execute([$_SESSION['cart_session']]);
        $row = $statement->fetch();
        // brgy
        $brgyStatement = $conn->prepare("SELECT * FROM tbl_barangays WHERE id=?"); 
        $brgyStatement->execute([$row['brgy_id']]);
        $barangayData = $brgyStatement->fetch();
        // city
        $cityStatement = $conn->prepare("SELECT * FROM tbl_cities WHERE id=?");
        $cityStatement->execute([$row['city_id']]);
        $cityData = $cityStatement->fetch();
        // province
        $provinceStatement = $conn->prepare("SELECT * FROM tbl_provinces WHERE id=?");
        $provinceStatement->execute([$row['province_id']]);
        $provinceData = $provinceStatement->fetch();

        // region 
        $regionStatement = $conn->prepare("SELECT * FROM tbl_regions WHERE id=?");
        $regionStatement->execute([$row['region_id']]);
        $regionData = $regionStatement->fetch();

        $addressData = [
            'id' => $row['id'],
            'brgy_id' => [
                'id' => $barangayData['id'],
                'name' => $barangayData['brgyDesc']
            ],
            'city_id' => [
                'id' => $cityData['citymunCode'],
                'name' => $cityData['citymunDesc']
            ],
            'province_id' => [
                'id' => $provinceData['provCode'],
                'name' => $provinceData['provDesc']
            ],
            'region_id' => [
                'id' => $regionData['regCode'],
                'name' => $regionData['regDesc']
            ],
            'street_bldg_unit' => $row['street_bldg_unit'],
            'landmark' => $row['landmark'],
            'addressType' => $row['addressType'],
            'name' => $row['name']
        ];

        return $addressData;
    }

    if(!$_SESSION['id']) {
        // pass redirect url so that after logging in you will be able to return to the intended page, in this case check-out
        header("location: login_modal.php?redirectUrl=checkout");
    } 

    $userId = $_SESSION['id'];
    $cartSession = $_SESSION['cart_session'];
    $preselectedAddressId = isset($_SESSION['preselectedAddressId']) 
        ? $_SESSION['preselectedAddressId'] 
        : null;

    
        
    $preselectedAddressData = getPreselectedAddress($preselectedAddressId, $conn);
    // var_dump($preselectedAddressData); die();
    $sql = " SELECT * FROM tbl_orders WHERE user_id = ? AND cart_session = ? ";
    $statement = $conn->prepare($sql);
    $statement->execute([$userId, $cartSession]);
    $count = $statement->rowCount();
    
?>

<style>
.stripe-button-el span {
    font-size: 18px!important;
    text-shadow: none!important;
    box-shadow: none!important;
    font-weight: 650!important;
    background: #9c5abd!important;
    border: 2px solid #8e44ad!important;
    color: white!important;
    padding: 15px;
    /* margin-top: 2.3em; */
    width:100%!important;
}
button[class^=stripe-button-el] span {
    /* background: #8e44ad;  */
    border: white!important;
    /* color: #9c5abd!important; */
    /* font-weight: 500; */
    /* height: 40px; */
    /* background-image: initial; */
    padding: 41px;
    padding-top: 16px;
    /* background: #9c5abd; */
}

</style>

<div class="container-fluid">
    <div class="row">

        <div class="col-lg-2 ml-0 py-0 px-0 my-0 ml-0 d-none d-lg-block d-xl-block">
            <div id='login_image'></div>
            <!-- <div id='login_ad'>
                <h1>fdsfsd</h1>
            </div> -->
        </div>

        <div class="col" style='height:80vh;overflow-y:auto;'>

            <div class="row float-right">
                <button id='close_modal' type="button" class="close mr-3 mt-2" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true" class='font-weight-light text-secondary' style='font-size:20px;'>&times;</span>
                </button>
            </div>

            <div class="container px-5 pb-2 pt-5">
                <div class="row mt-4"> 
                    <div class='col'>
                       <h3>Your Billing Information</h3>
                    </div>
                </div>

                <div class="row">
                    <div class="col">

                        <!-- HIDDEN ELEMENT  -->
                        <form action='../controllers/process_save_billing_address.php' method='POST' id='shipping_info_modal'>
                            <input type="hidden" id="address_id" name="address_id" value="<?= isset($preselectedAddressId) 
                                        ? $_SESSION['preselectedAddressId'] : null ?>">

                            <div class="form-inline ml-0 px-0 mt-5">
                                <div class='pr-5 pt-3 mr-4'>Choose Billing Address</div> 
                                &nbsp;<div class="form-check form-check-inline radio-item pr-5 pl-0 selected">    
                                    <input class="form-check-input user_addressTypes" name="address_type" id="btn_save_shipping_as_billing" type="radio" value='<?= isset($preselectedAddressData['id']) 
                                        ? $preselectedAddressData['id'] : "" ?>' checked="">
                                    <label  style='justify-content:start' class="form-check-label" for='btn_save_shipping_as_billing' active>&nbsp;Same As Shipping</label>
                                </div>

                                

                                 <?php
                                
                                    $sql = " SELECT * FROM tbl_addresses WHERE `user_id` = ? AND id != ? ";
                                            $statement = $conn->prepare($sql);
                                            $statement->execute([$userId,$preselectedAddressData['id']]);
                                            $count = $statement->rowCount();

                                    // IF OTHER ADDRESSES ASIDE THE ONE USED AS SHIPPING
                                    if($count) {
                              
                                        while($row = $statement->fetch()){ 
                                        $addressType = ucfirst($row['addressType']);
                                        $checked = "";

                                    ?>
                                <div class="form-check form-check-inline radio-item pr-5 pl-0">

                                    <input class="form-check-input user_addressTypes" id='<?= $addressType ?>' name="address_type" type="radio" value="<?= $row['id'] ?>" <?= $checked ?>>
                                    <label style='justify-content:start' class="form-check-label" for='<?= $addressType ?>'>&nbsp;<?= $addressType ?></label>    
                                </div>

                                    <?php }  } ?>

                                <div class="form-check form-check-inline radio-item pr-5 pl-0">    
                                    <input class="form-check-input user_addressTypes" name="address_type" id="btn_add_new_address" type="radio">
                                    <label style='justify-content:start' class="form-check-label" for='btn_add_new_address'>&nbsp;Add New Address</label>
                                </div>

                            </div>
                                
                        
                            <!-- REGION -->
                            <div class="form-group row my-5">
                                <label for='region' class='col'>Region*</label>
                                <div class="input-group col-9">
                                    <select class="custom-select" id="region" onchange="region">
                                        <option id='region-initial-selected' value='...' selected="...">...</option>
                                            <?php 
                                                $sql = " SELECT * FROM tbl_regions ";
                                                $statement = $conn->prepare($sql);
                                                $statement->execute();
                                                
                                                while($row = $statement->fetch()){ 
                                                    $selected = "";
                                                    if (isset($preselectedAddressData['region_id']) 
                                                        && $row['regDesc'] == $preselectedAddressData['region_id']['name']) {
                                                        $selected = "selected";
                                                    }

                                                    $region = $row['regDesc'];
                                                    $regionId = $row['id'];
                                                    $regCode = $row['regCode'];
                                            ?>
                                        <option data-id='<?=$region?>' value='<?= $regionId ?>' <?= $selected ?>>
                                            <?= $region ?>
                                        </option>
                                    <?php } ?>
                                    </select>
                                </div>
                            </div>
                            
                            <!-- PROVINCE -->
                            <div class="form-group row mb-5">
                                <label for='province' class='col'>Province*</label>
                                <div class="input-group col-9">
                                    <select class="custom-select" id="province" data-id='<?= $regionId?>'>
                                        <option id='province-initial-selected' value='...' selected="...">...</option>
                                        <?php 

                                            if(isset($preselectedAddressData['region_id']['id'])) {
                                                $regCode = $preselectedAddressData['region_id']['id'];
                                            }
                                            
                                            $sql = " SELECT * FROM tbl_provinces WHERE regCode = $regCode ";
                                            $statement = $conn->prepare($sql);
                                            $statement->execute([$regCode]);
                                            while($row = $statement->fetch()){ 
                                                $selected = "";
                                                if(isset($preselectedAddressData['province_id'])
                                                    && $row['provDesc'] == $preselectedAddressData['province_id']['name']
                                                ) {
                                                    $selected = "selected";
                                                }
                                                $province = $row['provDesc'];
                                                $provinceId = $row['id'];
                                                $provCode = $row['provCode'];
                                        ?>
                                                <option class='province-option' value='<?= $provinceId ?>' <?= $selected ?>><?= $province ?> </option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>
                            
                            <!-- CITYMUN -->
                            <div class="form-group row mb-5">
                                <label for='cityMun' class='col'>City or Municipality*</label>
                                <div class="input-group col-9">
                                    <select class="custom-select" id="cityMun" data-id='<?= $provinceId ?>'>
                                        <option id='cityMun-initial-selected' value='...' selected="...">...</option>
                                        <?php 

                                            if(isset($preselectedAddressData['province_id']['id'])) {
                                                $provCode = $preselectedAddressData['province_id']['id'];
                                            }    
                                            
                                            $sql = " SELECT * FROM tbl_cities WHERE provCode = ? ";
                                            $statement = $conn->prepare($sql);
                                            $statement->execute([$provCode]);
                                            while($row = $statement->fetch()){ 

                                                $selected = "";
                                                if(isset($preselectedAddressData['city_id']['id']) 
                                                    && $row['citymunDesc'] == $preselectedAddressData['city_id']['name']) {
                                                    $selected = "selected";
                                                }
                                                $cityMun = $row['citymunDesc'];
                                                $cityMunId = $row['id'];
                                                $cityMunCode = $row['citymunCode'];
                                        ?>
                                        <option class='cityMun-option' value='<?= $cityMunId ?>' <?= $selected ?>><?= $cityMun ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>
                            
                            <!-- BARANGAY -->
                            <div class="form-group row mb-5">
                                <label for='barangay' class='col'>Barangay*</label>
                                <div class="input-group col-9">
                                    <select class="custom-select"  id="barangay" data-id='<?= $cityMunId?>'>
                                        <option id='brgy-initial-selected' value='...' selected="...">...</option>
                                        <?php 

                                            if(isset($preselectedAddressData['city_id']['id'])) {
                                                $cityMunCode = $preselectedAddressData['city_id']['id'];
                                            }

                                            $sql = " SELECT * FROM tbl_barangays WHERE citymunCode = ? ";
                                            $statement = $conn->prepare($sql);
                                            $statement->execute([$cityMunCode]);
                                            while($row = $statement->fetch()){ 
                                                $selected = "";
                                                if($preselectedAddressData['brgy_id']['id'] == $row['id']) {
                                                    $selected = "selected";
                                                }
                                                $barangay = $row['brgyDesc'];
                                                $barangayId = $row['id'];
                                        ?>
                                        <option class='barangay-option' value='<?= $barangayId ?>' <?= $selected ?>><?= $barangay ?></option>
                                        <?php } ?>
                                    </select> 
                                </div>
                            </div>

                            <!-- STREET BLDG. UNIT NO -->
                            <div class="form-group row mb-5">
                                <label for='streetBldgUnit' class='col'>Street, Bldg., Unit No., etc.*</label>
                                <div class="input-group col-9">
                                    <input type="text" 
                                        class='form-control' 
                                        id='streetBldgUnit' 
                                        value="<?= isset($preselectedAddressData['street_bldg_unit']) 
                                            ? $preselectedAddressData['street_bldg_unit'] 
                                            : "" ?>">
                                </div>
                            </div>
                            
                            <!-- LANDMARK -->
                            <div class="form-group row mb-5">
                                <label for='landmark' class='col'>Landmark</label>
                                <div class="input-group col-9">
                                    <input type="text" class='form-control' id='landmark'
                                        value="<?= isset($preselectedAddressData['landmark']) 
                                                ? $preselectedAddressData['landmark'] 
                                                : "" ?>">
                                </div>
                            </div>
                  
                            <!-- ADDRESS TYPE -->
                            <div class="form-group row mb-5">
                                <label for='addressType' class='col'>Address Type*</label>
                                <div class="input-group col-9">
                                    <!-- for editing -->
                                    <select class="custom-select" id="addressType">
                                        <?php
                                            $addressType = null;
                                            if(!isset($preselectedAddressData['addressType'])) {
                                                echo "<option value='...' selected=\"...\">...</option>";
                                            } else {
                                                $addressType = $preselectedAddressData['addressType'];
                                            }
                                        ?>
                                        <option value='...'>...</option>
                                        <option value="home" <?= $addressType == "home" ? "selected": ""?>>Home</option>
                                        <option value="office" <?= $addressType == "office" ? "selected": ""?>>Office</option>
                                        <option value="others" <?= $addressType == "others" ? "selected": ""?>>Others</option>
                                    </select>
                                </div>
                            </div>

                            <!-- NAME -->
                            <div class="form-group row mb-5">
                                <label for='name' class='col'>Complete Name*</label>
                                <div class="input-group col-9">
                                    <input type="text" class='form-control' id='name'
                                        value="<?= isset($preselectedAddressData['name']) 
                                                ? $preselectedAddressData['name'] 
                                                : "" ?>">
                                </div>
                            </div>

                             <!-- PAYMENT MODE -->
                             <div class="form-group row mb-5">
                                <label for='modeOfPayment' class='col'>Payment Mode*</label>
                                <div class="input-group col-9">
                                    <select class="custom-select" id="modeOfPayment">
                                        <option value='...' selected="...">...</option>
                                            <?php 
                                                $sql = " SELECT * FROM tbl_payment_modes ";
                                                $statement = $conn->prepare($sql);
                                                $statement->execute();
                                                while($row = $statement->fetch()){ 
                                                    $payment_mode_name = $row['name'];
                                                    $payment_mode_id = $row['id'];
                                            ?>
                                        <option value='<?= $payment_mode_id ?>'>
                                            <?= $payment_mode_name ?>
                                        </option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>

                            <p id="billing_info_error"></p>

                            <div class="container px-0 my-5" id='billing_info_buttons'>
                            <br><br><br>
                                <!-- CHECKOUT BUTTON -->
                                <div class="row">
                                    <div class="col-4">

                                        <?php 
                                            $modalLinkClassPrefix = ''; 
                                            if(isset($_SESSION['id'])) {
                                                $modalLinkClassPrefix='-big';
                                            }
                                        ?>
                                        
                                        <a class='btn btn-lg btn-block py-3 btn-purple back modal-link<?= $modalLinkClassPrefix?>'
                                            data-url="../partials/templates/shipping_info_modal.php" role='button'>
                                            <i class="fas fa-angle-double-left"></i>
                                            &nbsp;Edit Shipping Info
                                        </a>

                                    </div>

                                    <div class="col-4">

                                    </div>

                                    <div class="col-4">
                                        <a class='btn btn-lg btn-block py-3 btn-purple modal-link<?= $modalLinkClassPrefix?>' 
                                            data-url='../partials/templates/confirmation_modal.php' id='btn_order_confirmation' role='button'>
                                            Confirm Order
                                        </a>

                                    </div>
                                </div>
                            </div>
                                

                        </form>

                        <div class="container px-0 my-5" id='stripe_buttons' style='display:none;'>
                        <br><br><br>
                            <!-- CHECKOUT BUTTON -->
                            <div class="row">
                                <div class="col-4">

                                    <?php 
                                        $modalLinkClassPrefix = ''; 
                                        if(isset($_SESSION['id'])) {
                                            $modalLinkClassPrefix='-big';
                                        }
                                    ?>
                                    
                                    <a class='btn btn-lg btn-block py-3 btn-purple back modal-link<?= $modalLinkClassPrefix?> mt-5'
                                        data-url="../partials/templates/shipping_info_modal.php" role='button' id='btn_back_to_shipping'>
                                        <i class="fas fa-angle-double-left"></i>
                                        &nbsp;Edit Shipping Info
                                    </a>

                                </div>

                                <div class="col-4"></div>

                                <div class="col-4">
                                    
                                    <form action="<?= BASE_URL ?>/app/controllers/process_charge.php" method="post" id="stripe_pay_button">
                                        <?php $totalAmount = $_SESSION['total_amount'] ?>
                                        <script
                                            src="https://checkout.stripe.com/checkout.js" class="stripe-button stripe-button-el"
                                            data-key="<?= $stripe['publishable_key'] ?>"
                                            data-amount="<?= str_replace(".", "", number_format($totalAmount,2, "",".")) ?>"
                                            data-name="Mamaroo"
                                            data-description="Checkout Payment"
                                            data-label="Pay With Card"
                                            data-image="https://stripe.com/img/documentation/checkout/marketplace.png"
                                            data-locale="auto">
                                        </script>
                                        <script>
                                            // Hide default stripe button, be careful there if you
                                            // have more than 1 button of that class
                                            document.getElementsByClassName("stripe-button-el")[0].style.display = 'none';
                                        </script>
                                        <!-- <a type="submit" class="btn btn-lg btn-block py-3 btn-purple" id='btn_stripe'>Use Credit Card</a> -->
                                    </form>
                                </div>
                            </div>
                        </div>





                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
                        


    