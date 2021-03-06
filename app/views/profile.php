<?php require_once "../../config.php";?>
<?php require_once "../partials/header.php";?>

<?php 

    if(!isset($_SESSION['id'])) {
        // ob_clean();
        // header("location: index.php?msg=NotLoggedIn"); // doesn't work because header already exists
        // ECHO THIS TO REDIRECT YOU TO HEADER
        echo "<script>window.location.href='".BASE_URL."/app/views/'</script>";
        exit;
    }

    $id = $_GET['id'];
    if(empty($id)) {
        echo "<script>window.location.href='".BASE_URL."/app/views/'</script>";
    } else {

        $id = $_SESSION['id'];
        $sql = "SELECT * FROM tbl_users WHERE id = ? ";

        $statement = $conn->prepare($sql);
        $statement->execute([$id]);
        $row = $statement->fetch();

        $id = $row['id'];      
        $fname = ucfirst($row['first_name']);   
        $lname = ucfirst($row['last_name']);  
        $username = $row['username'];      
        $email = $row['email'];  
        $profile_pic = $row['profile_pic'];

        if($profile_pic == "") {
            $profile_pic = DEFAULT_PROFILE; 
        } else {
            $profile_pic = BASE_URL ."/". $profile_pic . "_80x80.jpg";
        } 
    }  
       
?>
    <!-- PAGE CONTENT -->
    <br>
    <div class="container p-0 my-lg-5 mt-md-5">


        <div class="row mx-0">

            <!-- MAIN BAR-->
            <div class="col">

                <!-- PROFILE -->
                <div class='container p-5 rounded mb-5' style='background:white;'>
                    <div class="row mb-3">
                        <div class="col-12 d-flex">
                            <div class='flex-fill d-sm-flex d-flex flex-lg-row flex-md-row flex-sm-column'>
                                <div class='flex-fill'>
                                    <div class="d-flex flex-lg-row flex-md-row align-items-center flex-sm-column">
                                        <div class='pr-3'>
                                            <img src='<?= $profile_pic ?>'  class="profile_user_photo rounded-circle">
                                        </div>
                                        <div class="d-flex flex-column text-lg-left text-md-left text-sm-center">
                                            <div class="pt-sm-4">
                                                <?php if($fname && $lname) { ?>
                                                <h3><?= $fname . " " . $lname ?></h3>
                                                <?php } else { ?> 
                                                <h3><?= $username ?></h3>
                                                <?php } ?>
                                            </div>
                                            
                                        </div>
                                    </div>
                                </div>
                                <div class='flex-fill text-lg-right text-sm-center'>
                                    <div class="d-flex flex-column">
                                        <a class='nav-link modal-link px-0' href='#' data-id='<?= $id ?>' data-url='../partials/templates/upload_user_pic_modal.php' role='button'>
                                            <i class="fas fa-camera"></i>
                                            Update Image
                                        </a>
                                        <div class="text-gray">
                                            <small>File size: Max of 1MB</small>
                                        </div>
                                        <div class="text-gray">
                                            <small>File extension: jpg, jpeg, png</small>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

                <!-- SECOND ROW -->
                <div class='container p-0'>
                    <div class="row">

                        
                        <div class="col-lg-6 col-md-12 col-sm-12">
                            <!-- BASIC INFO -->
                            <div class="container p-5 rounded mb-5" style='background:white;'>
                                <div class="row mb-3">
                                    <div class="col">
                                        <div class='d-flex flex-row'>
                                            <div class='flex-fill'>
                                                <h4>Basic Info<span class='vanish-sm'>rmation</span></h4>
                                            </div>
                                            <div class='flex-fill text-right'>
                                                <a class='nav-link modal-link' href='#' data-id='<?= $id ?>' data-url='../partials/templates/edit_user_modal.php' role='button'>
                                                    <i class="far fa-edit"></i>
                                                    Edit
                                                </a>
                                                
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="row border-top">
                                    <!-- LEFT OF MAIN BAR -->
                                    <div class="col">
                                        <div class="container px-0">

                                            <?php if ($fname && $lname) { ?>
                                            <div class="row my-5">
                                                <div class="col-lg-3 col-md-4 col-sm-4">
                                                    Name
                                                </div>
                                                <div class="col">
                                                    <?= $fname . " " . $lname ?>
                                                </div>
                                            </div>  
                                            
                                            <div class="row mb-5">
                                            <?php } else { echo "<div class='row my-5'>"; } ?>
                                                <div class="col-lg-3 col-md-4 col-sm-4">
                                                    Username
                                                </div>
                                                <div class="col">
                                                    <?= $username ?>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-lg-3 col-md-4 col-sm-4">
                                                    Email
                                                </div>
                                                <div class="col-sm-8">
                                                    <?= hide_email($email) ?>
                                                </div>
                                            </div>

                                        </div>

                                    </div>
                                    <!-- /LEFT OF MAIN BAR -->

                                
                                </div>
                                <!-- ================ -->

                            </div>

                             <!-- FOLLOWING -->
                            <?php 
                                $sql = "SELECT * FROM tbl_followers WHERE user_id = ?";
                                $statement = $conn->prepare($sql);
                                $statement->execute([$id]);
                                $count = $statement->rowCount();
                                                                    
                                if($count){
                            ?>
                            <div class='container p-5 rounded mb-5' style='background:white;'>
                                <div class="row mb-3">
                                    <div class="col d-flex flex-column">

                                
                                        <div class='flex-fill'>
                                            <h4>Following</h4>
                                        </div>

                                        <div class='flex-fill d-flex flex-row border-top'>
                                            <?php while($row = $statement->fetch()){ 
                                                $storeId = $row['store_id'];
                                                $storeLogo = getStoreLogoFromId ($conn,$storeId);
                                                $storeLogo = BASE_URL ."/".$storeLogo .".jpg";
                                            ?>
                                            <div class='flex pt-4'>
                                                <a href="store-profile.php?id=<?= $storeId ?>">
                                                    <img src="<?=$storeLogo?>" style="width:40px;height:40px;" class='rounded-circle'>
                                                </a>
                                            </div>
                                            <?php } ?>
                                        </div>

                                            
                                    
                                    </div>
                                </div>

                            </div>
                                <?php } ?>

                             <!-- ORDER HISTORY -->
                             <div class="container p-lg-5 p-md-5 px-sm-2 rounded mb-5 pt-5" style='background:white;height:550px;overflow-y:auto;'>
                                <div class="row mb-3">
                                    <div class="col">
                                        <div class='d-flex flex-row'>
                                            <div class='flex-fill'>
                                                <h4>Order History</h4>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                 <!-- PENDING TRANSACTIONS -->
                                 <?php
                                    $sql = "SELECT o.*, s.name as 'status' FROM tbl_orders o JOIN tbl_status s ON o.status_id=s.id WHERE `user_id` = ? AND status_id = 1 ORDER BY o.purchase_date DESC";
                                                $statement = $conn->prepare($sql);
                                                $statement->execute([$id]);
                                                $count = $statement->rowCount();
                            
                                            if($count) {
                                ?>
                                <div class="row border-top">
                                    <div class="col px-2">
                                        <div class="container px-0 table-responsive-sm">
                                            <table class="table table-hover borderless mt-4 text-center">
                                                <tr>
                                                    <h5 class='text-gray pl-3 mt-5'>Pending Orders</h5>
                                                    
                                                </tr>
                                                
                                                <tr>
                                                    <thead class='text-secondary bg-gray'>
                                                        <th>Date</th>
                                                        <th>Transaction Code</th>
                                                        <!-- <th>Status</th> -->
                                                        <th>View</th>
                                                    </thead>
                                                </tr>                               
                        
                                                <?php 
                                                        while($row = $statement->fetch()){ 
                                                        $transactionId = $row['id'];
                                                        $transactionCode = $row['transaction_code'];
                                                        $purchaseDate = $row['purchase_date'];
                                                        // $status = $row['status'];
                                                        $orderHistoryCartSession = $row['cart_session'];
                                            
                                                ?>
                                                
                                                <tr>

                                                    <div>

                                                        <!-- PURCHASE DATE -->
                                                        <td>
                                                            <a data-url="../partials/templates/view_order_summary_modal.php" data-id='<?=$orderHistoryCartSession?>' class='border-0 btn_view_order_history' style='cursor:pointer;size:15px;'>
                                                                <div class='py-3 text-secondary'><?=date("M d, Y", strtotime($purchaseDate))?></div>
                                                            </a>
                                                        </td>
                                                        
                                                        
                                                        <!-- IMAGE, NAME AND VARIATION -->
                                                        <td class='mx-0'> 
                                                            <a data-url="../partials/templates/view_order_summary_modal.php" data-id='<?=$orderHistoryCartSession?>' class='border-0 btn_view_order_history' style='cursor:pointer;size:15px;'>
                                                                <div class='py-3'><?=$transactionCode ?></div>
                                                            </a>
                                                        </td>

                                                        <!-- STATUS-->
                                                        <!-- <td class='mx-0'> 
                                                            <div class='py-3 text-gray'><?= ucfirst($status) ?></div>
                                                        </td> -->

                                                        <!-- VIEW -->
                                                        <td>
                                                            <a data-url="../partials/templates/view_order_summary_modal.php" data-id='<?=$orderHistoryCartSession?>' class='border-0 btn_view_order_history' style='cursor:pointer;size:15px;'>
                                                                <i class="far fa-file-pdf text-gray py-3"></i>
                                                            </a>
                                                        </td>
                                                        
                                                    </div>
                                                    
                                                </tr>
                                                <?php } ?>
                    
                                            </table>

                                            
                                        
                                        </div>
                                    </div>
                                </div>
                                <?php } ?>

                                <!-- CONFIRMED TRANSACTIONS -->
                                <?php
                                    $sql = "SELECT o.*, s.name as 'status' FROM tbl_orders o JOIN tbl_status s ON o.status_id=s.id WHERE `user_id` = ? AND status_id = 2 ORDER BY o.purchase_date DESC";
                                                $statement = $conn->prepare($sql);
                                                $statement->execute([$id]);
                                                $count = $statement->rowCount();
                            
                                            if($count) {
                                ?>
                                <div class="row border-top">
                                    <div class="col px-2">
                                        <div class="container px-0 table-responsive-sm">
                                            <table class="table table-hover borderless mt-4 text-center">
                                                <tr>
                                                    <h5 class='text-gray pl-3 mt-5'>Confirmed Orders</h5>
                                                    
                                                </tr>
                                                
                                                <tr>
                                                    <thead class='text-secondary bg-gray'>
                                                        <th>Date</th>
                                                        <th>Transaction Code</th>
                                                        <!-- <th>Status</th> -->
                                                        <th>View</th>
                                                    </thead>
                                                </tr>                               
                        
                                                <?php 
                                                        while($row = $statement->fetch()){ 
                                                        $transactionId = $row['id'];
                                                        $transactionCode = $row['transaction_code'];
                                                        $purchaseDate = $row['purchase_date'];
                                                        // $status = $row['status'];
                                                        $orderHistoryCartSession = $row['cart_session'];
                                            
                                                ?>
                                                
                                                <tr>

                                                    <div>

                                                        <!-- PURCHASE DATE -->
                                                        <td>
                                                            <a data-url="../partials/templates/view_order_summary_modal.php" data-id='<?=$orderHistoryCartSession?>' class='border-0 btn_view_order_history' style='cursor:pointer;size:15px;'>
                                                                <div class='py-3 text-secondary'><?=date("M d, Y", strtotime($purchaseDate))?></div>
                                                            </a>
                                                        </td>
                                                        
                                                        
                                                        <!-- IMAGE, NAME AND VARIATION -->
                                                        <td class='mx-0'> 
                                                            <a data-url="../partials/templates/view_order_summary_modal.php" data-id='<?=$orderHistoryCartSession?>' class='border-0 btn_view_order_history' style='cursor:pointer;size:15px;'>
                                                                <div class='py-3'><?=$transactionCode ?></div>
                                                            </a>
                                                        </td>

                                                        <!-- STATUS-->
                                                        <!-- <td class='mx-0'> 
                                                            <div class='py-3 text-gray'><?= ucfirst($status) ?></div>
                                                        </td> -->

                                                        <!-- VIEW -->
                                                        <td>
                                                            <a data-url="../partials/templates/view_order_summary_modal.php" data-id='<?=$orderHistoryCartSession?>' class='border-0 btn_view_order_history' style='cursor:pointer;size:15px;'>
                                                                <i class="far fa-file-pdf text-gray py-3"></i>
                                                            </a>
                                                        </td>
                                                        
                                                    </div>
                                                    
                                                </tr>
                                                <?php } ?>
                    
                                            </table>

                                            
                                        
                                        </div>
                                    </div>
                                </div>
                                <?php } ?>

                                <!-- COMPLETED TRANSACTIONS -->
                                <?php
                                    $sql = "SELECT o.*, s.name as 'status' FROM tbl_orders o JOIN tbl_status s ON o.status_id=s.id WHERE `user_id` = ? AND status_id = 3 ORDER BY o.purchase_date DESC";
                                            $statement = $conn->prepare($sql);
                                            $statement->execute([$id]);
                                            $count = $statement->rowCount();
                            
                                        if($count) {
                                ?>
                                <div class="row border-top">
                                    <div class="col px-2">
                                        <div class="container px-0 table-responsive-sm">
                                            
                                            <table class="table table-hover borderless mt-4 text-center">
                                                <tr>
                                                    <h5 class='text-gray pl-3 mt-5'>Completed Orders</h5>
                                                    
                                                </tr>
                                                
                                                <tr>
                                                    <thead class='text-secondary bg-gray'>
                                                        <th>Date</th>
                                                        <th>Transaction Code</th>
                                                        <!-- <th>Status</th> -->
                                                        <th>View</th>
                                                    </thead>
                                                </tr>
                                            
                        
                                                <?php 
                                                    while($row = $statement->fetch()){ 
                                                        $transactionId = $row['id'];
                                                        $transactionCode = $row['transaction_code'];
                                                        $purchaseDate = $row['purchase_date'];
                                                        // $status = $row['status'];
                                                        $orderHistoryCartSession = $row['cart_session'];
                                            
                                                ?>

                                                
                                                <tr>

                                                    <!-- PURCHASE DATE -->
                                                    <td>
                                                        <a data-url="../partials/templates/view_order_summary_modal.php" data-id='<?=$orderHistoryCartSession?>' class='border-0 btn_view_order_history' style='cursor:pointer;size:15px;'>
                                                            <div class='py-3 text-secondary'><?=date("M d, Y", strtotime($purchaseDate))?></div>
                                                        </a>
                                                    </td>
                                                    
                                                    
                                                    <!-- IMAGE, NAME AND VARIATION -->
                                                    <td class='mx-0'> 
                                                        <a data-url="../partials/templates/view_order_summary_modal.php" data-id='<?=$orderHistoryCartSession?>' class='border-0 btn_view_order_history' style='cursor:pointer;size:15px;'>
                                                            <div class='py-3'><?=$transactionCode ?></div>
                                                        </a>
                                                    </td>

                                                    <!-- STATUS-->
                                                    <!-- <td class='mx-0'> 
                                                        <div class='py-3 text-gray'><?= ucfirst($status) ?></div>
                                                    </td> -->

                                                    <!-- VIEW -->
                                                    <td>
                                                        <a data-url="../partials/templates/view_order_summary_modal.php" data-id='<?=$orderHistoryCartSession?>' class='border-0 btn_view_order_history' style='cursor:pointer;size:15px;'>
                                                            <i class="fas fa-file-download text-gray py-3"></i>
                                                        </a>
                                                    </td>
                                                    
                                                </tr>
                                                <?php } ?>
                    
                                            </table>

                                            

                                        </div>

                                    </div>
                                </div>
                                <?php } ?>


                                <!-- CANCELLED TRANSACTIONS -->
                                <?php
                                    $sql = "SELECT o.*, s.name as 'status' FROM tbl_orders o JOIN tbl_status s ON o.status_id=s.id WHERE `user_id` = ? AND status_id = 4 ORDER BY o.purchase_date DESC";
                                            $statement = $conn->prepare($sql);
                                            $statement->execute([$id]);
                                            $count = $statement->rowCount();
                            
                                        if($count) {
                                ?>
                                <div class="row border-top">
                                    <div class="col px-2">
                                        <div class="container px-0 table-responsive-sm">
                                            
                                            <table class="table table-hover borderless mt-4 text-center">
                                                <tr>
                                                    <h5 class='text-gray pl-3 mt-5'>Cancelled Orders</h5>
                                                    
                                                </tr>
                                                
                                                <tr>
                                                    <thead class='text-secondary bg-gray'>
                                                        <th>Date</th>
                                                        <th>Transaction Code</th>
                                                        <!-- <th>Status</th> -->
                                                        <th>View</th>
                                                    </thead>
                                                </tr>
                                            
                        
                                                <?php 
                                                    while($row = $statement->fetch()){ 
                                                        $transactionId = $row['id'];
                                                        $transactionCode = $row['transaction_code'];
                                                        $purchaseDate = $row['purchase_date'];
                                                        // $status = $row['status'];
                                                        $orderHistoryCartSession = $row['cart_session'];
                                            
                                                ?>

                                                
                                                <tr>

                                                    <!-- PURCHASE DATE -->
                                                    <td>
                                                        <a data-url="../partials/templates/view_order_summary_modal.php" data-id='<?=$orderHistoryCartSession?>' class='border-0 btn_view_order_history' style='cursor:pointer;size:15px;'>
                                                            <div class='py-3 text-secondary'><?=date("M d, Y", strtotime($purchaseDate))?></div>
                                                        </a>
                                                    </td>
                                                    
                                                    
                                                    <!-- IMAGE, NAME AND VARIATION -->
                                                    <td class='mx-0'> 
                                                        <a data-url="../partials/templates/view_order_summary_modal.php" data-id='<?=$orderHistoryCartSession?>' class='border-0 btn_view_order_history' style='cursor:pointer;size:15px;'>
                                                            <div class='py-3'><?=$transactionCode ?></div>
                                                        </a>
                                                    </td>

                                                    <!-- STATUS-->
                                                    <!-- <td class='mx-0'> 
                                                        <div class='py-3 text-gray'><?= ucfirst($status) ?></div>
                                                    </td> -->

                                                    <!-- VIEW -->
                                                    <td>
                                                        <a data-url="../partials/templates/view_order_summary_modal.php" data-id='<?=$orderHistoryCartSession?>' class='border-0 btn_view_order_history' style='cursor:pointer;size:15px;'>
                                                            <i class="fas fa-file-download text-gray py-3"></i>
                                                        </a>
                                                    </td>
                                                    
                                                </tr>
                                                <?php } ?>
                    
                                            </table>

                                            

                                        </div>

                                    </div>
                                </div>
                                <?php } ?>

                            </div>
                            


                             <!-- PRODUCTS TO REVIEW -->
                             <div class="container p-lg-5 p-md-5 px-sm-2 rounded mb-5 pt-5" style='background:white;height:500px;overflow-y:auto;'>
                                <div class="row mb-3">
                                    <div class="col">
                                        <div class='d-flex flex-row'>
                                            <div class='flex-fill'>
                                                <h4>Products To Review</h4>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="row border-top">
                                        <?php 
                                            $sql = "SELECT c.*, v.variation_name, i.name 
                                                    AS 'productName',i.price, i.id 
                                                    AS 'productId', i.store_id 
                                                    FROM tbl_carts c 
                                                    JOIN tbl_variations v 
                                                    JOIN tbl_items i 
                                                    ON c.variation_id=v.id 
                                                    AND v.product_id=i.id 
                                                    WHERE `user_id` = ? 
                                                    AND status_id = 3 
                                                    GROUP BY productId 
                                                    ORDER BY date_created DESC";
                                                $statement = $conn->prepare($sql);
                                                $statement->execute([$id]);
                                                $count = $statement->rowCount();

                                                if($count) {
                                        ?>
                                    <div class="col px-0">
                                        <div class="container px-2 table-responsive-sm">
                                            <table class="table table-hover borderless mt-4">

 
                                                <?php
                                                    while($row = $statement->fetch()){ 
                                                        $completeOrderSession = $row['cart_session'];
                                                        $completeOrderVariationId = $row['variation_id'];
                                                        $completeOrderVariationName = $row['variation_name'];
                                                        $completeOrderProductId = $row['productId'];
                                                        $completeOrderProductName = $row['productName'];
                                                        $completeOrderPrice = $row['price'];
                                                        $completeOrderLogo = productprofile($conn,$completeOrderProductId);
                                                        $completeOrderLogo = BASE_URL. "/".$completeOrderLogo.".jpg";
                                                        $completeOrderStoreId = $row['store_id'];
                                                ?>
                                        
                                                
                                                <tr id='wish-row<?=$completeOrderProductId?>' class='d-flex align-items-center'>
                                                    
                                                    
                                                    <!-- IMAGE, NAME AND VARIATION -->
                                                    <td class='mx-0 flex-fill'> 
                                                        <!-- <input type="text" value='<?=$completeOrderSession?>'> -->
                                                        <a data-url="../partials/templates/review_product_modal.php" 
                                                                data-productid='<?=$completeOrderProductId?>' 
                                                                class='btn btn_review_product btn_products_to_review<?=$completeOrderProductId?>' 
                                                                style='cursor:pointer;size:15px;'>
                                                            
                                                            <div class='d-flex flex-row align-items-center' style='justify-content:flex-start;'>
                                                                <div class='flex pr-2'>
                                                                    <img src='<?=$completeOrderLogo?>' style='width:80px;height:80px;'>
                                                                </div>   
                                                                <div class='flex-fill'>
                                                                    <div class='d-flex flex-column text-left'>
                                                                    
                                                                            <div>
                                                                                <div class='text-secondary'><?= $completeOrderProductName ?></div>
                                                                            </div>
                                                                            <div>
                                                                                <div class='text-gray'><?= $completeOrderVariationName ?></div>
                                                                            </div>
                                                                            <div>
                                                                                <span class='text-gray'>&#36;&nbsp;</span>
                                                                                <span class='text-gray'><?= $completeOrderPrice ?></span>
                                                                            </div>
                                                                    
                                                                        
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </a> 
                                                    </td>

                                                    <!-- RATING MODAL -->
                                                    <td class='flex'>
                                                        <a data-url="../partials/templates/review_product_modal.php" 
                                                                data-productid='<?=$completeOrderProductId?>' 
                                                                class='btn btn_review_product btn_products_to_review<?=$completeOrderProductId?> py-2' 
                                                                style='cursor:pointer;size:15px;'>
                                                           
                                                                <?=changeWordInsideProductRatingButton($conn,$completeOrderProductId)?>
                                                            
                                                        </a>
                                                    </td>
                                                    
                                                </tr>
                        
                                                <?php }?>
                                            


                                            </table>

                                        </div>

                                    </div>
                                    <?php } ?>
                                </div>
                            </div>


                            

                        </div>


                        <div class="col-lg-6 col-md-12 col-sm-12">

                            <!-- ADDRESSES -->
                            <div class="container p-5 mb-5 rounded" style='background:white;'>
                                <div class="row mb-3">
                                    <div class="col">
                                        <div class='d-flex flex-row'>
                                            <div class='flex-fill'>
                                                <h4>Addresses</h4>
                                            </div>
                                            <div class='flex-fill text-right'>
                                                <a class='nav-link modal-link' href='#' data-id='<?= $id ?>' data-url='../partials/templates/edit_address_modal.php' role='button'>
                                                    <i class="far fa-edit"></i>
                                                    Edit
                                                </a>
                                                
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="row border-top">
                                    <div class="col">
                                        <div class="container px-0">

                                            <?php 
                                                $sql = "SELECT * FROM tbl_addresses WHERE `user_id` = ?";
                                                $statement = $conn->prepare($sql);
                                                $statement->execute([$id]);	

                                                $count = $statement->rowCount();

                                                if($count) {
                                                    while($row = $statement->fetch()){
                                                        $addressType = $row['addressType'];
                                                        $addressType = ucwords(strtolower($addressType));
                                                
                                                        $landmark = $row['landmark'];
                                                        $landmark = ucwords(strtolower($landmark));

                                                        $street = $row['street_bldg_unit'];
                                                        $street = ucwords(strtolower($street));

                                                        $regionId = $row['region_id'];
                                                        $provId = $row['province_id'];
                                                        $cityId = $row['city_id'];
                                                        $brgyId = $row['brgy_id'];
                                            ?>

                                             <div class="row mt-5">
                                                <div class="col-3">
                                                    <?=$addressType?>
                                                </div>
                                                <div class="col">
                                                        <?=$street?>,&nbsp;
                                                        <?= getBrgyName($conn, $brgyId) ?>,&nbsp;
                                                        <?= getCityName($conn, $cityId) ?>,&nbsp;
                                                        <?= getProvinceName($conn, $provId) ?>,
                                                        <?= getRegionName($conn, $regionId) ?>&nbsp; -- &nbsp;
                                                        
                                                        <span class='text-gray'>
                                                            <?=$landmark?>
                                                        </span>
                                                    
                                                </div>
                                            </div>  


                                            <?php } } ?>

                                            

                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- WISH LIST -->
                            <div class="container p-5 rounded mb-5" style='background:white;height:550px;overflow-y:auto;'>
                                <div class="row mb-3">
                                    <div class="col">
                                        <div class='d-flex flex-row'>
                                            <div class='flex-fill'>
                                                <h4>Wish List</h4>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="row border-top">
                                    <div class="col px-0">
                                        <div class="container px-2">
                                                <!-- ITEMS SUMMARY -->
                                            <table class="table table-hover borderless mt-4">
                                                
                                            
                        
                                                <?php 

                                                    $sql = "SELECT w.*, p.img_path, p.name, p.price
                                                            FROM tbl_wishlists w 
                                                            JOIN tbl_items p on p.id=w.product_id 
                                                            WHERE user_id= ? ";
                                                        $statement = $conn->prepare($sql);
                                                        $statement->execute([$id]);
                                                        $count = $statement->rowCount();
                                     
                                                    if($count) {
                                                    while($row = $statement->fetch()){ 
                                                        $productId = $row['product_id'];
                                                        $name = $row['name'];
                                                        $price = $row['price'];
                                                        $item_img = productprofile($conn,$productId);
										                $item_img = BASE_URL ."/".$item_img.".jpg";

                                                ?>
                                                

                                                
                                                <tr id='wish-row<?=$productId?>'>
                                                    
                                                    
                                                    <!-- IMAGE, NAME AND VARIATION -->
                                                    <td class='mx-0'> 
                                                        <a href="product.php?id=<?=$productId?>" target='_blank'>
                                                            <div class='d-flex flex-row align-items-center mx-0 px-1' style='justify-content:flex-start;'>
                                                                <div class='flex pr-2'>
                                                                    <img src='<?=$item_img?>' style='width:80px;height:80px;'>
                                                                </div>   
                                                                <div class='flex-fill'>
                                                                    <div class='d-flex flex-column'>
                                                                    
                                                                            <div>
                                                                                <div class='text-secondary'><?= $name ?></div>
                                                                            </div>
                                                                            <div>
                                                                                <span class='text-gray'>&#36;&nbsp;</span>
                                                                                <span class='text-gray'><?= $price ?></span>
                                                                            </div>
                                                                    
                                                                        
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </a> 
                                                    </td>

                                                    <!-- ADD TO CART -->
                                                    <!-- CAN'T DO THIS ANYMORE SINCE YOU NEED TO SELECT VARIATION FIRST -->

                                                    <!-- DELETE -->
                                                    <td>
                                                        <button data-productid='<?=$productId?>' type="button" class="close btn_delete_wish" aria-label="Close">
                                                            <span aria-hidden="true" class='font-weight-light text-secondary' style='font-size:20px;'>&times;</span>
                                                        </button>

                                                    </td>
                                                    
                                                </tr>
                        
                                                <?php } } ?>
                                            


                                            </table>

                                        </div>

                                    </div>
                                </div>
                            </div>
                            

                            <!-- MESSAGES -->
                            <div class="container p-lg-5 p-md-5 px-sm-2 rounded mb-5 pt-5" style='background:white;height:650px;'>
                                <div class="row mb-3">
                                    <div class="col-4">   
                                        <h4>Inbox</h4>  
                                    </div>
                                    <div class="col">
                                        <!-- <input type="text" 
                                            placeholder="&#128269;" 
                                            class='border-bottom border-top-0 border-right-0 border-left-0' 
                                            id='search_store_name' style='width:100%;'>
                                    </div> -->
                                        <div class="input-group" style='width:100%;'>
                                            <div class="input-group-prepend">
                                                <span class="input-group-text border-right-0 border-left-0 border-top-0" style='background:white;'>
                                                    <i class="fas fa-search" style='background:white;'></i>
                                                </span>
                                            </div>
                                            <input type="text" class="form-control border-right-0 border-left-0 border-top-0" id="search_store_name">
                                        </div>
                                    </div>
                                    
                                </div>
                                
                                <div class='row'>
                                    <!-- SELLERS/STORES -->
                                    <div class="col-lg-3 col-md-3 col-sm-3 mx-0 px-0" style='background:white;height:530px;overflow-y:auto;'>
                                       
                                        <table class="table table-hover borderless" id='sender_container'>
                                            
                                        
                    
                                            <?php 
                                                // CHECK IF THERE IS AN EXISTING CONVERSATIONS INITIATED BY THE BUYER
                                                $sql = "SELECT * FROM tbl_conversations 
                                                WHERE `from` = ? OR `to` =?"; // named parameters
                                                $statement = $conn->prepare($sql);
                                                $statement->execute([$id, $id]);
                                                if($statement->rowCount()) {
                                                    while($row = $statement->fetch()){
                                                    $conversationId = $row['id'];  
                                                    $sellerId = $row['to'];
                                                    $adminId = $row['from'];

                                                    if(getStoreLogo ($conn,$sellerId)){
                                                        $logo = getStoreLogo ($conn,$sellerId);
                                                        $logo = BASE_URL ."/". $logo .".jpg";
                                                    } else {
                                                        $logo = getProfilePic($conn,$adminId);
                                                        $logo = BASE_URL ."/". $logo .".jpg";
                                                    }

                                            ?>
                                            

                                            
                                            <tr>
                                                
                                                
                                                <!-- IMAGE, NAME AND VARIATION -->
                                                <td> 
                                                    <a data-sellerid='<?= $sellerId ?>' data-conversationid='<?=$conversationId?>' class='selected_conversation'>
                                                        <div class='d-flex flex-row align-items-center' style='justify-content:flex-start;'>
                                                            <div class='flex'>
                                                                <img src='<?= $logo ?>' style='width:40px;height:40px;' class='circle'>
                                                            </div>   
                                                            <div class='flex-fill vanish-sm vanish-md'>
                                                                <div class='d-flex flex-column'>
                                                                
                                                                        
                                                                    <small class='text-secondary'><?= getStoreName ($conn,$sellerId) ?></small>
                                                                    <!-- DATE SENT -->

                                                                </div>
                                                            </div>
                                                        </div>
                                                    </a> 
                                                </td>

                            
                                            </tr>
                    
                                            <?php } } ?>
                                        


                                        </table>
                                        
                                    </div>
                                    
                                    <!-- MESSAGES -->
                                    <div class="col mx-0 px-0 no-gutters">
                                        <div class="container border-bottom-0  mx-0 px-0 no-gutters">
                                            <input type='hidden' id='profile_conversation_id'>    
                                            <div class="row no-gutters">
                                                <div class="col-12" id='profile_message_container' style='background:white;height:460px;overflow-y:auto;'>
                                                <!-- WHERE FETCHED MESSAGES ARE DISPLAYED -->
                                                </div>
                                            </div>
                                            <div class="row no-gutters">
                                                <div class="col-12">
                                                    <form>
                                                        <textarea class="form-control border-0" 
                                                            id="profile_message_input" 
                                                            data-sellerid='<?= $sellerId ?>' 
                                                            style='width:100%;background:#eff0f5;resize:none;' 
                                                            rows='3'></textarea>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>

                            <!-- DEACTIVATE -->
                            <div class='container p-5 rounded mb-5' style='background:white;'>
                                <div class="row mb-3">
                                    <div class="col d-flex flex-lg-row flex-md-row flex-sm-column">

                                
                                        <div class='flex-fill d-flex flex-row'>
                                            <h4>Deactivate</h4>
                                            <a data-toggle="tooltip" title="Mamaroo sellers will need to wait for 3 days for the admin to process this request. Deactivation of seller account  will lead to deletion of his/her store." data-original-title="#">
                                                &nbsp;<i class="far fa-question-circle text-gray"></i>
                                            </a>
                                        </div>
                                        <div class='flex-fill text-right pt-lg-0 pt-md-0 pt-sm-4'>
                                            <button class='btn btn-border border text-gray' id='btn_deactivate'>
                                                <?php 
                                                    $sql =  "SELECT * FROM tbl_users WHERE `status` = 2 AND id = ?";
                                                    $statement = $conn->prepare($sql);
                                                    $statement->execute([$_SESSION['id']]);

                                                    $count = $statement->rowCount();
                                                    if($count){
                                                        echo "PENDING DEACTIVATION";
                                                    } else {
                                                        echo "DEACTIVATE MY ACCOUNT";
                                                    }
                                                ?>        
                                            </button>
                                        </div>


                                    </div>
                                </div>

                            </div>
                                

                           

                        </div>

                    </div>

                </div>
                <!-- /SECOND ROW -->
                
                

            </div>
            <!-- /MAIN BAR -->


        </div>
        <!-- /.ROW -->
    </div>
    <!-- /.CONTAINER -->


<?php require_once "../partials/footer.php";?>
<?php require_once "../partials/modal_container.php";?>
<?php require_once "../partials/modal_container_big.php"; ?>

  