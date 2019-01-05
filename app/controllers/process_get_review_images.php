<?php
require_once '../sources/pdo/src/PDO.class.php';
require_once "connect.php";

$q = "SELECT * FROM tbl_rating_images WHERE rating_id=?";
$stmt = $conn->prepare($q);
$stmt->execute([$_GET['ratingId']]);

if($stmt->rowCount()) {
    while($row = $stmt->fetch()) :
?>
    <div class='pr-1'>
        <img class="" src="../../<?=$row['url']?>" alt="product_image" height='80' width='100'>    
    </div>
<?php
    endwhile;
}