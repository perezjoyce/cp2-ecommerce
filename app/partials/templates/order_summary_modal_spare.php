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
?>
     
            
        <form>
            <label class='mb-5'>Your Order Summary</label>

            <br>

            <label>Items In Cart</label>
            <table class="table table-bordered">
                <tr id="table-header">
                    <th> Item </th>
                    <th> Unit Price </th>
                    <th> Quantity </th>
                    <th> Price </th>

                </tr>
        


                



            </table>

            

            <table class="table table-bordered mb-5">

                
            </table>

               <!-- add shipping fee somewhere -->

           

            <label>Preferred Mode of Payment</label>
            <div class="input-group mb-5">
                <!-- for editing -->
                <select class="custom-select" id="modeOfPayment" onchange="modeOfPayment">
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


            <p id="order_summary_error_message"></p>

            <!-- if input type is submit, this will automatically submit input to users.php hence change this to button, type to button and remove value SO THAT you can employ validation -->
            <!-- indicate id for button -->
            <div class="d-flex justify-content-center mb-5">
                <a class="mr-5 modal-link" data-url='../partials/templates/shipping_info_modal.php' role='button'>
                    <i class="fas fa-3x fa-arrow-circle-left"></i>
                </a>
                <a data-url='../partials/templates/order_confirmation_modal.php' id=btn_order_confirmation role='button'>
                    <i class="fas fa-3x fa-arrow-circle-right"></i>
                </a>
            </div>

        </form>

    
   
