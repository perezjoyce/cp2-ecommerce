<?php 
  require_once '../../config.php';
  require_once "../sources/class.upload.php";

  if(isset($_POST['id'])){
      $id = $_POST['id']; 

    $sql = "SELECT * FROM tbl_product_images WHERE id =?";
    $statement = $conn->prepare($sql);
    $statement->execute([$id]);
    $row = $statement->fetch();

    // I AM TEMPORARILY REMOVING THIS THE PIC WON'T GET DELETED FROM THE SERVER PERMANENTLY. HEROKU DOESN'T STORE UPLOADED FILES HENCE I CANNOT ADD NEW ONCE.
    // unlink( "../../" . $row['url'].".jpg"); // 
    // unlink( "../../" . $row['url']."_80x80.jpg");

    $sql = "DELETE FROM tbl_product_images WHERE id = ?";
    $statement = $conn->prepare($sql);
    $statement->execute([$id]);

    echo "success";
  } else {
      echo "product image id is undefined";
  }

?>