<?php 

    session_start(); 
    require_once "../../controllers/connect.php";

    $cartSession = $_SESSION['cart_session'];
    
    $sql = "SELECT c.*, p.img_path, p.name, p.price, p.id as productId
    FROM tbl_carts c 
    JOIN tbl_items p on p.id=c.item_id 
    WHERE cart_session='" . $cartSession. "'";
    $result = mysqli_query($conn, $sql);
?>

<form action="../controllers/process_add_to_cart.php" method="POST" id="form_cart">
    
    <label class="my-5">Your Shopping Cart</label>

    <table class="table table-bordered">
        <tr id="table-header">
            <th> Item </th>
            <th> Unit Price </th>
            <th> Quantity </th>
            <th> Total Price </th>
            <th> Action </th>

        </tr>
        <?php
        $count = mysqli_num_rows($result);
        $totalPrice = 0;
      
        if($count) :
            while($row = mysqli_fetch_assoc($result)){ 
                $userId = $row['user_id'];
                $productId = $row['item_id'];
                $name = $row['name'];
                $price = $row['price'];
                $quantity = $row['quantity'];
                $image = $row['img_path'];
                $totalPrice = $totalPrice + ($price * $quantity);
        ?>
            <tr>
       
                <td> 
                    <?= $name ?>
                    <br>
                    <img class="unitImage" src="<?= $image ?>" style='width:50px;height:50px;'> 
                </td>
                <td>&#8369; <span class="unitPrice"> <?= $price ?> </span> </td>
                <td> <input class='itemQuantity' 
                            type="number" 
                            style='width:50px;' 
                            value="<?= $quantity ?>"
                            data-productid="<?= $productId ?>"
                            min="1" 
                            max="99" 
                            onKeyUp="if(this.value>99){this.value='99';}else if(this.value<1){this.value='1';}"></td>
                <td>&#8369; <span class="totalPrice"> <?= $price * $quantity ?> </span> </td>
                <td> 
                    <a data-productid='<?= $productId ?>' role='button' class="btn_delete_item">
                        Delete
                    </a>
                </td>
            </tr>
        <?php 
            }
        endif;
        ?>

    </table>

    <table class="table table-bordered mb-5">

        <tr>
            <th>Total</th>
            <td colspan="4"> &#8369;<span class="subtotalAmount"><?= $totalPrice ?></span> </td>
        </tr>

    </table>
    


    <p id="error_message"></p>

    <!-- if input type is submit, this will automatically submit input to users.php hence change this to button, type to button and remove value SO THAT you can employ validation -->
    <!-- indicate id for button -->
    <a type="button" class="btn btn-block btn-outline-success mb-5 modal-link" data-url='../partials/templates/checkout_modal.php' id="btn_checkout">
        CHECK OUT
    </a>

</form>