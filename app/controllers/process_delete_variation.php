<?php 
  require_once '../sources/pdo/src/PDO.class.php';
  require_once "connect.php";
//   require_once '../../controllers/functions.php';

  if(isset($_POST['variationId'])){
      $variationId = $_POST['variationId'];

    $sql = "DELETE FROM tbl_variations WHERE id = ?";
    $statement = $conn->prepare($sql);
    $statement->execute([$variationId]);

    echo "success";
  } else {
      echo "variationId is not defined";
  }

?>