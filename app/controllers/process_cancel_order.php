<?php 
require_once '../../config.php';

if(isset($_POST['cartSession'])) {
    $cartSession = $_POST['cartSession'];
    $storeId = $_POST['storeId'];

    $sql = "SELECT c.*, i.store_id 
            FROM tbl_carts c 
            JOIN tbl_variations v 
            JOIN tbl_items i 
            ON v.product_id=i.id 
            AND c.variation_id=v.id 
            WHERE cart_session = ?
            AND store_id = ?";
    $statement = $conn->prepare($sql);
    $statement->execute([$cartSession, $storeId]);
    $count = $statement->rowCount();

   if($count){ 
        while($row = $statement->fetch()){  
            $cartItemId = $row['id'];
            $sql2 = "UPDATE tbl_carts SET status_id = 4 WHERE id=?";
            $statement2 = $conn->prepare($sql2);
           $statement2->execute([$cartItemId]);
        }

        echo "success";
    }
    

}
