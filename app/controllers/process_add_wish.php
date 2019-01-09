<?php
require_once '../../config.php';

    if (isset($_POST['productId'])) {

        $userId = $_SESSION['id'];
        $productId = $_POST['productId'];
        $response =[];
    
        $sql = " SELECT * FROM tbl_wishlists WHERE user_id=? AND product_id=? ";
        $statement = $conn->prepare($sql);
	    $statement->execute([$userId, $productId]);
        $count = $statement->rowCount();
        
        if(!$count) {
             $sql = " INSERT INTO tbl_wishlists ( user_id, product_id ) VALUES ( ?, ? ) ";
             $statement = $conn->prepare($sql);
             $statement->execute([$userId, $productId]);  
        }

        $sql = " SELECT * FROM tbl_wishlists WHERE user_id=? AND product_id=? ";
        $statement = $conn->prepare($sql);
        $statement->execute([$userId, $productId]);
        $userWishCount = $statement->rowCount();

        $sql = " SELECT * FROM tbl_wishlists WHERE product_id=? ";
        $statement = $conn->prepare($sql);
        $statement->execute([$productId]);
        $productWishCount = $statement->rowCount();

        $response = ['userWishCount' => $userWishCount, 'productWishCount' => $productWishCount];
        
        echo json_encode($response);

    }
