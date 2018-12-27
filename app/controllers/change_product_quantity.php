<?php
session_start(); 
require_once "connect.php";
$cartSession = $_SESSION['cart_session'];

if(isset($_POST['value'])) {
    $quantity = $_POST['value'];
    $variationId = $_POST['variationId'];
    $sql = " UPDATE tbl_carts SET quantity=? WHERE cart_session=? AND variation_id=? ";
    $statement = $conn->prepare($sql);
    $statement->execute([$quantity, $cartSession, $variationId]);

    echo "success";
}