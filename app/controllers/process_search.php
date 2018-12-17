<?php
    
    include_once "connect.php";

	$searchkey = $_GET['searchKey'];

	$sql = "SELECT * FROM tbl_items WHERE name like ?";
	$statement = $conn->prepare($sql);
    $statement->execute(["%$searchkey%"]);
	$count = $statement->rowCount();
	
    if($count) {
        while($row = $statement->fetch()) {
            $categoryId = $row['id'];
            echo "<a href='catalog.php?id=$categoryId' id='$categoryId'><div class='dropdown-item livesearch-item'>".$row['name']."</div></a>";
        }
    } else {
        echo "<a href='index.php'><div class='dropdown-item livesearch-item text-secondary'>Sorry, no results were found. :(</div></a>";
    }
?>
