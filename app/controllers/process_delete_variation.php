<?php 
  require_once '../../config.php';

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