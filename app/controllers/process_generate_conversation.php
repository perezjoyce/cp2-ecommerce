<?php
require_once '../../config.php';

$userId = $_SESSION['id'];
$sellerId = $_GET['sellerId'];

// check if there is already a previous conversation with this seller
    $sql = "SELECT * FROM tbl_conversations 
        WHERE `from`=:userId AND `to`=:sellerId"; // named parameters
    $statement = $conn->prepare($sql);
    $statement->execute([
        'userId' => $userId,
        'sellerId' => $sellerId
    ]);

    // Create a new Conversation entry if no conversation yet
    if(!$statement->rowCount()) {
        $sql = "INSERT INTO tbl_conversations SET `from`=:userId, `to`=:sellerId, date=now()";
        $stmt = $conn->prepare($sql);
        $stmt->execute([
            'userId' => $userId,
            'sellerId' => $sellerId
        ]);

        // Fetch again because we already inserted a new conversation
        $sql = "SELECT * FROM tbl_conversations 
            WHERE `from`=:userId AND `to`=:sellerId"; // named parameters
        $statement = $conn->prepare($sql);
        $statement->execute([
            'userId' => $userId,
            'sellerId' => $sellerId
        ]);
    }

    // fetch the conversation
    if($statement->rowCount()) {
        $row = $statement->fetch();
        $conversationId = $row['id'];

        $sellerQuery = "SELECT * FROM tbl_stores WHERE user_id=?";
        $sellerStatement = $conn->prepare($sellerQuery);
        $sellerStatement->execute([$sellerId]);

        $sellerRow = $sellerStatement->fetch();

        // fetch the last message
        $sql = "SELECT u.*, m.* FROM tbl_messages m 
            JOIN tbl_users u on u.id=m.user_id        
            WHERE conversation_id=? ORDER BY m.date"; // DESC
        $statement2 = $conn->prepare($sql);
        $statement2->execute([$conversationId]);

        $lastMessengerName = $sellerRow['name'];
        $lastMessage = "";
        $messageDetails = "";
        $logo = productprofile($conn,$sellerRow['logo']);
        $logo = BASE_URL . "/".$logo .".jpg";

        $messageItemSelected = "<div class='message_items__message'>
                    <img src='".$logo."' height='60' width='60' class='circle'>
                    <div class='m_partial_container'>
                        <em>".$lastMessengerName."</em>
                        <div class='message_partial'>".substr($lastMessage, 0, 20)."...</div>
                    </div>
                </div>";

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
            
        } else {
            $messageItemSelected = "<div class='message_items__message'>
                <img src='".$sellerRow['logo']."' height='60' width='60' class='circle'>
                <div class='m_partial_container'>
                    <em>".$lastMessengerName."</em>
                    <div class='message_partial'></div>
                </div>
            </div>";

            $messageDetails .= "<div class='message_details__items'>
                    <p>This will be the start of your conversation with seller. Please indicate the product that you will inquire of.</p>                    
                </div>";
        }  

    }

    echo json_encode([
        "messageItemSelected" => $messageItemSelected,
        "messageDetails" => $messageDetails,
        "conversationId" => $conversationId,
        "otherMessageItems" => []
    ]);