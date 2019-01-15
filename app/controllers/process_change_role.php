<?php
require_once '../../config.php';


if(isset($userId)) {

    $userId = $_POST['userId'];
    $role = $_POST['role'];

    if($role == 'admin' || $roller == 'user') {

        $sql = " UPDATE tbl_users SET userType = ? WHERE id = ? ";
        $statement = $conn->prepare($sql);
        $statement->execute([$role,$userId]);

        echo "success";

    } else {

        $sql = " UPDATE tbl_users SET isSeller = 'yes' WHERE id = ? ";
        $statement = $conn->prepare($sql);
        $statement->execute([$userId]);

        echo "success";

    }

}
