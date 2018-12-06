<?php

    session_start(); 
    require_once "connect.php";

    if (isset($_POST['productId'])) {

        $userId = $_SESSION['id'];
        $productId = $_POST['productId'];

    
        $sql = " SELECT * FROM tbl_wishlists WHERE user_id=? AND product_id=? ";
        $statement = $conn->prepare($sql);
	    $statement->execute([$userId, $productId]);
        $count = $statement->rowCount();
        
        if(!$count) {
             $sql = " INSERT INTO tbl_wishlists ( date_added, user_id, product_id ) VALUES ( now(), ?, ? ) ";
             $statement = $conn->prepare($sql);
             $statement->execute([$userId, $productId]);  
        }

        $sql = " SELECT * FROM tbl_wishlists WHERE user_id=? AND product_id=? ";
        $statement = $conn->prepare($sql);
        $statement->execute([$userId, $productId]);
        $count = $statement->rowCount();

        echo $count;

    }
