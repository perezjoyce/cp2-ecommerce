<?php
require_once '../../config.php';

if (isset($_POST['productId'])) {

    $userId = $_SESSION['id'];
    $productId = $_POST['productId'];

    $sql = " SELECT * FROM tbl_wishlists WHERE user_id=? AND product_id=? ";
	$statement = $conn->prepare($sql);
	$statement->execute([$userId, $productId]);
    $count = $statement->rowCount();

    $response = [];

    if($count) {
        $row = $statement->fetch();
        $sql = " DELETE FROM tbl_wishlists WHERE product_id=? AND user_id=? ";
        $statement = $conn->prepare($sql);
        $statement->execute([$productId, $userId]); 
    } 

    $sql = " SELECT * FROM tbl_wishlists WHERE user_id=? AND product_id=? ";
    $statement = $conn->prepare($sql);
    $statement->execute([$userId, $productId]);
    $userWishCount = $statement->rowCount();

    $sql = " SELECT * FROM tbl_wishlists WHERE product_id=? ";
    $statement = $conn->prepare($sql);
    $statement->execute([$productId]);
    $productWishCount = $statement->rowCount();

    $response = ['userWishCount' => $userWishCount, 'productWishCount' => $productWishCount];
    
    echo json_encode($response);

}