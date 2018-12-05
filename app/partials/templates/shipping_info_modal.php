<?php

    session_start(); 
    require_once "../../controllers/connect.php";


    $cartSession = $_SESSION['cart_session'];
    if(!$_SESSION['id']) {
        // pass redirect url so that after logging in you will be able to return to the intended page, in this case check-out
        header("location: login_modal.php?redirectUrl=checkout");
    } 

    ?>

    <form>
            <label class='mb-5'>Shipping Information</label>
            <!-- QUERY IF EXISTING. IF YES, CHOOSE BETWEEN OFFICE, HOME AND OTHERS, IF NO, SHOW BELOW -->
            <br>
            <label for='region'>Region</label>
            <div class="input-group mb-3">
                <select class="custom-select" id="region" onchange="region">
                    <option selected="...">...</option>
                    <?php 
                        $sql = " SELECT * FROM tbl_regions ";
                        $result = mysqli_query($conn, $sql);
                        while($row = mysqli_fetch_assoc($result)){ 
                            $region = $row['regDesc'];
                            $regionId = $row['id'];
                            $regCode = $row['regCode'];
                    ?>
                    <option value='<?= $regionId ?>'>
                        <?= $region ?>
                    </option>
                <?php } ?>
                </select>
            </div>

            <label for='province'>Province</label>
            <div class="input-group mb-3">
                <select class="custom-select" id="province" data-id='<?= $regionId?>'>
                    <option selected="...">...</option>
                    <?php 
                        $sql = " SELECT * FROM tbl_provinces WHERE regCode = $regCode";
                        $result = mysqli_query($conn, $sql);
                        while($row = mysqli_fetch_assoc($result)){ 
                            $province = $row['provDesc'];
                            $provinceId = $row['id'];
                            $provCode = $row['provCode'];
                    ?>
                    <option id='prov-option' value='<?= $provinceId ?>'><?= $province ?> </option>
                    <?php } ?>
                </select>
            </div>
       
            <label for='cityMun'>City or Municipality</label>
            <div class="input-group form-group">
                <select class="custom-select" id="cityMun" data-id='<?= $provinceId ?>'>
                    <option selected="...">...</option>
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
         
            <label for='barangay'>Barangay</label>
            <div class="input-group form-group">
                <select class="custom-select"  id="barangay" data-id='<?= $cityMunId?>'>
                    <option selected="...">...</option>
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

            <label for='building'>Street, Bldg., Unit No., etc.</label>
            <div class="input-group mb-5 form-group">
                <input type="text" class='form-control' id='other_shipping_info'>
            </div>

            
            <!-- display shipping fee somewhere -->

            <label>Preferred Courier</label>
            <div class="input-group mb-5">
                <!-- for editing -->
                <select class="custom-select" id="modeOfPayment" onchange="modeOfPayment">
                    <option selected="...">...</option>
                    <option value="lbc">LBC</option>
                    <option value="palawan">Palawan Express</option>
                    <option value="2go">2-GO</option>
                </select>
            </div>
				



            <p id="error_message"></p>

            <!-- if input type is submit, this will automatically submit input to users.php hence change this to button, type to button and remove value SO THAT you can employ validation -->
            <!-- indicate id for button -->
            <div class="d-flex justify-content-center mb-5">

                <a class="mr-5 modal-link" data-url='../partials/templates/cart_modal.php'>
                    <i class="fas fa-3x fa-arrow-circle-left"></i>
                </a>

                <a class="modal-link" data-url='../partials/templates/order_summary_modal.php'>
                    <i class="fas fa-3x fa-arrow-circle-right"></i>
                </a>

            </div>
                  

        </form>
    
    