<?php 
  require_once '../sources/pdo/src/PDO.class.php';
  require_once "connect.php";
//   require_once '../../controllers/functions.php';

  if(isset($_POST['productId'])){
      $productId = $_POST['productId'];

    $sql = "SELECT * FROM tbl_items WHERE id = ?";
    $statement = $conn->prepare($sql);
    $statement->execute([$productId]);
    $count = $statement->rowCount();

    if($count) {
        $sql = "DELETE FROM tbl_items WHERE id = ?";
        $statement = $conn->prepare($sql);
        $statement->execute([$productId]);
    } else {
        echo "Product id is undefined";
    }

  }

?>