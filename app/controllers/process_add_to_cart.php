<?php

session_start(); 
require_once "connect.php";

if (isset($_POST['productId'])) {

    $cartSession = $_SESSION['cart_session'];
    $productId = $_POST['productId'];

    $quantity = 1;
    $sql = " SELECT * FROM tbl_carts WHERE cart_session='$cartSession' AND item_id=$productId";
	$result = mysqli_query($conn, $sql);
    $count = mysqli_num_rows($result);
    
    if($count) {
        $row = mysqli_fetch_assoc($result);
        $quantity = $row['quantity'] + 1;
        $sql = " UPDATE tbl_carts SET quantity=$quantity WHERE cart_session='$cartSession'";
        $result = mysqli_query($conn, $sql);
    } else {
        $sql = " INSERT INTO tbl_carts ( dateCreated, item_id, quantity, cart_session) VALUES (now(), $productId, $quantity, '$cartSession') ";
        $result = mysqli_query($conn, $sql);
    }

    $sql = " SELECT * FROM tbl_carts WHERE cart_session='$cartSession'";
	$result = mysqli_query($conn, $sql);
    $count = mysqli_num_rows($result);

    // var_dump($count); die();
    echo $count;

    
} 





