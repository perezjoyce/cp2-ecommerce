<?php require_once "../../config.php";?>
<?php require_once "../controllers/connect.php";?>
<?php require_once "../controllers/functions.php";?>

<?php 
    // if(!isset($_SESSION['id'])) {
    //     // ob_clean();
    //     // header("location: index.php?msg=NotLoggedIn"); // doesn't work because header already exists
    //     // ECHO THIS TO REDIRECT YOU TO HEADER
    //     echo "<script>window.location.href='".BASE_URL."/app/views/'</script>";
    // }

    $id = $_GET['id'];
    if(empty($id)) {
        header("location: index.php");
    } else {

        // get the store information

        $storeInfo = $storeId = getStore ($conn,$id);
        $id = $_SESSION['id'];
        $currentUser = getUser($conn, $id);
        $isSeller = $currentUser['isSeller'] == "yes" ? 1 : 0;    
    }  

    if ($isSeller && $currentUser['id'] == $storeInfo['user_id']) {
        require_once "../partials/store_header.php";
    } else {
        require_once "../partials/header.php";
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
                                        <span class="input-group-text border-right-0 border-left-0 border-top-0 px-0" id="store_page_search_button" style='background:white;'>
                                            <i class="fas fa-search" style='background:white;'></i>
                                        </span>
                                    </div>
                                    <input type="text" class="form-control border-right-0 border-left-0 border-top-0" id="store_page_search" placeholder="Search for client names here..." style='font-size:14px;'>
                                </div>
                            
                            </div>
                            
                            <div class="row mx-0">
                                <table class="table table-hover borderless" id='sender_container'>
                                                
                                            
                        
                                    <?php 
                                        // CHECK IF THERE IS AN EXISTING CONVERSATIONS INITIATED BY THE BUYER
                                        $sql = "SELECT * FROM tbl_conversations 
                                        WHERE `to` = ? "; // named parameters
                                        $statement = $conn->prepare($sql);
                                        $statement->execute([$storeInfo['user_id']]);
                                        if($statement->rowCount()) {
                                            while($row = $statement->fetch()){
                                            $conversationId = $row['id'];  
                                            $clientId = $row['from'];

                                    ?>
                                    

                                    
                                    <tr>
                                        
                                        
                                        <!-- IMAGE, NAME AND VARIATION -->
                                        <td> 
                                            <a data-sellerid='<?= $sellerId ?>' data-conversationid='<?=$conversationId?>' class='selected_conversation'>
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
                                                                        echo getUsername ($conn,$userId);
                                                                    }
                                                                ?>
                                                            </div>
                                                            <div class='text-gray'>
                                                                <?
                                                                    $sql = "SELECT last_login FROM tbl_users WHERE id = ?";
                                                                    $statement = $conn->prepare($sql);
                                                                    $statement->execute([$clientId]);	
                                                                    $row = $statement->fetch();
                                                                    $lastLogin = $row['last_login'];
                                                                    $datetime1 = new DateTime($lastLogin);
                                                                    $datetime2 = new DateTime();
                                                                    $interval = $datetime1->diff($datetime2);
                                                                    $ago = "";

                                                                    
                                                                    if($interval->format('%w') != 0) {
                                                                        $ago = $interval->format('Active %w weeks ago');
                                                                    } else {
                                                                        if($interval->format('%d') != 0) {
                                                                            $ago = $interval->format('Active %d days ago ');
                                                                        } else {
                                                                            if($interval->format('%h') != 0) {
                                                                                $ago = $interval->format('Active %h hrs ago');
                                                                            } elseif($interval->format('%i') != 0) {
                                                                                $ago = $interval->format('Active %i minutes ago');
                                                                            } else {
                                                                                $ago = "
                                                                                <small>
                                                                                    <i class='fas fa-circle text-success'>&nbsp;</i>
                                                                                </small>
                                                                                Active Now
                                                                                ";
                                                                            }
                                                                        }
                                                                        
                                                                    }

                                                                    echo $ago;
                                                                ?>
                                                                
                                                            </div>
                                                            

                                                        </div>
                                                    </div>
                                                </div>
                                            </a> 
                                        </td>

                    
                                    </tr>
            
                                    <?php } } ?>
                                            


                                </table>
                            </div>
                        </div>

                        <!-- MESSAGE AREA -->
                        <div class="col px-0">
                            <div class="d-flex flex-column">
                                <div style='background:white;height:411px;overflow-y:auto;'>

                                </div>
                                <div>
                                    <form>
                                        <textarea class="form-control border-0" 
                                            id="profile_message_input" 
                                            data-sellerid='<?= $sellerId ?>' 
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

    <!-- IF USER IS LOGGED IN AND USER IS NOT THE SELLER -->
    <?php if(isset($_SESSION['id']) && !$isSeller){ include '../partials/message_box.php'; } ?>

<?php require_once "../partials/footer.php";?>
<?php require_once "../partials/modal_container.php";?>
<?php require_once "../partials/modal_container_big.php"; ?>

  