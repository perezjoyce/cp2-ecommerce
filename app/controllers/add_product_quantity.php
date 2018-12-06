<?php
session_start(); 
require_once "connect.php";

$cartSession = $_SESSION['cart_session'];
$quantity = $_POST['quantity'];
$productId = $_POST['productId'];
$sql = " UPDATE tbl_carts SET quantity=? WHERE cart_session=? AND item_id=? ";
$statement = $conn->prepare($sql);
$statement->execute([$quantity, $cartSession, $productId]);