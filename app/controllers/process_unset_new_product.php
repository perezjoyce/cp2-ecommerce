<?php 
    require_once '../sources/pdo/src/PDO.class.php';
    session_start(); // INITIATE
    if(isset($_SESSION['newProductId'])){
        $id = $_SESSION['newProductId'];
        unset($_SESSION['newProductId']);
        echo "../views/product.php?id=$id";
    } else {
        $id = $_GET['productid'];
        echo "../views/product.php?id=$id";
    }
?>
