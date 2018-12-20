<?php
	session_start();
	require_once "connect.php";
	require_once "../controllers/functions.php";

	$categoryId = $_POST['categoryId'];
    
    $sql = "SELECT * FROM tbl_categories WHERE id = ?";
            $statement = $conn->prepare($sql);
            $statement->execute([$categoryId]);
            $row = $statement->fetch();
            $name = $row['name'];
            $parentCategoryId = $row['parent_category_id'];

            if($parentCategoryId != null) {
                echo "
                    <a href='#' class='text-purple'>
                        <i class='fas fa-angle-right'></i> 
                        &nbsp;$name&nbsp;
                    </a>";
            }
            

            