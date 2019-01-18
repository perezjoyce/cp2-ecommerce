<?php
require_once '../../config.php';
require "../sources/class.upload.php";


$id = $_SESSION['id']; //userId
$storeId = $_POST['id'];

$target_dir = "../../uploads/" . $id."/" . $storeId ."/"; // folder
$filename = uniqid(); //RANDOM FILENAME
$uploader = new Upload($_FILES['upload']);
$imageFileType = strtolower(pathinfo($_FILES['upload']['name'], PATHINFO_EXTENSION));
//$target_file = $target_dir . basename($filename) . ".". $imageFileType;

//VALIDATION
// to limit file size to 1 MB
if ($_FILES['upload']['size'] > 2000000) {
    // REDIRECTING PAGE WITH ERROR MSG IN URL QUERY STRING
    $errorMsg = urlencode("Sorry, your file is too large.");
    header("Location: ../views/store-profile.php?id=$storeId&uploadError=" . $errorMsg);
    exit;
} 

// to limit type of files 
if ($imageFileType != 'jpg' && $imageFileType != 'png' && $imageFileType != 'jpeg') {
    $errorMsg = urlencode("Only JPG, JPEG and PNG Files are allowed.");
    header("Location: ../views/store-profile.php?id=$storeId&uploadError=" . $errorMsg);
    exit;
}

else {

    $uploader->file_new_name_body = $filename; // rename uploaded file
    $uploader->Process($target_dir); // actual uploading process
   
    // resize uploaded file and copy in another file
    $uploader->file_new_name_body = $filename . "_80x80";
    $uploader->image_resize = true;
    $uploader->image_convert = 'jpg';
    $uploader->image_x = 80;
    $uploader->image_y = 80;
    $uploader->image_ratio_y = false;
    $uploader->image_ratio = true;
    $handle->image_ratio_crop = 'TBLR';
    $uploader->Process($target_dir); // actual uploading of new photo with new size
    if ($uploader->processed) {
        $uploader->Clean();
    }
    
    //SAVE URL IN DATABASE
    $sql = "UPDATE tbl_permits SET permit='uploads/$id/$storeId/$filename' WHERE store_id=?";
    $statement = $conn->prepare($sql);
    $statement->execute([$storeId]);

    //UPDATE STATUS TO PENDING
    $sql = "UPDATE tbl_stores SET with_permit=1 WHERE id = ? ";
    $statement = $conn->prepare($sql);
    $statement->execute([$storeId]);
    $message = "Your file was successfully submitted for review.";
    echo "<script type='text/javascript'>
        alert('$message');
        setTimeout(function(){window.location.href='store-profile.php?id=$storeId'}, 1500);
        </script>";
    // header("Location: ../views/store-profile.php?id=$storeId");
}

