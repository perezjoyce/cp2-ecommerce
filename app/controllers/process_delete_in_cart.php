<?php
session_start(); 
require_once "connect.php";

if (isset($_POST['productId'])) {

    $cartSession = $_SESSION['cart_session'];
    $productId = $_POST['productId'];

    $sql = " SELECT * FROM tbl_carts WHERE cart_session=? AND item_id=? ";
	$statement = $conn->prepare($sql);
	$statement->execute([$cartSession, $productId]);
    $count = $statement->rowCount();

    if($count) {
        $row = $statement->fetch();
        $sql = " DELETE FROM tbl_carts WHERE cart_session=? AND item_id=? ";
        $statement = $conn->prepare($sql);
	    $result = $statement->execute([$cartSession, $productId]);

        echo $result;
    } 

}