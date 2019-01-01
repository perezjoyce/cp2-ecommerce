<?php
session_start(); 
require_once "connect.php";
require_once "functions.php";

$userId = $_SESSION['id'];
$sellerId = $_GET['sellerId'];
$conversationId = $_GET['conversationId'];


$sql = "SELECT u.*, m.* FROM tbl_messages m 
    JOIN tbl_users u on u.id=m.user_id        
    WHERE conversation_id=? ORDER BY m.date"; 
    $statement = $conn->prepare($sql);
    $statement->execute([$conversationId]);       
    $messageDetails = "";

    if($statement->rowCount()) {

        while($row2 = $statement->fetch()) {
            $backgroundClass = 'seller-message'; 
            if($row2['user_id'] == $userId) {
                $backgroundClass='my-message';
            }
            
            $messageDetails .= "<div class='message_details__items'>
                <p class='$backgroundClass'>".$row2['message']."</p>                    
            </div>";
        }
        
    } else {
        
        $messageDetails .= "<div class='message_details__items'>
                <p>This will be the start of your conversation with ".getStoreName ($conn,$sellerId)." </p>                    
            </div>";
    }  

    
    echo json_encode([
        "messageDetails" => $messageDetails,
        "conversationId" => $conversationId
    ]);
    // echo $messageDetails;