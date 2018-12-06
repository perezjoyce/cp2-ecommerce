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

    if($count) {
        $row = $statement->fetch();
        $sql = " DELETE FROM tbl_wishlists WHERE product_id=? AND user_id=? ";
        $statement = $conn->prepare($sql);
        $result = $statement->execute([$productId, $userId]); 

        echo $result;
    } 

}