<?php
require_once '../../config.php';
require_once "../sources/class.upload.php";

$userId = $_SESSION['id'];
$shopName = $_POST['name'];
$description = $_POST['about'];
$fname = $_POST['fname'];
$lname = $_POST['lname'];
$address = $_POST['address'];
$hours = $_POST['hours'];
$standard = $_POST['standard'];
$free = $_POST['free'];
$permit = $_POST['permit'];

//INSERT VALUES
$sql2 = "INSERT INTO tbl_stores(`name`, `description`, store_address,`user_id`,free_shipping_minimum, standard_shipping,`hours`)
        VALUES(?,?,?,?,?,?,?)";
$statement2 = $conn->prepare($sql2);
$statement2->execute([$shopName ,$description, $address, $userId, $free, $standard, $hours]);

//FETCH NEW STORE ID
$storeId = $conn->lastInsertId();

//INSERT PERMIT TO TBL_PERMIT
$target_dir = "../../uploads/" . $userId."/" . $storeId ."/"; // folder
$filename = uniqid(); //RANDOM FILENAME
$uploader = new Upload($_FILES['upload']);
$imageFileType = strtolower(pathinfo($_FILES['upload']['name'], PATHINFO_EXTENSION));

if ($_FILES['upload']['size'] > 2000000) {
    echo "tooLarge";
    exit;
} 

// to limit type of files 
if ($imageFileType != 'jpg' && $imageFileType != 'png' && $imageFileType != 'jpeg') {
    echo "wrongFileType";
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
    
    //$filename = $filename . "." . $imageFileType;
    $sql4 = "INSERT INTO tbl_permits SET permit='uploads/$userId/$filename', store_id = ? ";
    $statement4 = $conn->prepare($sql4);
    $statement4->execute([$storeId]);
}

//UPDATE FIRST NAME AND LASTNAME OF USER AND SET TO IS SELLER= YES
$sql = "UPDATE tbl_users SET first_name=?, last_name=?, isSeller='yes' WHERE id=?";
$statement = $conn->prepare($sql);
$statement->execute([$fname, $lname, $userId]);

echo $storeId;








			