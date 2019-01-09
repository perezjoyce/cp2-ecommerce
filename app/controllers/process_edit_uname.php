<?php
    require_once '../../config.php';

    if(isset($_SESSION['id'])) {
        
        if (isset($_POST['username'])) {
            $user_id = $_POST['id'];
            $username = $_POST['username']; 


            $sql = "SELECT * FROM tbl_users WHERE username = ? ";
            
            $statement = $conn->prepare($sql);
            $statement->execute([$username]);
            
            $row = $statement->fetch();
            $target_id = $row['id'];
            $count = $statement->rowCount();
                if($count) {
                    if($user_id == $target_id) {
                        echo "sameUser";
                    } else {
                        echo "userExists";
                    }
                } else {
                    echo "success";
                } 
        }else {
            echo "process_edit_uname.php did not receive variable";
            
        }
    }