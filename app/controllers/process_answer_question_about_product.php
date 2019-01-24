<?php 
require_once '../../config.php';

if(isset($_POST['userId'])){
    
    $qaId = $_POST['qaId'];
    $answer = $_POST['answer'];

    $sql = "UPDATE tbl_questions_answers SET answer=?,date_answered=NOW() WHERE id=?";
    $statement = $conn->prepare($sql);
    $statement->execute([$answer,$qaId]);
    
    // SEND EMAIL TO SELLER
    echo "Your response has been posted.";

} else {
    echo "Your response has not been posted. Please try again.";
}
			
?>