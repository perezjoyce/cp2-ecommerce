<?php
require_once '../../config.php';
require_once "../sources/class.upload.php";

$userId = $_SESSION['id'];
$shopName = $_POST['sname'];
$description = $_POST['about'];
$fname = $_POST['fname'];
$lname = $_POST['lname'];
$address = $_POST['address'];
$hours = $_POST['hours'];
$standard = $_POST['standard'];
$free = $_POST['free'];
$permit = $_POST['permit'];
$logo = $_POST['logo'];
// $response =[];

//INSERT VALUES
$sql = "INSERT INTO tbl_stores(`name`, `description`, store_address,`user_id`,free_shipping_minimum, standard_shipping,`hours`)
        VALUES(?,?,?,?,?,?,?)";
$statement = $conn->prepare($sql);
$statement->execute([$shopName ,$description, $address, $userId, $free, $standard, $hours]);

//FETCH NEW STORE ID
$sql2 = "SELECT id FROM tbl_stores ORDER BY id DESC LIMIT 1 ";
    $statement2 = $conn->prepare($sql2);
    $statement2->execute([$userId]);
    $row2 = $statement2->fetch();
    $storeId = $row2['id'];

//INSERT LOGO
$target_dir = "../../uploads/". $userId ."/". $storeId ."/"; // folder
$filename = uniqid(); //RANDOM FILENAME
$uploader = new Upload($_FILES['upload']);
$imageFileType = strtolower(pathinfo($_FILES['upload']['name'], PATHINFO_EXTENSION));

if ($_FILES['upload']['size'] > 2000000) {
    echo "tooLarge";
    // $response = ['status' => 'tooLarge'];
    exit;
} 

// to limit type of files 
// if ($imageFileType != 'jpg' || $imageFileType != 'png' || $imageFileType != 'jpeg') {
//     echo "wrongFileType";
//     exit;
// }

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
    $sql3 = "UPDATE tbl_stores SET logo='uploads/$userId/$storeId/$filename' WHERE id=? AND `user_id` =? ";
    $statement3 = $conn->prepare($sql3);
    $statement3->execute([$storeId, $userId]);
}

//INSERT PERMIT TO TBL_PERMIT
$target_dir2 = "../../uploads/". $userId ."/". $storeId ."/"; // folder
$filename2 = uniqid(); //RANDOM FILENAME
$uploader2 = new Upload($_FILES['upload']);
$imageFileType2 = strtolower(pathinfo($_FILES['upload']['name'], PATHINFO_EXTENSION));

if ($_FILES['upload']['size'] > 2000000) {
    echo "tooLarge";
    // $response = ['status' => 'tooLarge'];
    exit;
} 

// to limit type of files 
// if ($imageFileType != 'jpg' || $imageFileType != 'png' || $imageFileType != 'jpeg') {
//     echo "wrongFileType";
//     exit;
// }

else {
    $uploader2->file_new_name_body = $filename2; // rename uploaded file
    $uploader2->Process($target_dir2); // actual uploading process
    
    // resize uploaded file and copy in another file
    $uploader2->file_new_name_body = $filename2 . "_80x80";
    $uploader2->image_resize = true;
    $uploader2->image_convert = 'jpg';
    $uploader2->image_x = 80;
    $uploader2->image_y = 80;
    $uploader2->image_ratio_y = false;
    $uploader2->image_ratio = true;
    $handle2->image_ratio_crop = 'TBLR';
    $uploader2->Process($target_dir2); // actual uploading of new photo with new size
    if ($uploader2->processed) {
        $uploader2->Clean();
    }
    
    $sql4 = "INSERT INTO tbl_permits(permit,store_id) VALUES('uploads/$userId/$storeId/$filename',?) ";
    $statement4 = $conn->prepare($sql4);
    $statement4->execute([$storeId]);
}

//UPDATE FIRST NAME AND LASTNAME OF USER AND SET TO ISSELLER= YES
$sql5 = "UPDATE tbl_users SET first_name=?, last_name=?, isSeller='yes' WHERE id=?";
$statement5 = $conn->prepare($sql5);
$statement5->execute([$fname, $lname, $userId]);

//ECHO STORE ID IF STORE HAS BEEN SET UP
$sql6 = "SELECT * FROM tbl_stores WHERE `user_id`=? ";
    $statement6 = $conn->prepare($sql6);
    $statement6->execute([$userId]);
    $count6 = $statement6->rowCount();
    if($count6) {
        // $response = ['status' => 'tooLarge'];
        echo $storeId;
    }









			