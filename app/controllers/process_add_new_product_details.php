<?php
require_once '../../config.php';

if($_POST['description']){
    $productId = $_POST['productId'];
    $description = $_POST['description'];
    if(isset($_POST['descriptionId'])) {
        $descriptionId = $_POST['descriptionId'];
        $sql = "UPDATE tbl_item_descriptions SET `description`=? WHERE id=?";
        $statement = $conn->prepare($sql);
        $statement->execute([$description, $descriptionId]);
    } else {
        $sql = "INSERT INTO tbl_item_descriptions(`description`,product_id) VALUES(?,?)";
        $statement = $conn->prepare($sql);
        $statement->execute([$description, $productId]);
    }

    echo $description;
}

