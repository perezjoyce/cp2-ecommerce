<?php
	session_start();
	require_once "connect.php";
	require_once "../controllers/functions.php";

	$brandId = $_POST['brandId'];
    
    $sql = "SELECT * FROM tbl_brands WHERE id = ?";
            $statement = $conn->prepare($sql);
            $statement->execute([$brandId]);
            $row = $statement->fetch();
            $name = $row['brand_name'];
           
            
            echo "
                <a href='#' class='text-purple'>
                    <i class='fas fa-angle-right'></i> 
                    &nbsp;$name&nbsp;
                </a>";
            
            

            