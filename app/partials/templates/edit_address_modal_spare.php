<?php

    session_start(); 
    //require_once "../../controllers/connect.php";

    include_once '../../sources/pdo/src/PDO.class.php';

	//set values
	$host = "localhost";
	$db_username = "root";
	$db_password = "";
	$db_name = "db_demoStoreNew";

	$conn = new PDO("mysql:host=$host;dbname=$db_name",$db_username,$db_password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $cartSession = $_SESSION['cart_session'];
    if(!$_SESSION['id']) {
        // pass redirect url so that after logging in you will be able to return to the intended page, in this case check-out
        header("location: login_modal.php?redirectUrl=checkout");
    } 
    
    $userId = $_SESSION['id'];
    $sql = " SELECT * FROM tbl_addresses WHERE `user_id` = ? ";
    $statement = $conn->prepare($sql);
    $statement->execute([$userId]);
    $count = $statement->rowCount();
?>

    

        <form>
           
                
            <!-- IF USER HAS ADDRESS... -->
            <?php if($count == 0 ) { ?>
            <label class='mb-5'>Add Address</label>
            <br>

            <?php } elseif ($count == 1 || $count == 2 ) { ?>
            <label class='mb-5'>Edit Address</label>
            <br>
            <label>Choose address type to edit...</label>
                <div class="form-check">
                    <?php 
                        while($row = $statement->fetch()){ 
                            $addressType = $row['addressType'];
                    ?>
                        <label class="form-check-label">
                            <input class="form-check-input user_addressTypes" name="address_type" type="radio" value="<?= $row['id'] ?>">
                            <?= $addressType ?>
                        </label>
                        <br>
                    <?php } ?>
                        <br>
                    <input type="button" value="Add New" id="btn_add_new_address">
                </div>
            <?php } else { ?>
                <label class='mb-5'>Edit Address</label>
                <br>
                <label>Choose address type to edit...</label>
                <div class="form-check">
                    <?php 
                        while($row = $statement->fetch()){ 
                            $addressType = $row['addressType'];
                    ?>
                        <label class="form-check-label">
                            <input class="form-check-input user_addressTypes" name="address_type" type="radio" value="<?= $row['id'] ?>">
                            <?= $addressType ?>
                        </label>
                        <br>
                    <?php } ?>
                </div>
            <?php } ?>
            
            <br>

            <input type="hidden" id="address_id" name="address_id">
            <label class="mt-4" for='region'>Region*</label>
            <div class="input-group mb-3">
                <select class="custom-select" id="region" onchange="region">
                    <option id='region-initial-selected' value='...' selected="...">...</option>
                    <?php 
                        $sql = " SELECT * FROM tbl_regions ";
                        $statement = $conn->prepare($sql);
                        $statement->execute();
                        while($row = $statement->fetch()){ 
                            $region = $row['regDesc'];
                            $regionId = $row['id'];
                            $regCode = $row['regCode'];
                    ?>
                    <option data-id='<?=$region?>' value='<?= $regionId ?>'>
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
                        $sql = " SELECT * FROM tbl_provinces WHERE regCode = ? ";
                        $statement = $conn->prepare($sql);
                        $statement->execute([$regCode]);
                        while($row = $statement->fetch()){ 
                            $province = $row['provDesc'];
                            $provinceId = $row['id'];
                            $provCode = $row['provCode'];
                    ?>
                    <option class='province-option' value='<?= $provinceId ?>'><?= $province ?> </option>
                    <?php } ?>
                </select>
            </div>
       
            <label for='cityMun'>City or Municipality*</label>
            <div class="input-group form-group">
                <select class="custom-select" id="cityMun" data-id='<?= $provinceId ?>'>
                    <option id='cityMun-initial-selected' value='...' selected="...">...</option>
                    <?php 
                        $sql = " SELECT * FROM tbl_cities WHERE provCode = ? ";
                        $statement = $conn->prepare($sql);
                        $statement->execute([$provCode]);
                        while($row = $statement->fetch()){ 
                            $cityMun = $row['citymunDesc'];
                            $cityMunId = $row['id'];
                            $cityMunCode = $row['citymunCode'];
                    ?>
                    <option class='cityMun-option' value='<?= $cityMunId ?>'><?= $cityMun ?></option>
                    <?php } ?>
                </select>
            </div>
         
            <label for='barangay'>Barangay*</label>
            <div class="input-group form-group">
                <select class="custom-select"  id="barangay" data-id='<?= $cityMunId?>'>
                    <option id='brgy-initial-selected' value='...' selected="...">...</option>
                    <?php 
                        $sql = " SELECT * FROM tbl_barangays WHERE citymunCode = ? ";
                        $statement = $conn->prepare($sql);
                        $statement->execute([$cityMunCode]);
                        while($row = $statement->fetch()){ 
                            $barangay = $row['brgyDesc'];
                            $barangayId = $row['id'];
                    ?>
                    <option class='barangay-option' value='<?= $barangayId ?>'><?= $barangay ?></option>
                    <?php } ?>
                </select> 
            </div>

            <label for='building'>Street, Bldg., Unit No., etc.*</label>
            <div class="input-group form-group">
                <input type="text" class='form-control' id='streetBldgUnit'>
            </div>

            <label for='building'>Landmark</label>
            <div class="input-group mb-5 form-group">
                <input type="text" class='form-control' id='landmark'>
            </div>

            
            <!-- display shipping fee somewhere -->

            <label>Address Type*</label>
            <div class="input-group mb-5">
                <!-- for editing -->
                <select class="custom-select" id="addressType">
                    <option value='...' selected="...">...</option>
                    <option value="home">Home</option>
                    <option value="office">Office</option>
                    <option value="others">Others</option>
                </select>
            </div>
                
                
        <div class="form-group">
            <label>Password</label>
            <input type="text" class="form-control" id="password" name="password">
            <p class="validation text-danger"></p>
        </div>

            <p id="address_error_message"></p>

            <!-- if input type is submit, this will automatically submit input to users.php hence change this to button, type to button and remove value SO THAT you can employ validation -->
            <!-- indicate id for button -->
           
            <div class="d-flex justify-content-center mb-5">
                <a class='btn btn-block btn-outline-secondary save_address_edit' id='btn_edit_address' role='button'>
                SAVE CHANGES
                </a>
            </div>
                  

        </form>
                        


    