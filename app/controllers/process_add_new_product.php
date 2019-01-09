<?php
 require_once '../../config.php';
 require_once "../sources/class.upload.php";

$response = [];

    if(isset($_POST['newProductId'])){

        $newProductId = $_POST['newProductId'];
        $storeId = $_POST['storeId'];
        $name = $_POST['name'];
        $price = $_POST['price'];
        $categoryId = $_POST['categoryId'];
        $subcategoryId = $_POST['subcategoryId'];
        $brandId = $_POST['brandId'];

        $sql = "SELECT * FROM tbl_items WHERE `name`=? AND id!=? AND store_id=?";
        $statement = $conn->prepare($sql);
        $statement->execute([$name, $newProductId, $storeId]);
        $count = $statement->rowCount();

        if($count){
            $response = ['status'=> 'duplicate'];
            
        } else {
        $sql2 = "UPDATE tbl_items SET `name`=?, price=?, category_id=?,brand_id=?,store_id=? WHERE id =?";
            $statement2 = $conn->prepare($sql2);
            $statement2->execute([$name, $price, $subcategoryId, $brandId, $storeId,$newProductId]);

            //FETCH CATEGORY AND BRAND NAMES
            $sql3 = "SELECT c.name 
                AS 'subcategory_name', b.*, bc.* 
                FROM `tbl_brand_categories` bc 
                JOIN tbl_brands  b 
                JOIN tbl_categories c 
                ON bc.brand_id=b.id 
                AND bc.category_id=c.id 
                WHERE category_id=? 
                AND brand_id = ?";
            $statement3 = $conn->prepare($sql3);
            $statement3->execute([$subcategoryId, $brandId]);
            $row3 = $statement3->fetch();
            $brandName = $row3['brand_name'];
            $subCategoryName = $row3['subcategory_name'];

            //FETCH PARENT CATEGORY NAME
            $sql4 = "SELECT * FROM tbl_categories WHERE id =?";
            $statement4 = $conn->prepare($sql4);
            $statement4->execute([$categoryId]);
            $row4 = $statement4->fetch();
            $categoryName = $row4['name'];

            $response = ['status' => 'success', 'id' => $newProductId, 'name' => 
                $name, 'price' => $price, 'category' => $categoryName, 
                'subcategory' => $subCategoryName, 'brand' => $brandName];
        }
        
    } else {

        $storeId = $_POST['storeId'];
        $name = $_POST['name'];
        $price = $_POST['price'];
        $categoryId = $_POST['categoryId'];
        $subcategoryId = $_POST['subcategoryId'];
        $brandId = $_POST['brandId'];

        $sql5 = "SELECT * FROM tbl_items WHERE `name`=? AND store_id=?";
        $statement5 = $conn->prepare($sql5);
        $statement5->execute([$name, $storeId]);
        $count5 = $statement5->rowCount();

        if($count5)  {
            $response = ['status'=> 'duplicate'];
        } else {
            // INSERT DATA
            $sql6 = "INSERT INTO tbl_items(`name`,price,category_id,brand_id,store_id) VALUES(?,?,?,?,?)";
            $statement6 = $conn->prepare($sql6);
            $statement6->execute([$name, $price, $subcategoryId, $brandId, $storeId]);

            // echo $name;die();
            
            //FETCH LAST INSERTED ID
            $newProductId = $conn->lastInsertId();
            $_SESSION['newProductId'] = $newProductId;

            //FETCH CATEGORY AND BRAND NAMES
            $sql7 = "SELECT c.name 
                AS 'subcategory_name', b.*, bc.* 
                FROM `tbl_brand_categories` bc 
                JOIN tbl_brands  b 
                JOIN tbl_categories c 
                ON bc.brand_id=b.id 
                AND bc.category_id=c.id 
                WHERE category_id=? 
                AND brand_id = ?";
            $statement7 = $conn->prepare($sql7);
            $statement7->execute([$subcategoryId, $brandId]);
            $row7 = $statement7->fetch();
            $brandName = $row7['brand_name'];
            $subCategoryName = $row7['subcategory_name'];

            //FETCH PARENT CATEGORY NAME
            $sql8 = "SELECT * FROM tbl_categories WHERE id =?";
            $statement8 = $conn->prepare($sql8);
            $statement8->execute([$categoryId]);
            $row8 = $statement8->fetch();
            $categoryName = $row8['name'];

            $response = ['status' => 'success', 'id' => $newProductId, 'name' => 
                $name, 'price' => $price, 'category' => $categoryName, 
                'subcategory' => $subCategoryName, 'brand' => $brandName];
        }

        
    }

    
    
    echo json_encode($response);

    ?>