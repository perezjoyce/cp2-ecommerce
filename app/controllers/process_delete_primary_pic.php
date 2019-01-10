<?php 
  require_once '../../config.php';
  require_once "../sources/class.upload.php";

  if(isset($_POST['id'])){
      $id = $_POST['id'];

    $sql = "SELECT * FROM tbl_items WHERE id = ?";
    $statement = $conn->prepare($sql);
    $statement->execute([$id]);
    $imageDetails = $statement->fetch();

    $sql = "UPDATE tbl_items SET img_path=null WHERE id = ?";
    $statement = $conn->prepare($sql);
    $statement->execute([$id]);
    // I AM TEMPORARILY REMOVING THIS THE PIC WON'T GET DELETED FROM THE SERVER PERMANENTLY. HEROKU DOESN'T STORE UPLOADED FILES HENCE I CANNOT ADD NEW ONCE.
    // unlink( "../../" . $imageDetails['img_path'].".jpg");
    // unlink( "../../" . $imageDetails['img_path']."_80x80.jpg");
    echo "success";
  } else {
      echo "productId is undefined";
  }

?>