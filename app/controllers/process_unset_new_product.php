<?php 
    require_once '../sources/pdo/src/PDO.class.php';
    session_start(); // INITIATE
    $id = $_SESSION['newProductId'];
    unset($_SESSION['newProductId']);
    echo "../views/product.php?id=$id";
?>
