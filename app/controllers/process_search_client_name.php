<?php
require_once "connect.php";
require_once "functions.php";
require_once "../../config.php";

$sellerId = $_SESSION['id'];
$searchkey = $_GET['keypress'];

    $sql = " SELECT * FROM tbl_users WHERE first_name LIKE ? OR last_name LIKE ? OR username LIKE ? ";
    $statement = $conn->prepare($sql);
    $statement->execute(["%$searchkey%", "%$searchkey%", "%$searchkey%"]);

    $count = $statement->rowCount();
	
    if($count) {
        while($row = $statement->fetch()) {
            $clientId = $row['id'];
            $fname = $row['first_name'];
            $lname = $row['last_name'];
            $profilePic = $row['profile_pic'];

            $sql2 = "SELECT * FROM tbl_conversations WHERE `from` = ? AND `to` = ? "; // named parameters
            $statement2 = $conn->prepare($sql2);
            $statement2->execute([$clientId, $sellerId]);
            if($statement2->rowCount()) {
                while($row2 = $statement2->fetch()){
                    $conversationId = $row2['id'];  
                    $profilePic = BASE_URL."/".$profilePic.".jpg";
                    
        echo 
        "
            <tr>
                <td> 
                    <a data-sellerid='$clientId' data-conversationid='$conversationId' class='selected_conversation'>
                        <div class='d-flex flex-row align-items-center' style='justify-content:flex-start;'>
                            <div class='flex pr-2'>
                                <img src='$profilePic' style='width:50px;height:50px;' class='circle'>
                            </div>   
                            <div class='flex-fill vanish-sm vanish-md'>
                                <div class='d-flex flex-column'>
                                
                                    <div class='text-secondary font-weight-bold'>"; 
                                   
                                          if(getFirstName($conn, $clientId) && getLastName($conn, $clientId)){
                                                    $firstName = getFirstName ($conn,$clientId);
                                                    $firstName = ucwords(strtolower($firstName));
                                                    $lastName = getLastName($conn,$clientId);
                                                    $lastName = ucwords(strtolower($lastName));

                                                echo $firstName . " " . $lastName;

                                            } else {
                                                $username = getUsername ($conn,$clientId);
                                                $username = ucwords(strtolower($username));
                                                echo $username;
                                                
                                            }
                                      
                             echo   "</div>
                                    <div class='text-gray'>";
                                        
                            echo getLastLogin($conn, $clientId); 
                                    "</div>
                                    

                                </div>
                            </div>
                        </div>
                    </a> 
                </td>
            <tr>";


                }
            }
        }
    }else {
        echo "fail";
    }
            



    




