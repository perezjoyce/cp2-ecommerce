<?php 
  require_once '../../config.php';

  if(isset($_POST['faqId'])){
      $faqId = $_POST['faqId'];

    $sql = "DELETE FROM tbl_questions_answers WHERE id = ?";
    $statement = $conn->prepare($sql);
    $statement->execute([$faqId]);

    echo "success";
  } else {
      echo "variationId is not defined";
  }

?>