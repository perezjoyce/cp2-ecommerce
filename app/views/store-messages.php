<?php require_once "../../config.php";?>
<?php 
    $id = $_GET['id'];
    if(empty($id)){ 
        header("location: index.php");
    } else {

        $storeInfo = $storeId = getStore ($conn,$id);
        $id = $_SESSION['id'];
        $currentUser = getUser($conn, $id);
        $isSeller = $currentUser['isSeller'] == "yes" ? 1 : 0;   
        

        $userIsStoreOwner = false;
        //IF USER IS NOT STORE OWNER, REDIRECT TO ORIGIN
        if($id === $storeInfo['user_id']) {
            $userIsStoreOwner = true;
        } else {
            echo '<script>history.go(-1);</script>';
        }
    }  

    
    $storeId = $storeInfo['id'];
    $storeName = $storeInfo['name'];
    $storeLogo = $storeInfo['logo'];
    $storeDescription = $storeInfo['description'];
    $storeAddress = $storeInfo['store_address'];
    $storeHours = $storeInfo['hours'];
    $storeFollowers = countFollowers ($conn, $storeId);
    $storeRating = getAverageStoreRating ($conn, $storeId);
    $storeMembershipDate = getMembershipDate($conn, $storeId);
    $storeShippingFee = displayStoreShippingFee($conn,$storeId);
    $storeFreeShippingMinimum = displayStoreFreeShipping($conn,$storeId);
    $fname = getFirstName ($conn,$id);
    $lname = getLastName ($conn,$id);
    
?>
<!-- require_once BASE_DIR . "/app/partials/store_header.php"; -->
<?php require_once "../partials/store_header.php";?>

    <!-- PAGE CONTENT -->
    <br>
    <div class="container p-0 my-lg-5 mt-md-5">


        <div class="row mx-0">

          
            <!-- MAIN BAR-->
            <div class="col">
                
                <!-- SEARCH BAR -->
                <div class='container p-5 rounded' style='background:white;'>
                    
                    <div class="row mx-0">
                        <!-- CONTACTS AREA -->
                        <div class="col-4" style='background:white;height:460px;overflow-y:auto;'>

                            <div class="row mx-0">
                        
                                <div class="input-group input-group-lg">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text border-right-0 border-left-0 border-top-0 px-0" style='background:white;'>
                                            <i class="fas fa-search" style='background:white;'></i>
                                        </span>
                                    </div>
                                    <input type="text" class="form-control border-right-0 border-left-0 border-top-0" id="search_client_name" placeholder="Search for client names here..." style='font-size:14px;'>
                                </div>
                            </div>
                            
                            <div class="row mx-0">
                                <table class="table table-hover borderless" id='sender_container'>
                                                
                                            
                                <tr>
                                    <?php 
                                        // CHECK IF THERE IS AN EXISTING CONVERSATIONS INITIATED BY THE BUYER
                                        $sql = "SELECT * FROM tbl_conversations WHERE `to` = ? "; 
                                        $statement = $conn->prepare($sql);
                                        $statement->execute([$storeInfo['user_id']]);

                                        if($statement->rowCount()) {
                                            while($row = $statement->fetch()){
                                            $conversationId = $row['id'];  
                                            $clientId = $row['from'];

                                            $sql2 = "SELECT * FROM tbl_messages WHERE user_id = ? AND conversation_id =? GROUP BY conversation_id"; 
                                            $statement2 = $conn->prepare($sql2);
                                            $statement2->execute([$clientId,$conversationId]);
                                            $count2 = $statement2->rowCount();

                                            if($count2){
                                                                    
                                                while($row2 = $statement2->fetch()){ 
                                                    $message = $row2['message'];
                                                    $date = $row2['date'];

                                    ?>


                                        <!-- IMAGE, NAME AND VARIATION -->
                                        <td> 
                                            <a data-sellerid='<?= $clientId ?>' data-conversationid='<?=$conversationId?>' class='selected_conversation'>
                                                <div class='d-flex flex-row align-items-center' style='justify-content:flex-start;'>
                                                    <div class='flex pr-2'>
                                                        <img src='<?= BASE_URL ."/" .getProfilePic ($conn,$clientId).".jpg" ?>' style='width:50px;height:50px;' class='circle'>
                                                    </div>   
                                                    <div class='flex-fill vanish-sm vanish-md'>
                                                        <div class='d-flex flex-column'>
                                                        
                                                            <div class='text-secondary font-weight-bold'>
                                                                <?php 
                                                                    if(getFirstName($conn, $clientId) && getLastName($conn, $clientId)){
                                                                        $firstName = getFirstName ($conn,$clientId);
                                                                        $firstName = ucwords(strtolower($firstName));
                                                                        $lastName = getLastName($conn,$clientId);
                                                                        $lastName = ucwords(strtolower($lastName));

                                                                        echo $firstName . " " . $lastName;

                                                                    } else {
                                                                        $username = getUsername ($conn,$userId);
                                                                        $username = ucwords(strtolower($username));
                                                                        echo $username;
                                                                    }
                                                                ?>
                                                            </div>
                                                            <div class='text-gray'>
                                                                <?= getLastLogin($conn, $clientId) ?> 
                                                            </div>
                                                            

                                                        </div>
                                                    </div>
                                                </div>
                                            </a> 
                                        </td>

                                    



                                    <?php } ?> 
                                </tr>

                               <?php } else echo ""; } }?>
                                            


                                </table>
                            </div>
                        </div>

                        <!-- MESSAGE AREA -->
                        <div class="col px-0">
                            <div class="d-flex flex-column">
                                <div style='background:white;height:411px;overflow-y:auto;' id='profile_message_container'>
                                    <?php 
                                    if(isset($_SESSION['last_selected_conversation'])){
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
                                            
                                        }

                                        echo $messageDetails;
 
                                    }
                                    
                                    ?>
                                </div>
                                <div>
                                    <form>
                                        <input type="hidden" id='profile_conversation_id'>
                                        <textarea class="form-control border-0" 
                                            id="profile_message_input" 
                                            data-sellerid='<?= $storeInfo['user_id'] ?>' 
                                            style='width:100%;background:#eff0f5;resize:none;' 
                                            rows='2'></textarea>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>


                
            </div>
            <!-- /MAIN BAR -->


        </div>
        <!-- /.ROW -->
    </div>
    <!-- /.CONTAINER -->

    

<?php require_once "../partials/footer.php";?>
<?php require_once "../partials/modal_container.php";?>
<?php require_once "../partials/modal_container_big.php"; ?>

  