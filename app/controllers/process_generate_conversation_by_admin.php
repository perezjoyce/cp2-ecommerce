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

        if(!$isSeller) {
            $logo = $clientrow['profile_pic'];
            $logo = BASE_URL . "/".$logo .".jpg";
            $name = $clientrow['name'];
        } else {
            $sellerQuery = "SELECT * FROM tbl_stores WHERE user_id=?";
            $sellerStatement = $conn->prepare($sellerQuery);
            $sellerStatement->execute([$sellerId]);
            $sellerRow = $sellerStatement->fetch();
            $name = $sellerRow['name'];
            $logo =  $sellerRow['logo'];
            $logo = BASE_URL . "/".$logo .".jpg";
        }

     
        // fetch the last message
        $sql5 = "SELECT u.*, m.* FROM tbl_messages m 
            JOIN tbl_users u on u.id=m.user_id        
            WHERE conversation_id=? ORDER BY m.date"; // DESC
        $statement5 = $conn->prepare($sql5);
        $statement5->execute([$conversationId]);

        $lastMessengerName = $name;
        $lastMessage = "";
        $messageDetails = "";

        $messageItemSelected = "<div class='message_items__message'>
                    <img src='".$logo."' height='60' width='60' class='circle'>
                    <div class='m_partial_container'>
                        <div class='font-weight-bold pl-1'>".$lastMessengerName."</div>
                        <div class='message_partial pl-1'><small>Please identify the product you are inquiring of.</small></div>
                    </div>
                </div>";

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
            $messageItemSelected = "<div class='message_items__message'>
                <img src='".$logo."' height='60' width='60' class='circle'>
                <div class='m_partial_container'>
                    <div class='font-weight-bold'>".$lastMessengerName."</div>
                    <div class='message_partial'></div>
                </div>
            </div>";

            $messageDetails .= "<div class='message_details__items'>
                    <p>This will be the start of your conversation with ".$name.".</p>                    
                </div>";
        }  

    }

    echo json_encode([
        "messageItemSelected" => $messageItemSelected,
        "messageDetails" => $messageDetails,
        "conversationId" => $conversationId
    ]);