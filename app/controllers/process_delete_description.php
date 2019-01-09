<?php 
 require_once '../../config.php';

  if(isset($_POST['descriptionId'])){
      $descriptionId = $_POST['descriptionId'];

    $sql = "DELETE FROM tbl_item_descriptions WHERE id = ?";
    $statement = $conn->prepare($sql);
    $statement->execute([$descriptionId]);

    echo "success";
  } else {
      echo "descriptionId is not defined";
  }

?>