<?php

require_once '../../../config.php';
require_once '../../sources/pdo/src/PDO.class.php';
require_once '../../controllers/functions.php';
require_once '../../controllers/connect.php';

    function getPreselectedAddress($addressId, $conn) {
        $statement = $conn->prepare("SELECT * FROM tbl_addresses WHERE id=?");
        $statement->execute([$addressId]);
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
    // $cartSession = $_SESSION['cart_session'];
    $preselectedAddressId = isset($_SESSION['preselectedAddressId']) 
        ? $_SESSION['preselectedAddressId'] 
        : null;


        
    $preselectedAddressData = getPreselectedAddress($preselectedAddressId, $conn);
    $sql = " SELECT * FROM tbl_addresses WHERE `user_id` = ? ";
    $statement = $conn->prepare($sql);
    $statement->execute([$userId]);
    $count = $statement->rowCount();
    
?>

<div class="container-fluid">
    <div class="row">

        <div class="col" style='height:80vh;overflow-y:auto;'>

            <div class="row float-right">
                <button id='close_modal' type="button" class="close mr-3 mt-2" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true" class='font-weight-light text-secondary' style='font-size:20px;'>&times;</span>
                </button>
            </div>

            <div class="container px-5 pb-2 pt-5">
                <div class="row mt-4"> 
                    <div class='col'>
                       <h3>Your Saved Address/es</h3>
                    </div>
                </div>

                <div class="row">
                    <div class="col">

                        <!-- HIDDEN ELEMENT  -->
                        <input type="hidden" id="address_id" name="address_id" value="<?= isset($preselectedAddressData['id']) 
                                        ? $preselectedAddressData['id'] : "" ?>">
                        <form action='../controllers/process_save_address.php' method='POST' id='shipping_info_modal'>
                            <?php if($count) {  ?>
                            
                            <div class="form-inline justify-content-left mt-5">
                                <div class='pr-5 pt-3'>Address Type To Edit</div>    
                                            
            
                                <?php
                                    while($row = $statement->fetch()){ 
                                        $addressType = ucfirst($row['addressType']);
                                        $checked = "";

                                        if(isset($preselectedAddressData['addressType']) && 
                                            strtolower($addressType) == strtolower($preselectedAddressData['addressType'])) {
                                                $checked = "checked";
                                        }
                                ?>
                                <div class="form-check form-check-inline pr-5 radio-item">
                                    <input class="form-check-input user_addressTypes" id='<?= $addressType ?>' name="address_type" type="radio" value="<?= $row['id'] ?>" <?= $checked ?>>
                                    <label class="form-check-label" for='<?= $addressType ?>'>&nbsp;<?= $addressType ?></label>
                                </div>

                                <?php }  ?>
                                <div class="form-check form-check-inline radio-item">    
                                    <input class="form-check-input user_addressTypes" name="address_type" id="btn_add_new_address" type="radio">
                                    <label class="form-check-label" for='btn_add_new_address'>&nbsp;Add New Address</label>
                                </div>

                            </div>
                            <?php } ?>
                                
                        
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
                                <label for='building' class='col'>Street, Bldg., Unit No., etc.*</label>
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
                                <label class='col'>Address Type*</label>
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
                                        <option value="home" <?= $addressType == "home" ? "selected": ""?>>Home</option>
                                        <option value="office" <?= $addressType == "office" ? "selected": ""?>>Office</option>
                                        <option value="others" <?= $addressType == "others" ? "selected": ""?>>Others</option>
                                    </select>
                                </div>
                            </div>

                            <!-- NAME -->
                            <div class="form-group row mb-5">
                                <label for='name' class='col-lg-3 col-md-3 col-sm-12'>Password*</label>
                                <div class="input-group col-lg-9 col-md-9 col-sm-12">
                                    <input type="password" class='form-control border-right-0' id='password'>
                                    <a class="btn hide btn_view_profile_password border border-left-0 mx-0 px-0 eye-profile">
                                        <i class="fas fa-eye-slash eye text-secondary bg-light hide pr-2"></i>
                                    </a>
                                </div>
                                <!-- <div class="input-group-append col-1 border-0 m-0 px-0">
                                    <a class="btn hide btn_view_profile_password border border-left-0 mx-0">
                                        <i class="fas fa-eye-slash eye text-secondary bg-light hide px-2 mx-0"></i>
                                    </a>
                                </div> -->
                            </div>
                                

                            <p id="address_error_message"></p>

                            <!-- if input type is submit, this will automatically submit input to users.php hence change this to button, type to button and remove value SO THAT you can employ validation -->
                            <!-- indicate id for button -->

                            

                            <div class="container px-0 mb-5">
                                <!-- CHECKOUT BUTTON -->
                                <div class="row">
                                    <div class="col-lg-8 col-md-6"></div>
                                    <div class="col-lg-4 col-md-6 col-sm-12"> 
                                        <a class='btn btn-lg btn-block py-3 btn-purple mt-5 save_address_edit' id='btn_edit_address' role='button'>
                                            Save Changes
                                        </a>

                                    </div>
                                </div>
                            </div>
                                

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
                        


    