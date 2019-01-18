<?php
require_once '../../config.php';
require_once "../sources/class.upload.php";

$userId = $_SESSION['id'];
$shopName = $_POST['sname'];
$description = $_POST['about'];
$fname = $_POST['fname'];
$lname = $_POST['lname'];
$address = $_POST['address'];
$hours = $_POST['hours'];
$standard = $_POST['standard'];
$free = $_POST['free'];


//INSERT VALUES
$sql = "INSERT INTO tbl_stores(`name`, `description`, store_address,`user_id`,free_shipping_minimum, standard_shipping,`hours`)
        VALUES(?,?,?,?,?,?,?)";
$statement = $conn->prepare($sql);
$statement->execute([$shopName ,$description, $address, $userId, $free, $standard, $hours]);

//UPDATE FIRST NAME AND LASTNAME OF USER AND SET TO ISSELLER= YES
$sql5 = "UPDATE tbl_users SET first_name=?, last_name=?, isSeller='yes' WHERE id=?";
$statement5 = $conn->prepare($sql5);
$statement5->execute([$fname, $lname, $userId]);

//ECHO STORE ID IF STORE HAS BEEN SET UP
$sql6 = "SELECT * FROM tbl_stores WHERE `user_id`=? ";
    $statement6 = $conn->prepare($sql6);
    $statement6->execute([$userId]);
    $count6 = $statement6->rowCount();
    if($count6) {
        // $response = ['status' => 'tooLarge'];
        echo $storeId;
    }









			