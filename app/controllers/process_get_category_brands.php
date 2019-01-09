<?php
require_once '../../config.php';

$id = $_GET['id'];
if($id) :
    $sql = "SELECT * FROM tbl_categories WHERE id=?";
    $statement = $conn->prepare($sql);
    $statement->execute([$id]);	
    $row = $statement->fetch();
    $parentCategoryId = $row['parent_category_id'];

    if($parentCategoryId !== null) :

        $sql ="SELECT bc.*, b.id as brand_id, c.id as categoryId, c.name, b.brand_name 
            FROM tbl_categories c JOIN tbl_brand_categories bc 
            JOIN tbl_brands b ON bc.category_id=c.id AND bc.brand_id = b.id 
            WHERE category_id = ?";
        $statement = $conn->prepare($sql);
        $statement->execute([$id]);	

        
            while($row = $statement->fetch()):
                $brandName = $row['brand_name'];
                $brandCategoryId = $row['categoryId'];
                $brandId = $row['brand_id'];
                echo "<a href=\"#\" class=\"flex-fill level-3 btn btn-block purple-link text-secondary text-left pl-3 py-2 px-0 sub_category_btn\" data-brandId='".$brandId."' data-id='".$brandCategoryId."'>".$brandName."</a>";
            endwhile;
    endif;
endif;
