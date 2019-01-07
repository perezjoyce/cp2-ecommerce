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
            'addressType' => $row['addressType']
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
    $sql = " SELECT * FROM tbl_orders WHERE user_id = ? AND cart_session = ? ";
    $statement = $conn->prepare($sql);
    $statement->execute([$userId, $cartSession]);
    $count = $statement->rowCount();
    
    if ($count) {

        // DOESN'T WORK UNLESS I MAKE A DIFFERENT FORM INSIDE THIS IF SO THAT THE EXISTING ONE WILL BE INSIDE ELSE
        // BUT THE PROB IS IF USER DECIDES TO EDIT

        $sql = " SELECT * FROM tbl_addresses WHERE `user_id` = ?";
            $statement = $conn->prepare($sql);
            $statement->execute([$userId]);
            $count = $statement->rowCount();
    }
?>

    

        <form>
            <label class='mb-5'>Shipping Information</label>

            <br>
                

                <label>Choose Saved Address Type</label>
                <div class="form-check">
                
                <!-- IF USER HAS ADDRESS... -->
                <?php 

            
                        $sql = " SELECT * FROM tbl_addresses WHERE `user_id` = ? ";
                        $statement = $conn->prepare($sql);
                        $statement->execute([$userId]);  

                        while($row = $statement->fetch()){ 
                            $addressType = ucfirst($row['addressType']);
                            $checked = "";
                            if(isset($preselectedAddressData['addressType']) && 
                                strtolower($addressType) == strtolower($preselectedAddressData['addressType'])) {
                                    $checked = "checked";
                            }
        
                ?>
                    <div>
                        <label class="form-check-label">
                            <input class="form-check-input user_addressTypes" name="address_type" type="radio" value="<?= $row['id'] ?>" <?= $checked ?>>
                            &nbsp;<?= $addressType ?>
                        </label>
                    </div>

                <?php } ?>
                    <div>
                        <label class="form-check-label">
                            <input class="form-check-input user_addressTypes" name="address_type" id="btn_add_new_address" type="radio">
                            &nbsp;Add New Address
                        </label>
                    </div>
                    
                
            </div>
                
            
            <br>
            <input type="text" 
                id="address_id" 
                name="address_id" 
                value="<?= isset($preselectedAddressData['id']) 
                    ? $preselectedAddressData['id'] : "" ?>">
            <label for='region'>Region*</label>
            <div class="input-group mb-3">
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

            <label for='province'>Province*</label>
            <div class="input-group mb-3">
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
       
            <label for='cityMun'>City or Municipality*</label>
            <div class="input-group form-group">
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
         
            <label for='barangay'>Barangay*</label>
            <div class="input-group form-group">
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

            <label for='building'>Street, Bldg., Unit No., etc.*</label>
            <div class="input-group form-group">
                <input type="text" 
                    class='form-control' 
                    id='streetBldgUnit' 
                    value="<?= isset($preselectedAddressData['street_bldg_unit']) 
                        ? $preselectedAddressData['street_bldg_unit'] 
                        : "" ?>">
            </div>

            <label for='building'>Landmark</label>
            <div class="input-group mb-5 form-group">
                <input type="text" class='form-control' id='landmark'
                    value="<?= isset($preselectedAddressData['landmark']) 
                            ? $preselectedAddressData['landmark'] 
                            : "" ?>">
            </div>

            
            <!-- display shipping fee somewhere -->

            <label>Address Type*</label>
            <div class="input-group mb-5">
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
				

            <p id="shipping_error_message"></p>

            <!-- if input type is submit, this will automatically submit input to users.php hence change this to button, type to button and remove value SO THAT you can employ validation -->
            <!-- indicate id for button -->

            <div class="d-flex justify-content-center mb-5">

                <a class="mr-5 modal-link-big" data-url='../partials/templates/cart_modal.php' role='button'>
                    <i class="fas fa-3x fa-arrow-circle-left"></i>
                </a>

                <a data-url='../partials/templates/order_summary_modal.php' id='btn_add_address' role='button'>
                    <i class="fas fa-3x fa-arrow-circle-right"></i>
                </a>

            </div>
                  

        </form>
                        


    