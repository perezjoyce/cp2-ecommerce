<?php 
require_once '../../config.php';

if(isset($_SESSION['id'])){
    $userId = $_SESSION['id'];

    // CHECK IF USER IS SELLER
    $sql = "SELECT * FROM tbl_users WHERE isSeller = 'yes' AND id = ?";
            $statement = $conn->prepare($sql);
            $statement->execute([$userId]);
            $count = $statement->rowCount();
            if($count){
                echo "seller";

                // CHANAGE STATUS TO APPLYING FOR DEACTIVATION
                $sql2 =  "UPDATE tbl_users SET `status` = 2 WHERE id = ?";
                $statement2 = $conn->prepare($sql2);
                $statement2->execute([$userId]);
            } else {
                
                // CHANAGE STATUS TO DEACTIVATED FOR NON-SELLERS
                $sql3 =  "UPDATE tbl_users SET `status` = 0, first_name = 
                            NULL, last_name = 
                            NULL, profile_pic = 
                            NULL WHERE id = ?";
                $statement3 = $conn->prepare($sql3);
                $statement3->execute([$userId]);
                unset($_SESSION["cart_session"]);
                unset($_SESSION["id"]);

                echo "success";
            }
}

?>