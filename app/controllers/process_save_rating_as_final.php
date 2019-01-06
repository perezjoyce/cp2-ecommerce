<?php
require_once '../sources/pdo/src/PDO.class.php';
require_once "connect.php";
session_start();

if(isset($_POST['ratingId'])){
    $ratingId = $_POST['ratingId'];
    $ratingScore = $_POST['ratingScore'];
    $final = 1;

    $sql = " UPDATE tbl_ratings SET rating_is_final = ? WHERE id = ? ";
    $statement = $conn->prepare($sql);
    $statement->execute([$final, $ratingId]);

    echo "success";

}



?>