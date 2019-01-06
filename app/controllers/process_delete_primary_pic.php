<?php 
  require_once '../sources/pdo/src/PDO.class.php';
  require_once "connect.php";
  ///require "../../config.php";
//   require_once '../../controllers/functions.php';

  if(isset($_POST['id'])){
      $id = $_POST['id'];

    $sql = "SELECT * FROM tbl_items WHERE id = ?";
    $statement = $conn->prepare($sql);
    $statement->execute([$id]);
    $imageDetails = $statement->fetch();

    $sql = "UPDATE tbl_items SET img_path=null WHERE id = ?";
    $statement = $conn->prepare($sql);
    $statement->execute([$id]);
    unlink( "../../" . $imageDetails['img_path'].".jpg");
    unlink( "../../" . $imageDetails['img_path']."_80x80.jpg");
    echo "success";
  } else {
      echo "productId is undefined";
  }

?>