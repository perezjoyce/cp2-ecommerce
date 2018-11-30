<?php
session_start(); 
require_once "connect.php";

if (isset($_POST['productId'])) {

    $userId = $_SESSION['id'];
    $productId = $_POST['productId'];

    $sql = " SELECT * FROM tbl_wishlists WHERE user_id='$userId' AND product_id=$productId ";
	$result = mysqli_query($conn, $sql);
    $count = mysqli_num_rows($result);

    if($count) {
        $row = mysqli_fetch_assoc($result);
        $sql = " DELETE FROM tbl_wishlists WHERE product_id=$productId ";
        $result = mysqli_query($conn, $sql);

        echo $result;
    } 

}