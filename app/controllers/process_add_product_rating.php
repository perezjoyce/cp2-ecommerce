<?php
require_once '../../config.php';

if (isset($_POST['productId'])) {

    $userId = $_SESSION['id'];
    $productId = $_POST['productId'];
    $rating = $_POST['rating'];

    // add order status(purchased or not) to tbl_carts later on
    $sql = " SELECT * FROM tbl_ratings WHERE `user_id`=? AND product_id=? ";
	$statement = $conn->prepare($sql);
	$statement->execute([$userId, $productId]);
    $count = $statement->rowCount();
    
    // if account exists
    if($count) {
        $sql = " UPDATE tbl_ratings SET product_rating=?, date_given='now()' WHERE product_id=? AND `user_id`=? ";
        $statement = $conn->prepare($sql);
	    $statement->execute([$rating, $productId, $userId]);
    } else {
        $sql = " INSERT INTO tbl_ratings ( product_rating, `user_id`, product_id, date_given) VALUES (?, ?, ?, now()) ";
        $statement = $conn->prepare($sql);
	    $statement->execute([$rating, $userId, $productId]);
    }

    //get updated number of reviews 
    $sql = " SELECT * FROM tbl_ratings WHERE product_id=? ";
	$statement = $conn->prepare($sql);
	$statement->execute([$productId]);
    $numberOfReviews = $statement->rowCount();

    //get user's rating for this product
    $sql = " SELECT * FROM tbl_ratings WHERE `user_id`=? AND product_id=? ";
    $statement = $conn->prepare($sql);
	$statement->execute([$userId, $productId]);
    $row = $statement->fetch();
    $rating = $row['product_rating'];

    $response = [];
    $response = [
        'reviewCount' => $numberOfReviews, 
        'userRating' => $rating,
        'aveRating'=> getAveProductReview($conn, $productId)
    ];

    echo json_encode($response);

}

