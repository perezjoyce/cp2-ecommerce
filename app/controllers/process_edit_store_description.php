<?php
require_once '../../config.php';

    if(isset($_POST['storeId'])){
        $storeId = $_POST['storeId'];
        $description = $_POST['description'];

        $sql = " UPDATE tbl_stores SET `description` = ? WHERE id = ? ";
        $statement = $conn->prepare($sql);
        $statement->execute([$description, $storeId]);
        //$row = $statement->fetch();
        //$description = $row['description'];

        echo $description;
    }
   
    
