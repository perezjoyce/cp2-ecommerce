<?php

session_start(); 
require_once "connect.php";
// require_once "functions.php";

if($_POST['productId']){
    $productId = $_POST['productId'];
    $variationName = $_POST['variationName'];
    $variationStock = $_POST['variationStock'];

    if(isset($_POST['variationId'])) {
        $variationId = $_POST['variationId'];

        $sql = "UPDATE tbl_variations SET variation_name=?,variation_stock=? WHERE id=?";
        $statement = $conn->prepare($sql);
        $statement->execute([$variationName, $variationStock, $variationId]);
    } else {
        $sql = "INSERT INTO tbl_variations(variation_name,variation_stock,product_id) VALUES(?,?,?)";
        $statement = $conn->prepare($sql);
        $statement->execute([$variationName, $variationStock, $productId]);
    }

    $response = [];
    $response = ['variationName' => $variationName, 'variationStock' => $variationStock];

    echo json_encode($response);
}

