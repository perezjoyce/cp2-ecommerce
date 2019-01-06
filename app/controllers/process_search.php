<?php
    require_once '../sources/pdo/src/PDO.class.php';
    include_once "connect.php";

	$searchkey = $_GET['searchKey'];

	$sql = "SELECT item.name, item.id, category.name as catName, brand.brand_name 
        FROM tbl_items item
        LEFT JOIN tbl_categories category on item.`category_id`=category.id
        LEFT JOIN tbl_brands as brand on brand.id=item.`brand_id`
        WHERE (item.name like ? ) 
        OR (category.name like ? ) 
        OR (brand.brand_name like ? )";

        // var_dump($sql);die();
	$statement = $conn->prepare($sql);
    $statement->execute(["%$searchkey%", "%$searchkey%", "%$searchkey%"]);
	$count = $statement->rowCount();
	
    if($count) {
        while($row = $statement->fetch()) {
            $productId = $row['id'];
            echo "<a href='product.php?id=$productId' id='$productId'><div class='dropdown-item livesearch-item text-responsive'>".$row['name']."</div></a>";
        }
    } else {
        echo "<a href='index.php'><div class='dropdown-item livesearch-item text-secondary text-responsive'>Sorry, no results were found. :(</div></a>";
    }
?>
