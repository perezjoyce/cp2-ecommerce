<?php 
require_once '../../config.php';

if(isset($_SESSION['id'])){
    $userId = $_POST['userId'];
    $isSeller = $_POST['isSeller'];
    $status = $_POST['status'];

    // VERIFY IF USER IS SELLER & HAS AN EXISTING STORE
    if($isSeller == "yes" && $status == 2) {
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
                
                // GET STORE ID
                $sql5 = "SELECT * FROM tbl_stores WHERE `user_id` = ?";
                $statement5 = $conn->prepare($sql5);
                $statement5->execute([$userId]);
                $row5 = $statement5->fetch();
                $storeId = $row5['id'];

                // DELETE STORE
                $sql3 =  "DELETE FROM tbl_stores WHERE `user_id` = ?  AND id = ?";
                    $statement3 = $conn->prepare($sql3);
                    $statement3->execute([$userId, $storeId]);

                //VERIFY 
                $sql4 = "SELECT * FROM tbl_stores WHERE `user_id` = ? AND id = ?";
                $statement4 = $conn->prepare($sql4);
                $statement4->execute([$userId, $storeId]);
                $count4 = $statement4->rowCount();
                if(!$count4){
                    echo "success";
                }

            } else {

                    echo "fail";

            }
    } else {
        echo "unauthorized";
    }
}

?>