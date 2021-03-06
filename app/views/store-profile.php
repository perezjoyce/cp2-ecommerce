<?php require_once "../../config.php";?>
<?php 
    $id = $_GET['id'];
   
    // get the store information
    $storeInfo = getStore($conn,$id);
    $id = isset($_SESSION['id']) ? $_SESSION['id'] : null ;
    try {
        $currentUser = getUser($conn, $id);
        $isSeller = $currentUser['isSeller'] == "yes" ? 1 : 0;    
    } catch (\Exception $e) {
        $currentUser = null;
        $isSeller = null;
    }
    
    if ($isSeller && $currentUser && $currentUser['id'] == $storeInfo['user_id']) {
        require_once "../partials/store_header.php";
    } else {
        require_once "../partials/header.php";
    }
        $sellerId = $storeInfo['user_id'];
        $storeId = $storeInfo['id'];
        $storeName = $storeInfo['name'];
        $storeLogo = $storeInfo['logo'];

        if($storeLogo == null) {
            $storeLogo = DEFAULT_STORE; 
        } else {
            $storeLogo = BASE_URL ."/". $storeLogo . ".jpg";
        } 


        $storeDescription = $storeInfo['description'];
        $storeAddress = $storeInfo['store_address'];
        $storeHours = $storeInfo['hours'];
        $storeFollowers = countFollowers ($conn, $storeId);
        $storeRating = getAverageStoreRating ($conn, $storeId);
        $storeMembershipDate = getMembershipDate($conn, $storeId);
        $storeShippingFee = displayStoreShippingFee($conn,$storeId);
        // var_dump($storeShippingFee);die();
        $storeFreeShippingMinimum = displayStoreFreeShipping($conn,$storeId);
        $fname = getFirstName ($conn,$sellerId);
        $lname = getLastName ($conn,$sellerId);

    
?>
    <!-- PAGE CONTENT -->
    <br>
    <div class="container p-0 my-lg-5 mt-md-5" id='store_page_container'>


        <div class="row mx-0">

          
            <!-- MAIN BAR-->
            <div class="col">

                <!-- PROFILE -->
                <div class='container p-5 rounded mb-5' style='background:white;'>
                    <div class="row mb-3">

                        <div class="col">
                            <div class='d-flex flex-lg-row flex-md-row flex-sm-column'>
                                <div class='flex-fill'>
                                    <div class="d-flex align-items-center flex-lg-row flex-md-row flex-sm-column">
                                        <div class='pr-lg-3 pr-md-3 pr-sm-0 mb-lg-0 mb-md-0 mb-sm-5'>
                                            <img src='<?= $storeLogo ?>' class='rounded-circle store_page_logo'>
                                        </div>

                                        <div class="d-flex flex-column">
                                            <h3><?= $storeName ?></h3>

                                            <div class='d-flex flex-row'>
                                                <?php 
                                                    $withPermit = checkifwithpermit($conn, $storeId);
                                                    if($withPermit == 2){
                                                ?>
                                                
                                                    <i class="fas fa-check-circle pr-1 text-purple"></i>
                                                    <small class="text-purple">VERIFIED SELLER</small>

                                                <?php } ?>
                                            </div>

                                            <div class="text-gray">
                                                <?
                                                    $sql = "SELECT last_login FROM tbl_users WHERE id = ?";
                                                    $statement = $conn->prepare($sql);
                                                    $statement->execute([$sellerId]);	
                                                    $row = $statement->fetch();
                                                    $lastLogin = $row['last_login'];
                                                    
                                                ?>
                                                <small id='lastLoginTimeAgo'><?= $lastLogin ?></small>
                                            </div>
                                            
                                        </div>
                                      
                                    </div>
                                </div>
                                
                                <div class='flex-fill text-lg-right text-md-right text-sm-center mt-lg-0 mt-md-0 mt-sm-5'>
                                    <div class="d-flex flex-column">
                                        <?php if ($isSeller && $currentUser['id'] == $storeInfo['user_id']) { ?>
                                            <a class='nav-link modal-link px-0' href='#' data-id='<?= $storeId ?>' data-url='../partials/templates/upload_store_pic_modal.php' role='button'>
                                                <i class="fas fa-camera"></i>
                                                Update Image
                                            </a>
                                            <div class="text-gray">
                                                <small>File size: Max of 1MB</small>
                                            </div>
                                            <div class="text-gray">
                                                <small>File extension: jpg, jpeg, png</small>
                                            </div>
                                        <?php } else { ?>
                                            <div class="text-gray" id='btn_follow_container'>
                                                
                                                <?php 
                                                    if(isset($_SESSION['id'])){
                                                        $sql = "SELECT * FROM tbl_followers WHERE user_id =? AND store_id =?";
                                                        $statement = $conn->prepare($sql);
                                                        $statement->execute([$currentUser['id'], $storeId]);
                                                        $count = $statement->rowCount();

                                                        if(!$count) {
                                                ?>
                                                <button class='btn btn-purple' id='btn_follow' data-id='<?=$storeId?>'>
                                                    &#65291; Follow
                                                </button>
                                                <?php } else {?>
                                                <button class='btn border text-gray' id='btn_follow' data-id='<?=$storeId?>'>
                                                    &#8722; Unfollow
                                                </button>
                                                <?php } } else { echo ""; } ?>
                                            </div>
                                       <?php } ?>
                                    </div>
                                </div>
                                

                                
                            </div>
                        </div>
                    </div>

                </div>

                <!-- INVITATION TO REGISTER -->
                <?php 
                 if ($isSeller && $currentUser['id'] == $storeInfo['user_id']) {
                    $withPermit = checkifwithpermit($conn, $storeId);
                    if($withPermit == 0){
                ?>
                
                <div class="container p-5 rounded mb-5" style="background:white;">
                    <div class="row">

                        <div class="col">
                            <div class="d-flex flex-column text-center p-lg-5 p-md-5 p-sm-3">
                                <div class="flex-fill mb-5">
                                    Submit a scanned copy of your business permit for review to get a verified seller badge.
                                </div>
                                <div class="flex-fill text-center">
                                    <a class="modal-link btn btn-purple" href="#" data-id="<?= $storeId ?>" data-url="../partials/templates/upload_store_permit_modal.php" role="button">
                                        UPLOAD PERMIT
                                    </a>                               
                                </div>                                
                            </div>
                        </div>
                    </div>

                </div>

                <?php } } ?>

                <!-- SECOND ROW -->
                <div class='container p-0'>
                    <div class="row">

                        
                        <div class="col-lg-6 col-md-6">

                            <!-- ABOUT -->
                            <div class="container p-5 rounded mb-5" style='background:white;height:250px;overflow-y:auto;'>
                                <?php if ($isSeller && $currentUser['id'] == $storeInfo['user_id']) { ?>        
                                <div class="row mb-3 border-bottom pb-2">
                                    <div class="col">
                                        <div class='d-flex flex-row'>
                                            <div class='flex-fill'>
                                                <div class="d-flex flex-row">
                                                    <h4>About</h4>
                                                    <a data-toggle="tooltip" title="Describe what makes your shop unique or share your vision statement here." data-original-title="#">
                                                        &nbsp;<i class="far fa-question-circle text-gray"></i>
                                                    </a>
                                                </div>
                                            </div>
                                            <div class='flex-fill text-right'>
                                                <a class='nav-link modal-link' href='#' data-id='<?= $storeId ?>' data-url='../partials/templates/edit_store_description_modal.php' role='button'>
                                                    <i class="far fa-edit"></i>
                                                    Edit
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <?php } else { echo ""; } ?>
                              
                                
                                <div class="row">
                                    <div class="col"> 
                                       <div class="mt-5" id='store_profile_description' style='line-height:1.8;'>
                                           <?= $storeDescription ?>
                                       </div>
                                    </div>
                                </div>
                            </div>

                          
                            <!-- BUSINESS DETAILS -->
                            <div class="container p-5 rounded mb-5" style='background:white;'>
                                <div class="row mb-3">
                                    <div class="col">
                                        <div class='d-flex flex-row'>
                                            <div class='flex-fill'>
                                                <h4>Business Details</h4>
                                            </div>
                                            <?php if ($isSeller && $currentUser['id'] == $storeInfo['user_id']) { ?>    
                                            <div class='flex-fill text-right'>
                                                <a class='nav-link modal-link' href='#' data-id='<?= $storeId ?>' data-url='../partials/templates/edit_store_details_modal.php' role='button'>
                                                    <i class="far fa-edit"></i>
                                                    Edit
                                                </a>
                                            </div>
                                            <?php } else { echo ""; } ?>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="row border-top">
                                    <div class="col">
                                        <div class="container px-0">

                                            <div class="row my-5">
                                                <div class="col-lg-3 col-md-5 col-sm-5">
                                                    Owner
                                                    <?php if ($isSeller && $currentUser['id'] == $storeInfo['user_id']) { ?>
                                                    <a data-toggle="tooltip" title="Please coordinate with the admin for changes in store ownership." data-original-title="#">
                                                        &nbsp;<i class="far fa-question-circle text-gray"></i>
                                                    </a>
                                                    <?php } ?>
                                                </div>
                                                <div class="col">
                                                    <?= ucwords(strtolower($fname)) . " " . ucwords(strtolower($lname)) ?>
                                                </div>
                                            </div>  

                                            <div class="row mb-5">
                                                <div class="col-lg-3 col-md-5 col-sm-5">
                                                    Address
                                                </div>
                                                <div class="col" id='store_profile_address'>
                                                    <?= $storeAddress ?>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-3">
                                                    Hours
                                                </div>
                                                <div class="col" id='store_profile_hours'>
                                                    <?= $storeHours ?>
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                            </div>
  

                        </div>


                        <div class="col-lg-6 col-md-6">

                            <!-- STATS -->
                            <div class="container p-5 rounded mb-5" style='background:white;'>
                                <div class="row mb-3">
                                    <div class="col">
                                        <div class='d-flex flex-row'>
                                            <div class='flex-fill'>
                                                <h4>Mamaroo Stats</h4>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="row border-top">
                                    <div class="col">
                                        <div class="container px-0">

                                            <div class="row my-4">
                                                <div class="col-lg-3 col-md-5 col-sm-5 pt-5">
                                                    Rating
                                                </div>
                                                <div class="col">
                                                    <div class="d-flex flex-row">
                                                        <h1 class='font-weight-bold text-warning rating-font'><?= $storeRating ?></h1>
                                                        <h1 class='text-gray font-weight-light pt-5'>&nbsp;/ 5</h1>
                                                    </div> 
                                                </div>
                                            </div>  

                                            <div class="row mb-5">
                                                <div class="col-lg-3 col-md-5 col-sm-5">
                                                    Followers
                                                </div>
                                                <div class="col">
                                                    <?= $storeFollowers ?>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-lg-3 col-md-5 col-sm-5">
                                                    Member Since
                                                </div>
                                                <div class="col">
                                                    <?= $storeMembershipDate ?>
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- SHIPPING RATES -->
                            <div class="container p-5 rounded mb-5" style='background:white;'>
                                <div class="row mb-3">
                                    <div class="col">
                                        <div class='d-flex flex-row'>
                                            <div class='flex-fill'>
                                                <h4>Shipping Rates</h4>
                                            </div>
                                            <?php if ($isSeller && $currentUser['id'] == $storeInfo['user_id']) { ?>    
                                            <div class='flex-fill text-right'>
                                                <a class='nav-link modal-link' href='#' data-id='<?= $storeId ?>' data-url='../partials/templates/edit_store_shipping_modal.php' role='button'>
                                                    <i class="far fa-edit"></i>
                                                    Edit
                                                </a>
                                            </div>
                                            <?php } else { echo ""; } ?>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="row border-top">
                                    <div class="col">
                                        <div class="container px-0">

                                            <div class="row mt-5">
                                                <div class="col-lg-3 col-md-4 col-sm-4">
                                                    Standard 
                                                </div>
                                                <div class="col">
                                                    <span>&#36;&nbsp;</span>
                                                    <span class='store_profile_standard_fee'><?= $storeShippingFee ?></span>
                                                </div>
                                            </div>  
                                            
                                            <?php if($storeFreeShippingMinimum) { ?>
                                            <div class="row mt-5">
                                                <div class="col-lg-3 col-md-4 col-sm-4">
                                                    Free
                                                </div>
                                                <div class="col">
                                                    <span>&nbsp;&#36;</span>
                                                    <span class='store_profile_free_shipping'><?= $storeFreeShippingMinimum ?></span>
                                                    <span>&nbsp;Minimum Spend</span>
                                                </div>
                                            </div>
                                            <?php } ?>

                                        </div>
                                    </div>
                                </div>
                            </div>

                            

                        </div>

                    </div>

                </div>
                <!-- /SECOND ROW -->


                
                <!-- SEARCH BAR -->
                <div class='container p-5 rounded mb-5' style='background:white;'>
                    <div class="row mx-0">
                        <div class="col-lg-6 col-md-6 col-sm-12">
                            <h4>Search For Products</h4>
                        </div>
                        <div class="col">
                            <div class="input-group input-group-lg">
                                <div class="input-group-prepend">
                                    <span class="input-group-text border-right-0 border-left-0 border-top-0" style='background:white;'>
                                        <i class="fas fa-search" style='background:white;'></i>
                                    </span>
                                </div>
                                <input type="text" class="form-control border-right-0 border-left-0 border-top-0" id="store_page_search" data-storeid='<?= $storeId ?>'>
                            </div>
                        </div>
						
					</div>
                </div>


                <!-- PRODUCTS FROM SELLER -->
                <div class="container p-0">
                    
                    <div class="row no-gutters justify-content-left" id='store_page_product_container'>
                        <?php 
                            $productId = $_GET['id'];
                            $sql2 = "SELECT * FROM tbl_items WHERE store_id = ? ";
                            $statement2 = $conn->prepare($sql2);
                            $statement2->execute([$storeId]);

                            if($statement2->rowCount()){
                                while($row2 = $statement2->fetch()){
                                $id = $row2['id'];
                                $name = $row2['name'];
                                $price = $row2['price'];
                                $price = number_format((float)$price, 2, '.', '');
                                $description = $row2['description'];
                                $logo = productprofile($conn,$id);
                                $logo = BASE_URL ."/".$logo.".jpg";
                            
                        ?>
                    
                        <div class="col-lg-3 col-md-4 col-sm-6 p-2">
                            <a href="product.php?id=<?= $id ?>">
                            <div class='card border-0'>
                                <a href="product.php?id=<?= $row2['id'] ?>">
                                <img class='card-img-top card-profilepic' src="<?= $logo ?>">

                                <div class="card-body p-lg-4 p-md-3 p-sm-p5">
                                    <div>
                                    <?= $name ?>
                                    </div>
                                    <div>&#36;
                                    <?= $price ?> 
                                    </div>

                                    <div class='d-flex flex-row mt-3'>
                 
                                        <!-- WISHLIST BUTTONS -->
                                        <div class='flex-fill' style='cursor:default;'>

                                        <?php 
                                            $wishCount = getProductWishlishtCount($conn,$id);
                                            if(isset($_SESSION['id'])) {
                                                if (checkIfInWishlist($conn,$id)) {
                                        ?>
                                            <a class='heart-toggler' data-id='<?= $id ?>' role='button' data-enabled='0' style='float:left'>
                                            <span class='wish_heart'><i class='fas fa-heart text-purple' id></i></span>
                                            <span class='product_wish_count'>
                                                <small>
                                                <?= $wishCount ?>
                                                </small>
                                            </span>
                                            </a>
                                    
                                        <?php  } else { ?>

                                            <a class='heart-toggler' data-id='<?= $id ?>' data-enabled='1' style='float:left'>
                                            <span class='wish_heart'><i class='far fa-heart text-purple'></i></span> 
                                            <span class='product_wish_count'>
                                                <small>
                                                <?php
                                                    if($wishCount == 0){
                                                    echo "";
                                                    } else {
                                                    echo $wishCount;
                                                    }
                                                ?>
                                                </small>
                                            </span>
                                            </a>

                                        <!-- IF LOGGED OUT -->
                                        <?php }  } else { 
                                            
                                            if($wishCount >= 1) {
                                        ?>
                                            
                                            <a class='btn_wishlist_logout_view' data-id='<?= $id ?>' disabled style='cursor:default; float:left'>
                                            <i class='far fa-heart text-purple'></i> 
                                            <span class='product_wish_count'>
                                                <small>
                                                <?= $wishCount ?>
                                                </small>
                                            </span>
                                            </a>
                                            
                                        <?php } else { ?>
                                            <a class='btn_wishlist_logout_view' data-id='<?= $id ?>' disabled style='cursor:default; float:left'>
                                            <i class='far fa-heart text-gray'></i> 
                                            </a>
                                            
                                        <?php } } ?>
                                        </div>

                                        <!-- AVERAGE STAR RATING -->
                                        <div class='flex-fill text-right'>
                                        <div class="ratings">
                                            <div class="empty-stars"></div>
                                            <div class="full-stars" style="width:<?=getProductRating($conn, $id)?>%"></div>
                                        </div>
                                        </div>
                                        <!-- /AVERAGE STAR RATING -->
                                    </div>

                                </div>
                                </a> 
                            </div>
                            </a>
                        </div>
                        
                            
                        <?php } } ?>

                    </div>
                </div>


            </div>
            <!-- /MAIN BAR -->


        </div>
        <!-- /.ROW -->
    </div>
    <!-- /.CONTAINER -->

    <!-- IF USER IS LOGGED IN AND USER IS NOT THE SELLER -->
    <?php if(isset($_SESSION['id']) && !$isSeller){ require_once '../partials/message_box.php'; } ?>

<?php require_once "../partials/footer.php";?>
<?php require_once "../partials/modal_container.php";?>
<?php require_once "../partials/modal_container_big.php"; ?>

  