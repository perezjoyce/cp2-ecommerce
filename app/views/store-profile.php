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
        $id = isset($_SESSION['id']) ? $_SESSION['id'] : null;
        try {
            $currentUser = getUser($conn, $id);
            $isSeller = $currentUser['isSeller'] == "yes" ? 1 : 0;    
        } catch (\Exception $e) {
            $currentUser = null;
            $isSeller = null;
        }
    }  

    if ($isSeller && $currentUser && $currentUser['id'] == $storeInfo['user_id']) {
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
                                    <div class="d-flex align-items-center">
                                        <div class='pr-3'>
                                            <img src='<?= BASE_URL . "/" . $storeLogo . ".jpg" ?>' class='rounded-circle store_page_logo'>
                                        </div>
                                        <div class="d-flex flex-column">
                                            <div>
                                                <h3><?= $storeName ?></h3>
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
                                                <?php } ?>
                                            </div>
                                        <?php } ?>
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
                                       <div class="mt-5" id='store_profile_description'>
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
                                                <div class="col-3">
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
                                                <div class="col-3">
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
                                                <h4>Shoperoo Stats</h4>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="row border-top">
                                    <div class="col">
                                        <div class="container px-0">

                                            <div class="row my-4">
                                                <div class="col-3 pt-5">
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
                                                <div class="col-3">
                                                    Followers
                                                </div>
                                                <div class="col">
                                                    <?= $storeFollowers ?>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-3">
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
                                                <div class="col-3">
                                                    Standard 
                                                </div>
                                                <div class="col">
                                                    <span>&#8369;&nbsp;</span>
                                                    <span id='store_profile_standard_fee'><?= $storeShippingFee ?></span>
                                                </div>
                                            </div>  
                                            
                                            <?php if($storeFreeShippingMinimum) { ?>
                                            <div class="row mt-5">
                                                <div class="col-3">
                                                    Free
                                                </div>
                                                <div class="col">
                                                    <span>&nbsp;&#8369;</span>
                                                    <span id='store_profile_free_shipping'><?= $storeFreeShippingMinimum ?></span>
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
                        <div class="col-6">
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
                                $item_img = $row2['img_path'];
                        ?>
                    
                        <div class="col-lg-3 col-md-4 col-sm-6 p-2">
                            <a href="product.php?id=<?= $id ?>">
                            <div class='card border-0'>
                                <a href="product.php?id=<?= $row2['id'] ?>">
                                <img class='card-img-top' src="<?= $item_img ?>">

                                <div class="card-body pr-0">
                                    <div>
                                    <?= $name ?>
                                    </div>
                                    <div>&#8369; 
                                    <?= $price ?> 
                                    </div>

                                    <div class='d-flex flex-row mt-3'>
                                    
                                    <!-- WISHLIST BUTTONS -->
                                    <div class='flex-fill' style='cursor:default;'>

                                        <?php if(checkIfInWishlist($conn,$id) == 1 ) { ?>

                                        <i class='fas fa-heart text-red'></i> 
                                        <span class='text-gray product-wish-count<?= $id ?>'>
                                            <small><?= getProductWishlishtCount($conn, $id) ?></small>
                                        </span>

                                        <?php } else { 
                                        
                                        if(getProductWishlishtCount($conn, $id) == 0) { ?>

                                        <i class='far fa-heart text-gray'></i> 
                                        <span class='text-gray product-wish-count<?= $id ?>'>
                                            <small><?= getProductWishlishtCount($conn, $id) ?></small>
                                        </span>

                                        <?php } else { ?>

                                        <i class='far fa-heart text-red'></i> 
                                        <span class='text-gray product-wish-count<?= $id ?>'>
                                            <small><?= getProductWishlishtCount($conn, $id) ?></small>
                                        </span>

                                        <?php   } }  ?>
                                    </div>
                                            
                                    

                                    <!-- AVERAGE STAR RATING -->
                                    <div class='flex-fill' style="display:flex; flex-direction: column; width:81%; align-items:flex-end">  
                                        <div class='stars-outer' 
                                        data-productrating='<?=getAveProductReview($conn, $id)?>' 
                                        data-productid='<?=$id?>' 
                                        id='average_product_stars2<?=$id?>'>
                                        <span class='stars-inner'></span>
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
    <?php if(isset($_SESSION['id']) && !$isSeller){ include '../partials/message_box.php'; } ?>

<?php require_once "../partials/footer.php";?>
<?php require_once "../partials/modal_container.php";?>
<?php require_once "../partials/modal_container_big.php"; ?>

  