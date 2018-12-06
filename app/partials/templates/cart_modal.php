<?php 

    session_start(); 
    include_once '../../sources/pdo/src/PDO.class.php';

	//set values
	$host = "localhost";
	$db_username = "root";
	$db_password = "";
	$db_name = "db_demoStoreNew";

	$conn = new PDO("mysql:host=$host;dbname=$db_name",$db_username,$db_password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $cartSession = $_SESSION['cart_session'];
    
    $sql = "SELECT c.*, p.stocks, p.img_path, p.name, p.price, p.id as productId
    FROM tbl_carts c 
    JOIN tbl_items p on p.id=c.item_id 
    WHERE cart_session=?";
    //$result = mysqli_query($conn, $sql);
    $statement = $conn->prepare($sql);
    $statement->execute([$cartSession]);
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
        $count = $statement->rowCount();
        $totalPrice = 0;
      
        if($count) :
            while($row = $statement->fetch()){ 
                $userId = $row['user_id'];
                $productId = $row['item_id'];
                $name = $row['name'];
                $price = $row['price'];
                $quantity = $row['quantity'];
                $image = $row['img_path'];
                $stocks = $row['stocks'];
                $totalPrice = $totalPrice + ($price * $quantity);
        ?>
            <tr>
       
                <td> 
                    <?= $name ?>
                    <br>
                    <a href="product.php?id=<?= $productId ?>">
                        <img class="unitImage" src="<?= $image ?>" style='width:50px;height:50px;'> 
                    </a>
                </td>
                <td>&#8369; <span class="unitPrice"> <?= $price ?> </span> </td>
                <td> <input class='itemQuantity' 
                            type="number" 
                            style='width:50px;' 
                            value="<?= $quantity ?>"
                            data-productid="<?= $productId ?>"
                            min="1" 
                            max="<?= $stocks ?>" >
                    <div>
                            <?php 
                            if ($stocks == 1) {
                                echo "Only 1 left!";
                            } else {
                                echo "$stocks stocks left";
                            }
                            ?>
                    </div>
                </td>
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
            <th>Sub-Total</th>
            <td colspan="4"> &#8369;<span class="subtotalAmount"><?= $totalPrice ?></span> </td>
        </tr>

    </table>
    


    <p id="error_message"></p>


     <div class="d-flex justify-content-center mb-5">

        <a class="modal-link" data-url='../partials/templates/shipping_info_modal.php'>
            <i class="fas fa-3x fa-arrow-circle-right"></i>
        </a>

    </div>

</form>