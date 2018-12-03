<?php

session_start(); 
require_once "connect.php";

if (isset($_POST['productId'])) {

    $userId = $_SESSION['id'];
    $productId = $_POST['productId'];
    $rating = $_POST['rating'];

    // add order status(purchased or not) to tbl_carts later on
    $sql = " SELECT * FROM tbl_ratings WHERE `user_id`=$userId AND product_id=$productId ";
	$result = mysqli_query($conn, $sql);
    $count = mysqli_num_rows($result);
    
    // if account exists
    if($count) {
        $sql = " UPDATE tbl_ratings SET product_rating=$rating, date_given='now()' WHERE product_id=$productId AND `user_id`=$userId ";
        $result = mysqli_query($conn, $sql);
    } else {
        $sql = " INSERT INTO tbl_ratings ( product_rating, `user_id`, product_id, date_given) VALUES ($rating, $userId, $productId, now()) ";
        $result = mysqli_query($conn, $sql);
    }

    //get updated number of reviews 
    $sql = " SELECT * FROM tbl_ratings WHERE product_id=$productId ";
	$result = mysqli_query($conn, $sql);
    $numberOfReviews = mysqli_num_rows($result);

    //get user's rating for this product
    $sql = " SELECT * FROM tbl_ratings WHERE `user_id`=$userId AND product_id=$productId ";
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_assoc($result);
    $rating = $row['product_rating'];

    $response = [];
    $response = ['reviewCount' => $numberOfReviews, 'userRating' => $rating];

    echo json_encode($response);

}

