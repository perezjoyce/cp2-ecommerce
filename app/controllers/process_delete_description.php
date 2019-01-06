<?php 
  require_once '../sources/pdo/src/PDO.class.php';
  require_once "connect.php";
//   require_once '../../controllers/functions.php';

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