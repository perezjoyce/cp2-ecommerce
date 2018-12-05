<?php

    session_start(); 
    require_once "../../controllers/connect.php";


    $cartSession = $_SESSION['cart_session'];
    if(!$_SESSION['id']) {
        // pass redirect url so that after logging in you will be able to return to the intended page, in this case check-out
        header("location: login_modal.php?redirectUrl=checkout");
    } 
    
    $userId = $_SESSION['id'];
    $sql = " SELECT * FROM tbl_addresses WHERE `user_id` = $userId ";
    $result = mysqli_query($conn,$sql);
    $count = mysqli_num_rows($result);
?>

    

        <form>
            <label class='mb-5'>Shipping Information</label>

            <br>
                
                <!-- IF USER HAS ADDRESS... -->
                <?php if($count) { 
                
                    $sql = " SELECT * tbl_addresses WHERE user_id = $userId ";
                    $sql = " SELECT * FROM tbl_addresses WHERE `user_id` = $userId ";
                    $result = mysqli_query($conn,$sql);
                ?>

                <label>Choose Saved Address Type</label>
                <div class="form-check">

                <?php 
                    while($row = mysqli_fetch_assoc($result)){ 
                        $addressType = $row['addressType'];
                ?>
                    <input class="form-check-input" type="checkbox" value="#">
                    <label class="form-check-label">
                        <?= $addressType ?>
                    </label>
                <?php } ?>
                    <br>
                    <input class="form-check-input" type="checkbox" value="others">
                    <label class="form-check-label">
                        others
                    </label>
                
            </div>
                <?php } else { ?>
            
            <br>
            <label for='region'>Region*</label>
            <div class="input-group mb-3">
                <select class="custom-select" id="region" onchange="region">
                    <option id='region-initial-selected' selected="...">...</option>
                    <?php 
                        $sql = " SELECT * FROM tbl_regions ";
                        $result = mysqli_query($conn, $sql);
                        while($row = mysqli_fetch_assoc($result)){ 
                            $region = $row['regDesc'];
                            $regionId = $row['id'];
                            $regCode = $row['regCode'];
                    ?>
                    <option data-id='<?=$region?>' id='region-option' value='<?= $regionId ?>'>
                        <?= $region ?>
                    </option>
                <?php } ?>
                </select>
            </div>

            <label for='province'>Province*</label>
            <div class="input-group mb-3">
                <select class="custom-select" id="province" data-id='<?= $regionId?>'>
                    <option id='province-initial-selected' selected="...">...</option>
                    <?php 
                        $sql = " SELECT * FROM tbl_provinces WHERE regCode = $regCode";
                        $result = mysqli_query($conn, $sql);
                        while($row = mysqli_fetch_assoc($result)){ 
                            $province = $row['provDesc'];
                            $provinceId = $row['id'];
                            $provCode = $row['provCode'];
                    ?>
                    <option id='province-option' value='<?= $provinceId ?>'><?= $province ?> </option>
                    <?php } ?>
                </select>
            </div>
       
            <label for='cityMun'>City or Municipality*</label>
            <div class="input-group form-group">
                <select class="custom-select" id="cityMun" data-id='<?= $provinceId ?>'>
                    <option id='cityMun-initial-selected' selected="...">...</option>
                    <?php 
                        $sql = " SELECT * FROM tbl_cities WHERE provCode = $provCode ";
                        $result = mysqli_query($conn, $sql);
                        while($row = mysqli_fetch_assoc($result)){ 
                            $cityMun = $row['citymunDesc'];
                            $cityMunId = $row['id'];
                            $cityMunCode = $row['citymunCode'];
                    ?>
                    <option id='cityMun-option' value='<?= $cityMunId ?>'><?= $cityMun ?></option>
                    <?php } ?>
                </select>
            </div>
         
            <label for='barangay'>Barangay*</label>
            <div class="input-group form-group">
                <select class="custom-select"  id="barangay" data-id='<?= $cityMunId?>'>
                    <option id='brgy-initial-selected' selected="...">...</option>
                    <?php 
                        $sql = " SELECT * FROM tbl_barangays WHERE citymunCode = $cityMunCode ";
                        $result = mysqli_query($conn, $sql);
                        while($row = mysqli_fetch_assoc($result)){ 
                            $barangay = $row['brgyDesc'];
                            $barangayId = $row['id'];
                    ?>
                    <option id='brgy-option' value='<?= $barangayId ?>'><?= $barangay ?></option>
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

            <label>Save Address Type As*</label>
            <div class="input-group mb-5">
                <!-- for editing -->
                <select class="custom-select" id="addressType">
                    <option selected="...">...</option>
                    <option value="home">Home</option>
                    <option value="office">Office</option>
                    <option value="others">Others</option>
                </select>
            </div>
				

            <p id="shipping_error_message"></p>

            <!-- if input type is submit, this will automatically submit input to users.php hence change this to button, type to button and remove value SO THAT you can employ validation -->
            <!-- indicate id for button -->
            <div class="d-flex justify-content-center mb-5">

                <a class="mr-5 modal-link" data-url='../partials/templates/cart_modal.php'>
                    <i class="fas fa-3x fa-arrow-circle-left"></i>
                </a>

                <a class="modal-link" data-url='../partials/templates/order_summary_modal.php' id='btn_shipping_info'>
                    <i class="fas fa-3x fa-arrow-circle-right"></i>
                </a>

            </div>
                  

        </form>
                        <?php } ?>


    