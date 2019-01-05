<?php
    require_once '../sources/pdo/src/PDO.class.php';
	require_once "connect.php";
    session_start();
    
    if(isset($_POST['categoryId'])){
        $categoryId = $_POST['categoryId'];

        $data = "";

        $sql = "SELECT * FROM tbl_categories WHERE parent_category_id = ?";
        $statement = $conn->prepare($sql);
        $statement->execute([$categoryId]);
        
        while($row = $statement->fetch()){
            $categoryId = $row['id'];
            $subCategoryName = $row['name'];

            $data .= "<option class='subcategory-option' value='$categoryId'>$subCategoryName</option>";
        
        }

        echo $data;

    }