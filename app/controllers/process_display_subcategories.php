<?php
    require_once '../../config.php';
    
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