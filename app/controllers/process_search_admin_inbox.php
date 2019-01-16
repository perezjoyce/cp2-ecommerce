<?php
require_once '../../config.php';
$adminId = $_SESSION['id'];
$searchkey = $_POST['searchkey'];

    $sql = ' SELECT u.id AS "user_id", u.first_name, u.last_name, u.username, u.last_login, u.profile_pic, u.isSeller, u.status, s.name, s.logo
            FROM tbl_users u 
            JOIN tbl_stores s 
            ON s.user_id=u.id 
            WHERE u.first_name 
            LIKE ? 
            OR u.last_name 
            LIKE ? 
            OR u.username 
            LIKE ? 
            OR s.name 
            LIKE ? 
            AND `user_id` != ?';
    $statement = $conn->prepare($sql);
    $statement->execute(["%$searchkey%", "%$searchkey%", "%$searchkey%", "%$searchkey%", $adminId]);

    $count = $statement->rowCount();
	
    if($count) {
        while($row = $statement->fetch()) {
            $clientId = $row['user_id'];
            $firstName = $row['first_name'];
            $lastName = $row['last_name'];
            $username = $row['username'];
            $isSeller = $row['isSeller'];
            $storeName = $row['name'];
            $status = $row['status'];
            $lastLogin = $row['last_login'];

            $profile_pic = $row['profile_pic'];
                if($profile_pic == "") {
                    $profile_pic = DEFAULT_PROFILE; 

                } else {
                    $profile_pic = BASE_URL ."/". $profile_pic . "_80x80.jpg";
                } 
?>

                                    <tr class='d-flex flex-column'>
                                        <td> 
                                            <a data-userid='<?= $clientId ?>' class='selected_user'>
                                                <div class='d-flex flex-row align-items-center' style='justify-content:flex-start;'>
                                                    <?php 
                                                        $logo = getStoreLogo($conn,$clientId);
                                                        $logo = BASE_URL ."/". $logo . "_80x80.jpg";
                                                        if($isSeller == 'no') { 
                                                    ?>
                                                        <div class='flex pr-2'>
                                                            <img src='<?= $profile_pic ?>' style='width:50px;height:50px;' class='rounded-circle'>
                                                        </div>   
                                                    <?php } else { ?>
                                                        <div class='flex d-flex align-items-end pr-2'>
                                                            <img src='<?= $logo ?>' style='width:50px;height:50px;' class='rounded-circle'>
                                                            <img src='<?= $profile_pic ?>' style='width:25px;height:25px;margin-left:-20px;' class='rounded-circle'>
                                                        </div>   
                                                    <?php } ?>
                                                   
                                                    <div class='flex-fill vanish-sm'>
                                                        <div class='d-flex flex-column'>
                                                        
                                                            <div class='text-secondary font-weight-bold'>
                                                                <?php 

                                                                    if($isSeller == 'no') {
                                                                        if($firstName && $lastName){
                                                                            $firstName = ucwords(strtolower($firstName));
                                                                            $lastName = ucwords(strtolower($lastName));

                                                                            echo $firstName . " " . $lastName;

                                                                        } else {
                                                                            $username = ucwords(strtolower($username));
                                                                            echo $username;
                                                                        }
                                                                    } else {
                                                                        echo $storeName;
                                                                        // $logo = getStoreLogo ($conn,$userId);
                                                                        // $logo = BASE_URL ."/". $profile_pic . "_80x80.jpg";
                                                                    }
                                                                ?>
                                                            </div>
                                                            <div class='text-gray'>
                                                                <small><?= $lastLogin ?> </small>
                                                            </div>
                                                            

                                                        </div>
                                                    </div>
                                                </div>
                                            </a> 
                                        </td>
                                    </tr>

            
<?php } 
    echo "success";
    } else { 
        echo "<script>
            $('#admin_contacts_container').html('<tr><td><small>Sorry. There is no client with this name in your inbox.</small></td></tr>');
            setTimeout(function(){window.location.reload()}, 2000);
            </script>"; 
    }   
?>   



