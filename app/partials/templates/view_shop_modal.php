<?php 
    require_once '../../../config.php';

    $storeId = $_GET['id'];
    $storeInfo = getStore ($conn,$storeId);
    $sellerId = $storeInfo['user_id'];
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
   
    $storeFreeShippingMinimum = displayStoreFreeShipping($conn,$storeId);
    $fname = getFirstName ($conn,$sellerId);
    $lname = getLastName ($conn,$sellerId);

?>

<div class="container-fluid" id='view_shop_modal'>
    <div class="row">
        
        <div class="col" style='height:80vh;overflow-y:auto;' id='printThis'>

            <div class="row float-right">
                <button id='close_modal' type="button" class="close mr-3 mt-2" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true" class='font-weight-light text-secondary' style='font-size:20px;'>&times;</span>
                </button>
            </div>

            <div class="container p-0 my-lg-5 mt-md-5" id='store_page_container'>

                <div class="row mx-0">

                    
                    <!-- MAIN BAR-->
                    <div class="col p-0">

                        <!-- PROFILE -->
                        <div class='container p-5 border mt-lg-0 mt-md-0 mt-sm-5' style='background:white;'>
                            <div class="row mb-3">

                                <div class="col">
                                    <div class='d-flex flex-lg-row flex-md-row flex-sm-column'>
                                        <div class='flex-fill'>
                                            <div class="d-flex align-items-center flex-lg-row flex-md-row flex-sm-column">
                                                <div class='pr-lg-3 pr-md-3 pr-0-sm'>
                                                    <img src='<?= $storeLogo ?>' class='rounded-circle store_page_logo'>
                                                </div>
                                                <div class="d-flex flex-column pt-lg-0 pt-md-0 pt-sm-4 text-lg-left text-md-left text-sm-center">

                                               
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
                                        
                                        <div class='flex-fill text-md-right text-lg-right text-sm-center pt-lg-0 pt-md-0 pt-sm-4'>
                                            <div class="d-flex flex-column">
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
                                            </div>
                                        </div>
                                        

                                        
                                    </div>
                                </div>
                            </div>

                        </div>

                        <!-- SECOND ROW -->
                        <div class='container p-0'>
                            <div class="row">

                                
                                <div class="col-lg-6 col-md-6 px-sm-0">

                                    <!-- ABOUT -->
                                    <div class="container p-5 rounded" style='background:white;height:250px;overflow-y:auto;'>
                                        <div class="row">
                                            <div class="col"> 
                                                <div class="mt-5" id='store_profile_description' style='line-height:1.8;'>
                                                    <?= $storeDescription ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    
                                    <!-- BUSINESS DETAILS -->
                                    <div class="container p-lg-5 p-md-5 p-sm-0 rounded mb-5" style='background:white;'>
                                        <div class="row mb-3">
                                            <div class="col">
                                                <div class='d-flex flex-row'>
                                                    <div class='flex-fill'>
                                                        <h4>Business Details</h4>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="row border-top">
                                            <div class="col">
                                                <div class="container px-0">

                                                    <div class="row my-5">
                                                        <div class="col-lg-3 col-md-4">
                                                            Owner
                                                        </div>
                                                        <div class="col">
                                                            <?= ucwords(strtolower($fname)) . " " . ucwords(strtolower($lname)) ?>
                                                        </div>
                                                    </div>  

                                                    <div class="row mb-5">
                                                        <div class="col-lg-3 col-md-4">
                                                            Address
                                                        </div>
                                                        <div class="col" id='store_profile_address'>
                                                            <?= $storeAddress ?>
                                                        </div>
                                                    </div>

                                                    <div class="row">
                                                        <div class="col-lg-3 col-md-4">
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
                                    <div class="container p-lg-5 p-md-5 p-sm-0 rounded mb-5" style='background:white;'>
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
                                                        <div class="col-lg-3 col-md-4 col-sm-5 pt-5">
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
                                                        <div class="col-lg-3 col-md-4 col-sm-6">
                                                            Followers
                                                        </div>
                                                        <div class="col">
                                                            <?= $storeFollowers ?>
                                                        </div>
                                                    </div>

                                                    <div class="row">
                                                        <div class="col-lg-3 col-md-4 col-sm-6">
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
                                    <div class="container p-lg-5 p-md-5 p-sm-0 rounded mb-5" style='background:white;'>
                                        <div class="row mb-3">
                                            <div class="col">
                                                <h4>Shipping Rates</h4>
                                            </div>
                                        </div>
                                        
                                        <div class="row border-top">
                                            <div class="col">
                                                <div class="container px-0">

                                                    <div class="row mt-5">
                                                        <div class="col-lg-3 col-md-4 col-sm-5">
                                                            Standard 
                                                        </div>
                                                        <div class="col">
                                                            <span>&#36;&nbsp;</span>
                                                            <span class='store_profile_standard_fee'><?= $storeShippingFee ?></span>
                                                        </div>
                                                    </div>  
                                                    
                                                    <?php if($storeFreeShippingMinimum) { ?>
                                                    <div class="row mt-5">
                                                        <div class="col-lg-3 col-md-4 col-sm-5">
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
                        <div class='container p-lg-5 p-md-5 p-sm-0 rounded mb-5' style='background:white;'>
                            <div class="row mx-0">
                                <div class="col-lg-6 col-md-6 col-sm-12">
                                    <h4>Search <span class='vanish-sm'>For</span> Products</h4>
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
                            
                                <div class="col-lg-3 col-md-4 col-sm-12 p-2">
                                   
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
                                
                                </div>
                                
                                    
                                <?php } } ?>

                            </div>
                        </div>


                    </div>
                    <!-- /MAIN BAR -->


                </div>
                <!-- /.ROW -->
            </div>

        </div>
    </div>
</div>

<script>
    var utcDateTime3 = $('.memberSince');
	var tz2 = moment.tz.guess();
	$.each(utcDateTime3, function(i, element){
		var dateTimeStr3 = $(element).text().trim();		
		$(element).text(moment(dateTimeStr3).tz(tz2).format("ll"));
    });
    
    var utcDateTime = $('#lastLoginTimeAgo').text();
	var agoTime = moment.utc(utcDateTime).fromNow();
	if(agoTime == "a few seconds ago") {
		$('#lastLoginTimeAgo').html("<small><i class='fas fa-circle text-success'>&nbsp;</i></small>Active Now");
	  } else {
		$('#lastLoginTimeAgo').html("Active " + agoTime);
	  }
</script>