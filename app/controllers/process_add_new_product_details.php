<?php

session_start(); 
require_once "connect.php";
require_once "functions.php";
require_once '../sources/pdo/src/PDO.class.php';

if($_POST['description']){
    $productId = $_POST['productId'];
    $description = $_POST['description'];
    if(isset($_POST['descriptionId'])) {
        $descriptionId = $_POST['descriptionId'];
        $sql = "UPDATE tbl_item_descriptions SET `description`=? WHERE id=?";
        $statement = $conn->prepare($sql);
        $statement->execute([$description, $descriptionId]);
    } else {
        $sql = "INSERT INTO tbl_item_descriptions(`description`,product_id) VALUES(?,?)";
        $statement = $conn->prepare($sql);
        $statement->execute([$description, $productId]);
    }

    echo $description;
}

