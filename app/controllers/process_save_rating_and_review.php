<?php
require_once '../sources/pdo/src/PDO.class.php';
require_once "connect.php";
session_start();

if(isset($_POST['ratingId'])){
    $ratingId = $_POST['ratingId'];
    $ratingScore = $_POST['ratingScore'];
    $productReview = $_POST['productReview'];
    $date = date('Y-m-d H:i:s');
    $final = 1;

    $sql = " UPDATE tbl_ratings SET product_rating=?, product_review=?, date_given=? WHERE id = ? ";
    $statement = $conn->prepare($sql);
    $statement->execute([$ratingScore, $productReview, $date, $ratingId]);

    $sql = " UPDATE tbl_rating_images SET is_final = ? WHERE rating_id = ? ";
    $statement = $conn->prepare($sql);
    $statement->execute([$final, $ratingId]);

    echo "success";

}



?>