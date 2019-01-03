<?php

	require_once "connect.php";
    session_start();
    
    if(isset($_POST['subcategoryId'])){
        $subcategoryId = $_POST['subcategoryId'];

        $data = "";

        $sql = "SELECT b.*, bc.* FROM `tbl_brand_categories` bc JOIN tbl_brands  b on bc.brand_id=b.id WHERE category_id=?";
        $statement = $conn->prepare($sql);
        $statement->execute([$subcategoryId]);
        
        while($row = $statement->fetch()){
            // var_dump($row); die();
            $brand_name = $row['brand_name'];
            $category_id = $row['category_id'];
            $brand_id = $row['brand_id'];

            $data .= "<option class='brand-option' value='$brand_id' data-categoryid='$category_id'>$brand_name</option>";
        
        }

        echo $data;

    }