<?php 
  require_once '../sources/pdo/src/PDO.class.php';
  require_once "connect.php";
//   require_once '../../controllers/functions.php';

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