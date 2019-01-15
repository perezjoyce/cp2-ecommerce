<?php
require_once '../../config.php';

$adminId = $_SESSION['id'];
$clientId = $_GET['userId'];

// check if there is already a previous conversation
    $sql = "SELECT * FROM tbl_conversations 
        WHERE `from`=? AND `to`=? OR `from`=? AND `to`=?";
    $statement = $conn->prepare($sql);
    $statement->execute([$adminId,$clientId,$clientId,$adminId]);

    // Create a new Conversation entry if no conversation yet
    if(!$statement->rowCount()) {
        $sql2 = "INSERT INTO tbl_conversations SET `from`=?, `to`=?, date=now()";
        $statement2 = $conn->prepare($sql2);
        $statement2->execute([$adminId,$clientId]);

        // Fetch again because we already inserted a new conversation
        $sql3 = "SELECT * FROM tbl_conversations 
            WHERE `from`=? AND `to`=?"; // named parameters
        $statement3 = $conn->prepare($sql3);
        $statement3->execute([$adminId,$clientId]);
    }

    // fetch the conversation
    if($statement->rowCount()) {
        $row = $statement->fetch();
        $conversationId = $row['id'];

        $sql4 = "SELECT * FROM tbl_users WHERE id=?";
        $statement4 = $conn->prepare($sql4);
        $statement4->execute([$clientId]);
        $clientrow = $statement4->fetch();
        $isSeller = $clientrow['isSeller'];

        if($isSeller == 'no'){
            $name = $clientrow['username'];
        } else {
            $sql6 = "SELECT * FROM tbl_stores WHERE user_id=?";
            $statement6 = $conn->prepare($sql6);
            $statement6->execute([$clientId]);
            $sellerrow = $statement6->fetch();
            $name = $sellerrow['name'];
        }

     
        // fetch the last message
        $sql5 = "SELECT u.*, m.* FROM tbl_messages m 
            JOIN tbl_users u on u.id=m.user_id        
            WHERE conversation_id=? ORDER BY m.date"; // DESC
        $statement5 = $conn->prepare($sql5);
        $statement5->execute([$conversationId]);

        $lastMessengerName = $name;
        $messageDetails = "";

       

        if($statement5->rowCount()) {

            while($row5 = $statement5->fetch()) {
                $backgroundClass = 'seller-message'; 
                if($row5['user_id'] == $_SESSION['id']) {
                    $backgroundClass='my-message';
                }
                

                $messageDetails .= "<div class='message_details__items'>
                    <p class='$backgroundClass'>".$row5['message']."</p>                    
                </div>";
            }
            
        } else {
            $messageDetails .= "<div class='message_details__items'>
                    <p>This will be the start of your conversation with ".$lastMessengerName.".</p>                    
                </div>";
        }  

    }

    echo json_encode([
        "messageDetails" => $messageDetails,
        "conversationId" => $conversationId
    ]);