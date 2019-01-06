<?php
    session_start();
    require_once '../sources/pdo/src/PDO.class.php';
    require_once "connect.php";
    require_once "functions.php";
    require_once "../sources/class.upload.php";

    $id = $_SESSION['id'];
    $storeId = $_POST['id'];
    $productId = $_POST['productid'];

    $target_dir = "../../uploads/" . $id."/" . $storeId ."/" . $productId . "/"; // folder
    // $filename = $_FILES['upload']['name'];
    $filename = uniqid(); //RANDOM FILENAME
    $uploader = new Upload($_FILES['upload']);
    $imageFileType = strtolower(pathinfo($_FILES['upload']['name'], PATHINFO_EXTENSION));
    //$target_file = $target_dir . basename($filename) . ".". $imageFileType;

    //VALIDATION
    // to limit file size to 1 MB
    if ($_FILES['upload']['size'] > 2000000) {
        // REDIRECTING PAGE WITH ERROR MSG IN URL QUERY STRING
        $errorMsg = urlencode("Sorry, your file is too large.");
        header("Location: ../views/store-edit-product.php?id=$storeId&productid=$productId&uploadError=" . $errorMsg);
        exit;
       
    } 

    // to limit type of files 
    if ($imageFileType != 'jpg' && $imageFileType != 'png' && $imageFileType != 'jpeg') {
        $errorMsg = urlencode("Only JPG, JPEG and PNG Files are allowed.");
        header("Location: ../views/store-edit-product.php?id=$storeId&productid=$productId&uploadError=" . $errorMsg);
        exit;
    }

    else {


            // $sql = "SELECT img_path FROM tbl_items WHERE id =?";
            //     $statement = $conn->prepare($sql);
            //     $statement->execute([$newProductId]);
            //     $row = $statement->fetch();
            //     $img_path = $row['img_path'];

            //IF IMG PATH DOESN'T EXIST, INSERT NEW PIC. ELSE, UPDATE PIC
            // if(!$img_path){

                $uploader->file_new_name_body = $filename; // rename uploaded file
                $uploader->process($target_dir); // actual uploading process
                //move_uploaded_file($_FILES['upload']['tmp_name'], $target_file);
                // SET PERMISSION ON FOLDER. TYPE IN TERMINAL : sudo chmod -R  777 app/controllers/uploads/ for file permission for the folder

                // resize uploaded file and copy in another file
                $uploader->file_new_name_body = $filename . "_80x80";
                //$uploader->process($target_dir);
                $uploader->image_resize = true;
                $uploader->image_convert = 'jpg';
                $uploader->image_x = 80;
                $uploader->image_y = 80;
                $uploader->image_ratio_y = false;
                $uploader->image_ratio = true;
                $uploader->image_ratio_crop = 'TBLR';
                $uploader->process($target_dir); // actual uploading of new photo with new size
                if ($uploader->processed) {
                    $uploader->Clean();
                }

                $sql = "SELECT * FROM tbl_product_images WHERE product_id = ? AND is_primary = ?";
                $statement = $conn->prepare($sql);
                $statement->execute([$productId,1]);

                if($statement->rowCount()) {
                    $row = $statement->fetch();
                    unlink( "../../" . $row['url'].".jpg");
                    unlink( "../../" . $row['url']."_80x80.jpg");

                    $sql = "UPDATE tbl_product_images SET `url`='uploads/$id/$storeId/$productId/$filename' WHERE product_id = ? AND is_primary=1";
                    $statement = $conn->prepare($sql);
                    $statement->execute([$productId]);
                } else {
                    $sql = "INSERT INTO tbl_product_images(`url`, `product_id`, is_primary) VALUES(?, ?, 1)";
                    $statement = $conn->prepare($sql);
                    $filePath = "uploads/$id/$storeId/$productId/$filename";
                    $statement->execute([$filePath, $productId]);
                }
                
                header("Location: ../views/store-edit-product.php?id=$storeId&productid=$productId");
                
            // } else {

            // }

           

        
    }
