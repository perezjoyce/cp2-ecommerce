<?php
require_once '../../config.php';

$userId = $_SESSION['id'];
$searchkey = $_GET['storeName'];

    $sql = " SELECT * FROM tbl_stores WHERE `name` LIKE ? ";
    $statement = $conn->prepare($sql);
    $statement->execute(["%$searchkey%"]);

    $count = $statement->rowCount();
	
    if($count) {
        while($row = $statement->fetch()) {
            $sellerId = $row['user_id'];
            $storeName = $row['name'];
            $storeLogo = $row['logo'];

            $sql2 = "SELECT * FROM tbl_conversations 
            WHERE `from` = ? AND `to` = ? "; // named parameters
            $statement2 = $conn->prepare($sql2);
            $statement2->execute([$userId, $sellerId]);
            if($statement2->rowCount()) {
                while($row2 = $statement2->fetch()){
                    $conversationId = $row2['id'];  
                    
                    echo 
                    "<tr class='mx-0 px-0'>

                        <td class='mx-0 px-0'> 
                            <a data-sellerid='$sellerId' data-conversationid='$conversationId' class='selected_conversation'>
                                <div class='d-flex flex-row align-items-center' style='justify-content:flex-start;'>
                                    <div class='flex'>
                                        <img src='$storeLogo' style='width:40px;height:40px;' class='circle'>
                                    </div>   
                                    <div class='flex-fill vanish-sm'>
                                        <div class='d-flex flex-column'>
                                        
                                                
                                            <small class='text-secondary'>$storeName</small>
                                            
                                        </div>
                                    </div>
                                </div>
                            </a> 
                        </td>
                    </tr>";

                }
            }
        }
    }else {
        echo "fail";
    }
            



    




