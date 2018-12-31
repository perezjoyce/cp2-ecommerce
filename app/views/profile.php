<?php require_once "../../config.php";?>
<?php require_once "../partials/header.php";?>
<?php require_once "../controllers/connect.php";?>
<?php require_once "../controllers/functions.php";?>

<?php 

    if(!isset($_SESSION['id'])) {
        // ob_clean();
        // header("location: index.php?msg=NotLoggedIn"); // doesn't work because header already exists
        // ECHO THIS TO REDIRECT YOU TO HEADER
        echo "<script>window.location.href='".BASE_URL."/app/views/'</script>";
    }

    $id = $_GET['id'];
    if(empty($id)) {
        header("location: index.php");
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
            $profile_pic = BASE_URL . $profile_pic . "_80x80.jpg";
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
                        <div class="col-12">
                            <div class='d-flex flex-lg-row flex-md-row flex-sm-column'>
                                <div class='flex-fill'>
                                    <div class="d-flex align-items-center">
                                        <div class='pr-3'>
                                            <img src='<?= $profile_pic ?>'  class="user-photo circle" height="90px;">
                                        </div>
                                        <div class="d-flex flex-column">
                                            <div>
                                                <h3><?= $fname . " " . $lname ?></h3>
                                            </div>
                                            
                                            <div class="text-gray">
                                                <?
                                                    $sql = "SELECT last_login FROM tbl_users WHERE id = ?";
                                                    $statement = $conn->prepare($sql);
                                                    $statement->execute([$id]);	
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
                                                ?>
                                                <small><?=$ago?></small>
                                             </div>

                                        </div>
                                    </div>
                                </div>
                                <div class='flex-fill text-right'>
                                    <div class="d-flex flex-column">
                                        <a class='nav-link modal-link px-0' href='#' data-id='<?= $id ?>' data-url='../partials/templates/upload_modal.php' role='button'>
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

                        
                        <div class="col-lg-6 col-md-6">
                            <!-- BASIC INFO -->
                            <div class="container p-5 rounded mb-5" style='background:white;'>
                                <div class="row mb-3">
                                    <div class="col">
                                        <div class='d-flex flex-row'>
                                            <div class='flex-fill'>
                                                <h4>Basic Information</h4>
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

                                            <div class="row my-5">
                                                <div class="col-3">
                                                    Name
                                                </div>
                                                <div class="col">
                                                    <?= $fname . " " . $lname ?>
                                                </div>
                                            </div>  

                                            <div class="row mb-5">
                                                <div class="col-3">
                                                    Username
                                                </div>
                                                <div class="col">
                                                    <?= $username ?>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-3">
                                                    Email
                                                </div>
                                                <div class="col">
                                                    <?= hide_email($email) ?>
                                                </div>
                                            </div>

                                        </div>

                                    </div>
                                    <!-- /LEFT OF MAIN BAR -->

                                
                                </div>
                                <!-- ================ -->

                            </div>

                             <!-- ORDER HISTORY -->
                             <div class="container p-5 rounded mb-5" style='background:white;height:600px;overflow-y:auto;'>
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
                                        <div class="container px-0">
                                            <table class="table table-hover borderless mt-4 text-center">
                                                <tr>
                                                    <h4 class='text-gray pl-3 mt-5'>Pending Orders</h4>
                                                    
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

                                            <?php } ?>
                                        
                                        </div>
                                    </div>
                                </div>

                                <!-- COMPLETED TRANSACTIONS -->
                                <?php
                                    $sql = "SELECT o.*, s.name as 'status' FROM tbl_orders o JOIN tbl_status s ON o.status_id=s.id WHERE `user_id` = ? AND status_id = 2 ORDER BY o.purchase_date DESC";
                                            $statement = $conn->prepare($sql);
                                            $statement->execute([$id]);
                                            $count = $statement->rowCount();
                            
                                        if($count) {
                                ?>
                                <div class="row border-top">
                                    <div class="col px-2">
                                        <div class="container px-0">
                                            
                                            <table class="table table-hover borderless mt-4 text-center">
                                                <tr>
                                                    <h4 class='text-gray pl-3 mt-5'>Completed Orders</h4>
                                                    
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

                                            <?php } ?>

                                        </div>

                                    </div>
                                </div>


                                <!-- CANCELLED TRANSACTIONS -->
                                <?php
                                    $sql = "SELECT o.*, s.name as 'status' FROM tbl_orders o JOIN tbl_status s ON o.status_id=s.id WHERE `user_id` = ? AND status_id = 3 ORDER BY o.purchase_date DESC";
                                            $statement = $conn->prepare($sql);
                                            $statement->execute([$id]);
                                            $count = $statement->rowCount();
                            
                                        if($count) {
                                ?>
                                <div class="row border-top">
                                    <div class="col px-2">
                                        <div class="container px-0">
                                            
                                            <table class="table table-hover borderless mt-4 text-center">
                                                <tr>
                                                    <h4 class='text-gray pl-3 mt-5'>Cancelled Orders</h4>
                                                    
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

                                            <?php } ?>

                                        </div>

                                    </div>
                                </div>

                            </div>


                             <!-- PRODUCTS TO REVIEW -->
                             <div class="container p-5 rounded mb-5" style='background:white;height:550px;overflow-y:auto;'>
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
                                    <div class="col px-0">
                                        <div class="container px-2">
                                               
                                            <table class="table table-hover borderless mt-4">
                                                
                                            
                        
                                                <?php 
                                                    $sql = "SELECT cart_session,purchase_date 
                                                            FROM tbl_orders WHERE `user_id` = ? 
                                                            AND status_id = 2 ORDER BY purchase_date DESC";
                                                        $statement = $conn->prepare($sql);
                                                        $statement->execute([$id]);
                                                        $count = $statement->rowCount();
                                     
                                                    if($count) {
                                                    while($row = $statement->fetch()){ 
                                                        $completeOrderSession = $row['cart_session'];

                                                        $sql2 = "SELECT variation_id FROM tbl_carts WHERE cart_session = ?";
                                                            $statement2 = $conn->prepare($sql2);
                                                            $statement2->execute([$completeOrderSession]);
                                                        
                                                        while($row2 = $statement2->fetch()){ 
                                                            $completeOrderVariationId = $row2['variation_id'];
                                                            
                                                            $sql3 = "SELECT v.id as 'variationId', v.variation_name as 'variationName', i.id as 'productId', i.name as 'productName', i.price, i.img_path, i.store_id FROM tbl_variations v JOIN tbl_items i ON v.product_id=i.id WHERE v.id = ? GROUP BY i.id";
                                                                $statement3 = $conn->prepare($sql3);
                                                                $statement3->execute([$completeOrderVariationId]);

                                                                while($row3 = $statement3->fetch()){ 
                                                                    $completeOrderVariationName = $row3['variationName'];
                                                                    $completeOrderProductId = $row3['productId'];
                                                                    $completeOrderProductName = $row3['productName'];
                                                                    $completeOrderPrice = $row3['price'];
                                                                    $completeOrderLogo = $row3['img_path'];
                                                                    $completeOrderStoreId = $row3['store_id'];

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
                                                                                <span class='text-gray'>&#8369;&nbsp;</span>
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
                        
                                                <?php } } } } ?>
                                            


                                            </table>

                                        </div>

                                    </div>
                                </div>
                            </div>


                            

                        </div>


                        <div class="col-lg-6 col-md-6">

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
                                                        $image = $row['img_path'];

                                                ?>
                                                

                                                
                                                <tr id='wish-row<?=$productId?>'>
                                                    
                                                    
                                                    <!-- IMAGE, NAME AND VARIATION -->
                                                    <td class='mx-0'> 
                                                        <a href="product.php?id=<?=$productId?>" target='_blank'>
                                                            <div class='d-flex flex-row align-items-center' style='justify-content:flex-start;'>
                                                                <div class='flex pr-2'>
                                                                    <img src='<?=$image?>' style='width:80px;height:80px;'>
                                                                </div>   
                                                                <div class='flex-fill'>
                                                                    <div class='d-flex flex-column'>
                                                                    
                                                                            <div>
                                                                                <div class='text-secondary'><?= $name ?></div>
                                                                            </div>
                                                                            <div>
                                                                                <span class='text-gray'>&#8369;&nbsp;</span>
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
                            <div class="container p-5 rounded mb-5" style='background:white;height:650px;'>
                                <div class="row mb-3">
                                    <div class="col">
                                        <div class='d-flex flex-row'>
                                            <div class='flex-fill'>
                                                <h4>Inbox</h4>
                                            </div>
                                            <div class="flex-fill">
                                                <input type="text" style='width:100%;'>
                                            </div>
                                        </div>
                                        
                                    </div>
                                </div>
                                
                                <div class="row border-top border-bottom border-top">
                                    <div class="col-lg-3 col-md-3 col-sm-3 px-0" style='background:white;height:530px;overflow-y:auto;'>
                                        <div class="container px-0">
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
                                                        $image = $row['img_path'];

                                                ?>
                                                

                                                
                                                <tr id='wish-row<?=$productId?>' class='mx-0 px-0'>
                                                    
                                                    
                                                    <!-- IMAGE, NAME AND VARIATION -->
                                                    <td class='mx-0 px-0'> 
                                                        <a href="product.php?id=<?=$productId?>" target='_blank'>
                                                            <div class='d-flex flex-row align-items-center' style='justify-content:flex-start;'>
                                                                <div class='flex'>
                                                                    <img src='<?=$image?>' style='width:40px;height:40px;' class='circle'>
                                                                </div>   
                                                                <div class='flex-fill vanish-sm'>
                                                                    <div class='d-flex flex-column'>
                                                                    
                                                                            
                                                                        <small class='text-secondary'><?= $name ?></small>
                                                                        <!-- DATE SENT -->
                                                                        <small class='text-secondary'><?= $name ?></small> 
                                                                                              
                                                                        
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
                                    <div class="col mx-0 px-0 no-gutters">
                                        <div class="container border-left border-right border-bottom-0  mx-0 px-0 no-gutters">
                                            <div class="row no-gutters" style='background:white;height:480px;overflow-y:auto;'>
                                                <div class="col-12"></div>
                                            </div>
                                            <div class="row border-top no-gutters" style='background:#eff0f5;height:50px;'>
                                                <div class="col-12">
                                                    <form action='process_ask_about_product' method='POST'>
                                                        <textarea class="form-control border-0" id="messageTextarea" style='width:100%;background:#eff0f5;resize:none;' rows='2'></textarea>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>
                           

                        </div>

                    </div>

                </div>
                <!-- /SECOND ROW -->


                <!-- MESSEGING -->
                <div class="container p-5 rounded mb-5" style='background:white;' id="main-wrapper">
                    <div class="row mb-3">
                        <div class="col">
                            <div class='d-flex flex-row'>
                                <div class='flex-fill'>
                                    <h4>Messages</h4>
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
                        <div class="col-lg-6 col-md-4 col-sm-12">
                            <div class="container px-0">

                                <div class="row my-5">
                                    <div class="col-3">
                                        Name
                                    </div>
                                    <div class="col">
                                        <?= $fname . " " . $lname ?>
                                    </div>
                                </div>  

                                <div class="row mb-5">
                                    <div class="col-3">
                                        Username
                                    </div>
                                    <div class="col">
                                        <?= $username ?>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-3">
                                        Email
                                    </div>
                                    <div class="col">
                                        <?= hide_email($email) ?>
                                    </div>
                                </div>

                            </div>

                        </div>
                        <!-- /LEFT OF MAIN BAR -->

                       
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

  