<?php 
require_once '../../config.php';

if(isset($_SESSION['id'])){
    $userId = $_POST['userId'];
    $isSeller = $_POST['isSeller'];

    // VERIFY IF USER IS SELLER & HAS AN EXISTING STORE
    if($isSeller == "yes") {
    $sql = "SELECT * FROM tbl_users WHERE isSeller = 'yes' AND id = ?";
            $statement = $conn->prepare($sql);
            $statement->execute([$userId]);
            $count = $statement->rowCount();
            if($count){
                // CHANAGE STATUS FROM APPLYING FOR DEACTIVATION TO DEACTIVATED
               $sql2 =  "UPDATE tbl_users SET `status` = 0, first_name = 
                NULL, last_name = 
                NULL, profile_pic = 
                NULL, isSeller = 'no'
                WHERE id = ?";
                    $statement2 = $conn->prepare($sql2);
                    $statement2->execute([$userId]);

                // DELETE STORE
                $sql3 =  "DELETE FROM tbl_stores WHERE `user_id` = ?  AND id = ?";
                    $statement3 = $conn->prepare($sql3);
                    $statement3->execute([$userId, $storeId]);

                //VERIFY 
                $sql4 = "SELECT * FROM tbl_stores WHERE `user_id` = ? AND id = ?";
                $statement4 = $conn->prepare($sql4);
                $statement4->execute([$userId, $storeId]);
                $count = $statement->rowCount();
                if(!$count){
                    echo "success";
                }

            } else {

                    echo "fail";

            }
    } 
}

?>