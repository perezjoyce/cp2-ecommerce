<?php

session_start(); 
require_once "connect.php";
require_once "functions.php";

if(isset($_POST['name'])){
    $storeId = $_POST['storeId'];
    $name = $_POST['name'];
    $price = $_POST['price'];
    $categoryId = $_POST['categoryId'];
    $subcategoryId = $_POST['subcategoryId'];
    $brand = $_POST['brand'];

    $sql = "INSERT INTO tbl_items ";
    $statement = $conn->prepare($sql);
    $statement->execute();
    $row = $statement->fetch()){
        $parentCategoryId = $row['id']
}