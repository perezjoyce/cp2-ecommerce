<?php

session_start(); 
require_once "connect.php";

if (isset($_POST['productId'])) {

    $cartSession = $_SESSION['cart_session'];
    $productId = $_POST['productId'];

    $quantity = 1;
    $sql = " SELECT * FROM tbl_carts WHERE cart_session=? AND item_id=?";
	$statement = $conn->prepare($sql);
	$statement->execute([$cartSession, $productId]);
    $count = $statement->rowCount();
    
    if($count) {
        $row = $statement->fetch();
        $quantity = $row['quantity'] + 1;
        $sql = " UPDATE tbl_carts SET quantity=? WHERE cart_session=? ";
        $statement = $conn->prepare($sql);
	    $statement->execute([$quantity, $cartSession]);
    } else {
        $sql = " INSERT INTO tbl_carts ( dateCreated, item_id, quantity, cart_session) VALUES (now(), ?, ?, ?) ";
        $statement = $conn->prepare($sql);
	    $statement->execute([$productId, $quantity, $cartSession]);
    }

    $sql = " SELECT * FROM tbl_carts WHERE cart_session=? ";
	$statement = $conn->prepare($sql);
	$statement->execute([$cartSession]);
    $count = $statement->rowCount();

    // var_dump($count); die();
    echo $count;

    
} 





