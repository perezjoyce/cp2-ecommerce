<?php 
session_start(); 
require_once "connect.php";
require_once "functions.php";

if(isset($_POST['storeId'])){
    $storeId = $_POST['storeId'];
    $userId = $_SESSION['id'];

    $sql = "SELECT * FROM tbl_followers WHERE user_id =? AND store_id =?";
    $statement = $conn->prepare($sql);
    $statement->execute([$userId, $storeId]);
    $count = $statement->rowCount();

    if(!$count) {
        $sql = "INSERT INTO tbl_followers(`user_id`, store_id) VALUES(?,?)";
        $statement = $conn->prepare($sql);
        $statement->execute([$userId, $storeId]);
    
        echo "followed";

    } else {
        $sql = "DELETE FROM tbl_followers WHERE user_id = ? AND store_id =?";
        $statement = $conn->prepare($sql);
        $statement->execute([$userId, $storeId]);
    
        echo "unfollowed";
    }


} else {
    echo "fail";
}

?>