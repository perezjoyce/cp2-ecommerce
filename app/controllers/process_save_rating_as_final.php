<?php
require_once '../../config.php';

if(isset($_POST['ratingId'])){
    $ratingId = $_POST['ratingId'];
    $ratingScore = $_POST['ratingScore'];
    $final = 1;

    $sql = " UPDATE tbl_ratings SET rating_is_final = ? WHERE id = ? ";
    $statement = $conn->prepare($sql);
    $statement->execute([$final, $ratingId]);

    echo "success";

}



?>