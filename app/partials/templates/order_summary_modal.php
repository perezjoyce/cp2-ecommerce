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
            <label class='my-5'>Your Order Summary</label>

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
                    <th>Total</th>
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



                    <p id="error_message"></p>

                    <!-- if input type is submit, this will automatically submit input to users.php hence change this to button, type to button and remove value SO THAT you can employ validation -->
                    <!-- indicate id for button -->
                    <a type="button" class="btn btn-block btn-outline-success mb-5 modal-link" data-url='../partials/templates/checkout_modal.php' id="btn_checkout">
                        CHECK OUT
                    </a>

        </form>

    
   
