<?php

session_start(); 
require_once "connect.php";
require_once "functions.php";

if(isset($_POST['newProductId'])){
    
    $newProductId = $_POST['newProductId'];
    $storeId = $_POST['storeId'];
    $name = $_POST['name'];
    $price = $_POST['price'];
    $categoryId = $_POST['categoryId'];
    $subcategoryId = $_POST['subcategoryId'];
    $brandId = $_POST['brandId'];
    $sql = "UPDATE tbl_items SET `name`=?, price=?, category_id=?,brand_id=?,store_id=? WHERE id =?";
        $statement = $conn->prepare($sql);
        $statement->execute([$name, $price, $subcategoryId, $brandId, $storeId,$newProductId]);
    
} else {

    $storeId = $_POST['storeId'];
    $name = $_POST['name'];
    $price = $_POST['price'];
    $categoryId = $_POST['categoryId'];
    $subcategoryId = $_POST['subcategoryId'];
    $brandId = $_POST['brandId'];

    // INSERT DATA
    $sql = "INSERT INTO tbl_items(`name`,price,category_id,brand_id,store_id) VALUES(?,?,?,?,?)";
    $statement = $conn->prepare($sql);
    $statement->execute([$name, $price, $subcategoryId, $brandId, $storeId]);
    
    //FETCH LAST INSERTED ID
    $newProductId = $conn->lastInsertId();
    $_SESSION['newProductId'] = $newProductId;

}

    //FETCH CATEGORY AND BRAND NAMES
    $sql = "SELECT c.name 
            AS 'subcategory_name', b.*, bc.* 
            FROM `tbl_brand_categories` bc 
            JOIN tbl_brands  b 
            JOIN tbl_categories c 
            ON bc.brand_id=b.id 
            AND bc.category_id=c.id 
            WHERE category_id=? 
            AND brand_id = ?";
    $statement = $conn->prepare($sql);
    $statement->execute([$subcategoryId, $brandId]);
    $row = $statement->fetch();
    $brandName = $row['brand_name'];
    $subCategoryName = $row['subcategory_name'];

    //FETCH PARENT CATEGORY NAME
    $sql = "SELECT * FROM tbl_categories WHERE id =?";
    $statement = $conn->prepare($sql);
    $statement->execute([$categoryId]);
    $row = $statement->fetch();
    $categoryName = $row['name'];

    $response = [];
    $response = ['id' => $newProductId, 'name' => 
            $name, 'price' => $price, 'category' => $categoryName, 
            'subcategory' => $subCategoryName, 'brand' => $brandName];
    
    echo json_encode($response);

    ?>