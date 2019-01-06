<?php
require_once '../sources/pdo/src/PDO.class.php';
require_once "connect.php";
require_once "functions.php";
require_once "../sources/class.upload.php";

$id = $_POST['rating_id'] ?: null;

$target_dir = "../../uploads/review_images/"; // folder
// $filename = $_FILES['upload']['name'];
$filename = uniqid(); //RANDOM FILENAME
//$uploader = new Upload($_FILES['upload']);
$imageFileType = strtolower(pathinfo($_FILES['upload']['name'], PATHINFO_EXTENSION));
//$target_file = $target_dir . basename($filename) . ".". $imageFileType;

// check if image is jpeg

//VALIDATION
// to limit file size to 1 MB
if ($_FILES['upload']['size'] > 1000000) {
    // REDIRECTING PAGE WITH ERROR MSG IN URL QUERY STRING
    $errorMsg = urlencode("Sorry, your file is too large.");
    header("Location: ../views/profile.php?id=$id&uploadError=" . $errorMsg);
    exit;
} 

else {


    $imgPath = $target_dir . $filename . ".jpg";
    if (move_uploaded_file($_FILES['upload']['tmp_name'], $imgPath)) {
        $sql = "INSERT INTO tbl_rating_images (url, rating_id) VALUES (?, ?) ";
        $statement = $conn->prepare($sql);
        
        $imgPath = "uploads/review_images/".$filename . ".jpg";
        $statement->execute([$imgPath, $id]);
    } else {
       echo "File was not uploaded";
    }
    
    // $uploader = new Upload($_FILES['upload']);
    // //$uploader->file_new_name_body = $filename; // rename uploaded file
    // $uploader->Process($target_dir);
    // //if($uploader->uploaded) {
        
    //     //$uploader->process($target_dir); // actual uploading of new photo with new size
    //     if ($uploader->processed) {
    //         $uploader->Clean();
    //     }
        
        //$filename = $filename . "." . $imageFileType;
       
    //}
    
    // $statement = $conn->prepare($sql);
    // $statement->execute([$id]);
    // header("Location: ../views/profile.php?id=$id");
}

