<?php
    
    include_once "connect.php";

	$searchkey = $_GET['searchKey'];

	$sql = "SELECT * FROM tbl_items WHERE name like ?";
	$statement = $conn->prepare($sql);
    $statement->execute(["%$searchkey%"]);
	$count = $statement->rowCount();
	
    if($count) {
        while($row = $statement->fetch()) {
            $productId = $row['id'];
            echo "<a href='product.php?id=$productId' id='$productId'><div class='dropdown-item livesearch-item'>".$row['name']."</div></a>";
        }
    } else {
        echo "<a href='#'><div class='dropdown-item livesearch-item text-secondary'>Sorry, no results were found. :(</div></a>";
    }
?>