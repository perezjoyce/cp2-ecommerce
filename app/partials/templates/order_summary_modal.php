<?php

    session_start(); 
    require_once "../../controllers/connect.php";


    $cartSession = $_SESSION['cart_session'];
    if(!$_SESSION['id']) {
        // pass redirect url so that after logging in you will be able to return to the intended page, in this case check-out
        header("location: login_modal.php?redirectUrl=checkout");
    } else {
        
        $sql = "SELECT c.*, p.img_path, p.name, p.price, p.id as productId
        FROM tbl_carts c 
        JOIN tbl_items p on p.id=c.item_id 
        WHERE cart_session='" . $cartSession. "'";
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
        
<?php
        $result = mysqli_query($conn, $sql);
        $count = mysqli_num_rows($result);
        if($count) {
            while($row = mysqli_fetch_assoc($result)){ 
                $productId = $row['productId'];
                $name = $row['name'];
                $price = $row['price'];
                $quantity = $row['quantity'];
                $image = $row['img_path'];
?>

                <tr>
                    
                    <td> 
                        <?= $name ?>
                        <br>
                        <img class="unitImage" src="<?= $image ?>" style='width:50px;height:50px;'> 
                    </td>
                    <td>&#8369; <span class="unitPrice"> <?= $price ?> </span> </td>
                    <td> <?= $quantity ?> </td>
                    <td>&#8369; <span class="totalPrice"> <?= $price * $quantity ?> </span> </td>
                </tr>


<?php
            }
        }
    } 
?>


            </table>

            

            <table class="table table-bordered mb-5">

                <tr>
                    <th>Grand Total</th>
                    <?php
                        $sql = "SELECT c.quantity, p.price, SUM(c.quantity * p.price) FROM tbl_carts c JOIN tbl_items p on p.id=c.item_id 
                                WHERE cart_session='" . $cartSession. "'";
                        $result = mysqli_query($conn, $sql);
                            while($row = mysqli_fetch_assoc($result)){ 
                                $totalPrice = $row['SUM(c.quantity * p.price)'];
                        }     
                    ?>
                    <td colspan="3"> &#8369;<span class="subtotalAmount"> </span> <?= $totalPrice  ?> </td>
                </tr>

            </table>

               <!-- add shipping fee somewhere -->

           

            <label>Preferred Mode of Payment</label>
            <div class="input-group mb-5">
                <!-- for editing -->
                <select class="custom-select" id="modeOfPayment" onchange="modeOfPayment">
                    <option selected="...">...</option>
                    <option value="cod">COD</option>
                    <option value="paypal">Paypal</option>
                    <option value="gcash">G-Cash</option>
                    <option value="perapadala">Pera Padala</option>
                    <option value="bdo">BDO</option>
                </select>
            </div>


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
                <a class="mr-5 modal-link" data-url='../partials/templates/shipping_info_modal.php'>
                    <i class="fas fa-3x fa-arrow-circle-left"></i>
                </a>
                <a class="#" data-url='#'>
                    <i class="fas fa-3x fa-arrow-circle-right"></i>
                </a>
            </div>

        </form>

    
   
