<?php
    session_start(); 
    require_once '../sources/pdo/src/PDO.class.php';
    require_once "connect.php";

    if(isset($_POST['address'])){
        $storeId = $_POST['storeId'];
        $address = $_POST['address'];
        $hours = $_POST['hours'];

        $sql = " UPDATE tbl_stores SET `store_address` = ?, `hours` = ? WHERE id = ? ";
        $statement = $conn->prepare($sql);
        $statement->execute([$address, $hours, $storeId]);
        
        $response = [];
        $response = ['where' => 'details', 'address' => $address, 'hours' =>  $hours];
        
      
    }

    if(isset($_POST['standard'])){
        $storeId = $_POST['storeId'];
        $standard = $_POST['standard'];
        $free = $_POST['free'];

        $sql = " UPDATE tbl_stores SET standard_shipping = ?, free_shipping_minimum = ? WHERE id = ? ";
        $statement = $conn->prepare($sql);
        $statement->execute([$standard, $free, $storeId]);
        
        $response = [];
        $response = ['where' => 'fees', 'standard' => $standard, 'free' =>  $free];

    }

    echo json_encode($response);

?>