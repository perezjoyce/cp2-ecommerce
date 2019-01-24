<?php 
require_once '../../config.php';

if(isset($_POST['qaId'])){
    
    $qaId = $_POST['qaId'];
    $answer = $_POST['answer'];

    $sql = "UPDATE tbl_questions_answers SET answer=?,date_answered=NOW() WHERE id=?";
    $statement = $conn->prepare($sql);
    $statement->execute([$answer,$qaId]);
    
    // SEND EMAIL TO SENDER
    echo "Your response has been posted.";

} else {
    echo "Error. Please try again.";
}
			
?>