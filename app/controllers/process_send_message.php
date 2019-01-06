<?php 

session_start(); 
require_once '../sources/pdo/src/PDO.class.php';
require_once "connect.php";


$message = $_POST['message'];
$userId= $_SESSION['id'];
$conversationId = $_POST['conversationId'];
$sellerId = $_POST['sellerId'];
$sql = "INSERT INTO tbl_messages SET message=?, user_id=?, date=now(), conversation_id=?";
$statement = $conn->prepare($sql);
$statement->execute([
    $message,
    $userId,
    $conversationId
]);


$sql = "SELECT u.*, m.* FROM tbl_messages m 
JOIN tbl_users u on u.id=m.user_id
WHERE conversation_id=? ORDER BY m.date ASC";
$statement2 = $conn->prepare($sql);
$statement2->execute([$conversationId]);

$messageDetails = "";
if($statement2->rowCount()) {

    while($row2 = $statement2->fetch()) { 
        $backgroundClass = 'seller-message'; 
        if($row2['user_id'] == $_SESSION['id']) {
            $backgroundClass='my-message';
        }
        $messageDetails .= "<div class='message_details__items'>
            <p class='$backgroundClass'>".$row2['message']."</p>
        </div>";
    }
    
}

// echo json_encode([
//     "messageDetails" => $messageDetails
// ]);

echo $messageDetails;