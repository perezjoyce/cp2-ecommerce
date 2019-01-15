<?php
require_once '../../config.php';


if(isset($_POST['userId'])) {

    $userId = $_POST['userId'];
    $role = $_POST['role'];

    if($role == 'admin' || $role == 'user') {

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
