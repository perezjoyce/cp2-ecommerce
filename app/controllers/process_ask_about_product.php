<?php 
require_once '../../config.php';

if(isset($_POST['userId'])){
    
    $userId = $_POST['userId'];
    $productId = $_POST['productId'];
    $question = $_POST['question'];

    $sql = "INSERT INTO tbl_questions_answers (question, product_id, `user_id`, date_asked) VALUES (?, ?, ?, NOW())";
    $statement = $conn->prepare($sql);
    $statement->execute([$question,$productId, $userId]);
    
    // SEND EMAIL TO SELLER
    echo "Thanks. Your question has been sent.";

} else {
    echo "Question has not been submitted. Please try again.";
}
			
?>