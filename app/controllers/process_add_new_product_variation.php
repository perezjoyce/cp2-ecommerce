<?php

session_start(); 
require_once '../sources/pdo/src/PDO.class.php';
require_once "connect.php";
// require_once "functions.php";

if($_POST['productId']){
    $response = "";

    $productId = $_POST['productId'];
    $variationName = $_POST['variationName'];
    $variationStock = $_POST['variationStock'];

    if(isset($_POST['variationId'])) {
        $variationId = $_POST['variationId'];

        $sql = "SELECT * FROM tbl_variations WHERE variation_name = ? AND id!=? AND product_id=?";
        $statement = $conn->prepare($sql);
        $statement->execute([$variationName, $variationId, $productId]);
        $count = $statement->rowCount();

        if(!$count) {
            $sql2 = "UPDATE tbl_variations SET variation_name=?,variation_stock=? WHERE id=?";
            $statement2 = $conn->prepare($sql2);
            $statement2->execute([$variationName, $variationStock, $variationId]);
            $response = 'success';
        } else {
            $response = 'duplicate';
        }

    } else {
        $sql3 = "SELECT * FROM tbl_variations WHERE variation_name = ? AND product_id =?";
        $statement3 = $conn->prepare($sql3);
        $statement3->execute([$variationName, $productId]);
        $count3 = $statement3->rowCount();

        if($count3) { 
            $response = 'duplicate';
        } else {
            $sql4 = "INSERT INTO tbl_variations(variation_name,variation_stock,product_id) VALUES(?,?,?)";
            $statement4 = $conn->prepare($sql4);
            $statement4->execute([$variationName, $variationStock, $productId]);
            $response = 'success';
        }
    }

    echo $response;

    // $response = [];
    // $response = ['variationName' => $variationName, 'variationStock' => $variationStock];

    // echo json_encode($response);
}

