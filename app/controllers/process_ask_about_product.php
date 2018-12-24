<?php 

session_start(); 
require_once "connect.php";

if(isset($_POST['userId'])){
    
    $userId = $_POST['userId'];
    $productId = $_POST['productId'];
    $question = $_POST['question'];

    $sql = "INSERT INTO tbl_questions_answers (question, product_id, `user_id`) VALUES (?, ?, ? )";
    $statement = $conn->prepare($sql);
    $statement->execute([$question,$productId, $userId]);
    
    echo "Thanks. You will be notified once seller responds.";

} else {
    echo "Question has not been submitted. Please try again.";
}
			
?>