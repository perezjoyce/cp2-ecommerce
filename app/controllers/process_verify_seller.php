<?php
require_once '../../config.php';


if(isset($_POST['userId'])) {

    $userId = $_POST['userId'];
    $storeId = $_POST['storeId'];

    $sql = "SELECT * FROM tbl_permits WHERE store_id=?";
    $statement = $conn->prepare($sql);
    $statement->execute([$storeId]);
    $row = $statement->fetch();
    $permit = $row['permit'];

    if($permit != null) {

        $sql = " UPDATE tbl_stores SET with_permit=2 WHERE id = ? ";
        $statement = $conn->prepare($sql);
        $statement->execute([$storeId]);

        echo "success";
    } else {
        echo "noPermit";
    }


}
