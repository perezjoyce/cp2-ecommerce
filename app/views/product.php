<?php require_once "../partials/header.php";?>
<?php require_once "../controllers/connect.php";?>
<?php require_once "../controllers/functions.php";?>
<?php include_once "../partials/categories.php"; ?>


<?php 
$id = $_GET['id'];

if(isset($_SESSION['id'])) {
  $userId = $_SESSION['id'];
}

?>

  <!-- BREADCRUMBS -->
  <div class="container">
    <div class="row my-4">
      <div class="col-12">
        <?php 
          @$origin = $_SERVER['HTTP_REFERER'];
          displayBreadcrumbs($conn, $id, $origin); 
        
        ?>
      </div>
    </div>
  </div>

  <!-- PRODUCT PAGE MAIN CONTAINER -->
  <div class="container">

    <!-- FIRST ROW -->
    <div class="row">
        
      <!-- FIRST COLUMN (PICS) -->
      <div class="col-lg-4 col-md-5 col-sm-12 mb-4 mb-4">
        <input type="hidden" id='iframeId'>
        <!-- IFRAME -->
        <div class="row mb-3 no-gutters">
          <?php 
            $sql = "SELECT * FROM tbl_product_images WHERE product_id = ?";
            $statement = $conn->prepare($sql);
            $statement->execute([$id]);	
            $row = $statement->fetch();
            $url = $row['url'];
            $img_id =$row['id'];
          ?>

          <div class="col position-relative" id="product_iframe">
            <img src='<?=$url?>' style='width:100%;height:50vh;' iframe_img_id='<?= $img_id ?>'>
          </div>
        </div>

        <!-- THUMBNAILS -->
        <div class="d-flex flex-row" style="overflow-x:scroll;">
          <?php 
            $sql = "SELECT * FROM tbl_product_images WHERE product_id = ?";
            $statement = $conn->prepare($sql);
            $statement->execute([$id]);	
            while($row = $statement->fetch()):
              $url = $row['url'];
              $img_id =$row['id'];

          ?>
          <div class="col-2 px-0 mr-1">
            <div class="card" style="border:none;">
              <img src='<?=$url?>' style='width:50px;max-height:50px;cursor:pointer;' class='product_thumbnail' data-id='<?=$img_id?>' data-url='<?=$url?>'>
            </div>
          </div>

          <?php endwhile;?>
        </div>
      </div>
      <!-- /FIRST COLUMN (PICS) -->


      <!-- SECOND COLUMN (PRODUCT DETAILS) -->
      <div class="col-lg-6 col-md-7 col-sm-12 mb-5">
          <?php
        
            $sql = "SELECT * FROM tbl_items WHERE id = ?";
            $statement = $conn->prepare($sql);
            $statement->execute([$id]);
            $row = $statement->fetch();
    
            $name = $row['name'];     
            $price = $row['price'];
            $description = $row['description'];
            $item_img = $row['img_path'];
            $categoryId = $row['category_id'];
            $brandId = $row['brand_id'];
            $stocks = $row['stocks'];
            $storeId = $row['store_id'];

        ?>

        <!-- PRODUCT NAME -->
        <div class="row pl-4 mb-2">
          <h1><?= ucwords(strtolower($name)); ?></h1>
        </div>    
          
        <!-- PRODUCT RATING -->
        <div class="row pl-4 mb-4">
          <?
          $sql = "SELECT AVG(product_rating) as averageProductRating FROM tbl_ratings WHERE product_id = ?";
          $statement = $conn->prepare($sql);
          $statement->execute([$id]);
          $row = $statement->fetch();
          $averageRating = $row['averageProductRating'];
          $aveRating = ($averageRating/5)*100;
          $aveRating = round($aveRating, 1);
          ?>

          <!-- FOR DEBUGGING PURPOSES -->
          <input type="hidden" value='<?= $aveRating ?>' id='average_product_rating'>
          <input type="hidden" value='<?= $aveRating ?>' id='average_product_rating_big'>
          <!-- AVE PRODUCT RATING AS STARS -->
          <!-- NUMBER OF REVIEWS -->
          <span id='average_product_stars'>
          </span>
          <span>&nbsp;&nbsp;</span>
      
            <?php 

          $totalProductRating = countRatingsPerProduct($conn, $id);

          if (isset($_SESSION['cart_session'])) { 
            if ($totalProductRating === 0 || $totalProductRating == "") { ?>
          <span class='rating-count<?=$id?>'>
            <!-- no value -->
          </span>
          <span class='rating-word'>
            <?= "&nbsp;No reviews yet" ?>
          </span>
              <?php } elseif($totalProductRating == 1){?>
          <span class='rating-count<?=$id?>'>
            <?= $totalProductRating ?>
          </span>
          <span class='rating-word'>
            <?= "&nbsp;Review" ?>
          </span>
              <?php } else {?>
          <span class='rating-count<?=$id?>'>
            <?= $totalProductRating ?>
          </span>
          <span class='rating-word'>
            <?= "&nbsp;Reviews" ?>
          </span>
              <?php } } ?>
            
        </div>
        

        <!-- PRICE -->
        <div class="row pl-4 mb-lg-5 mb-4">
          <h1 class='font-weight-bold text-purple'>&#8369;&nbsp;<?= $price?></h1>
        </div>

        <!-- SHIPPING FEE -->
        <!-- TO BE ADDED ONCE I MAKE SELLER PAGE -->
        <!-- <div class="row mb-4 border">
          <div class="col-3 border">
            <div>Shipping Fee</div>
          </div>
          <div class="col">

          </div>
        </div> -->

       

        <!-- BRAND -->
        <div class="row mb-5">
          <div class="col-3">
            <div>Brand</div>
          </div>
          <div class="col">
            <div class="row">
              <?php
                $sql = "SELECT * FROM tbl_brands WHERE id = ?";
                $statement = $conn->prepare($sql);
                $statement->execute([$brandId]);
                $row = $statement->fetch();
                $brandName = $row['brand_name'];
              ?>
              
              <span><?=$brandName?></span>
            </div>
          </div>
        </div>

        <!-- VARIATION -->
        <div class="row mb-5">
          <div class="col-3">
            <div class='pt-3'>Variation</div>
          </div>
          <div class="col">
            <div class="row">
              <?php
              
                $sql = "SELECT * FROM tbl_variations WHERE product_id = ?";
                $statement = $conn->prepare($sql);
                $statement->execute([$id]);
                if ($statement->rowCount()){
                  while($row = $statement->fetch()){
                    $variationId = $row['id'];
                    $variationName = $row['variation_name'];
                    $variationStock = $row['variation_stock'];
              ?>
              
              <button class="btn p-2 mr-2 mb-2 text-center border btn_variation text-responsive" style='width:18%;' data-variationStock='<?= $variationStock ?>'>
                [&nbsp;<?= ucwords($variationName) ?>&nbsp;]
              </button>

              <?php } } ?>
              <!-- HIDDEN FOR DEBUGGING PURPOSES -->
              <input type="hidden" id='variation_stock_hidden' val='<?$variationStock?>'>
            </div>
          </div>
        </div>
        
        <!-- QUANTITY --> 
        <div class="row mb-5">
          <?
            $sql = "SELECT SUM(variation_stock) as 'totalStocksAvailable'  FROM tbl_variations WHERE product_id = ?";
            $statement = $conn->prepare($sql);
            $statement->execute([$id]);
            $row = $statement->fetch();
            $totalStocksAvailable = $row['totalStocksAvailable'];

          ?>
          <div class="col-3">
            <div class='pt-2'>Quantity</div>
          </div>
          <div class="col pl-0">
            <div class="d-flex flex-row">
              <!-- INPUT FIELD -->
              <span class='flex-fill'>
                <div class="input-group">
                  <div class="input-group-prepend">
                    <button class="btn border btn_minus" type="button">&#8212;</button>
                  </div>
                  <input class='itemQuantity text-center' id ='variation_quantity'
                    type="number" 
                    style='width:35%;' 
                    value="1"
                    data-productid="<?= $id ?>"
                    min="1" 
                    max="<?= $totalStocksAvailable ?>" >
                  <div class="input-group-append">
                    <button class="btn border btn_plus" type="button">&#65291;</button>
                  </div>
                </div>
              </span>
              
              <div class='flex-grow-1 pt-2'>
                <!-- STOCK AVAILABILITY -->
                <span class='variation_display' id='variation_stock'>
                  <?= $totalStocksAvailable ?>
                </span>
                
                <!-- GRAMMAR -->
                  <?php
                    if($totalStocksAvailable == 1) {
                  ?>
                <span class='variation_display'>&nbsp;piece available</span>
                  
                  <?php }else{ ?>
            
                <span class='variation_display'>&nbsp;pieces available</span>
                <?php } ?>
              </div>
            </div>
          </div>
        </div>
        <br>
        <!-- BUTTONS -->
        <div class="d-flex flex-row mb-5">
          <?php
            $sql = " SELECT * FROM tbl_carts WHERE cart_session=? AND item_id=?";
            //$result = mysqli_query($conn, $sql);
            $statement = $conn->prepare($sql);
            $statement->execute([$cartSession, $id]);
            $count = $statement->rowCount();
          ?>

          <?php 
            if(isset($_SESSION['id'])) {
                if (checkIfInWishlist($conn,$id) == 0) {
          ?>
          <a class='btn btn-lg btn-gray py-3' style="width:50%;" data-id='<?= $id ?>' role='button' id="btn_add_to_wishlist">
            <i class="far fa-heart"></i>
            &nbsp;Wish List
          </a>

          <!-- ADD TO WISHLIST BUTTON -->
          <?php  } else { ?>

          <button class='btn btn-lg btn-gray py-3' style="width:50%;" data-id='<?= $id ?>' disabled id="btn_already_in_wishlist">
            <i class='far fa-heart'></i> 
            &nbsp;Item is in wishlist 
          </button>

          <?php }  } else { ?>
            <!-- WISHLIST BUTTON NOT AVAILABLE FOR LOGGED OUT AND UNREGISTERED USERS -->
          <?php }  ?>

          <?php
          
            if($count) {
          ?>
          <button class='btn btn-lg btn-purple py-3 ml-2' style="width:50%;" data-id='<?= $id ?>' role='button' id="btn_delete_from_cart" disabled>
            <i class='fas fa-shopping-bag'></i>
              &nbsp;Item is in bag
          </button>
          <?php } else { ?>
          <a class='btn btn-lg btn-purple py-3 ml-2' style="width:50%;" data-id='<?= $id ?>' role='button' id="btn_add_to_cart">
            <i class='fas fa-shopping-bag'></i>  
            &nbsp;Add to Shopping Bag
          </a>
          <?php }?>


        </div>

      </div>
      <!-- /PRODUCT DETAILS -->

      
      <!-- THIRD COLUMN (SELLER DETAILS) -->
      <div class="col-lg-2 col-md-3 col-sm-12 px-5 mb-5">

        <div class='row mb-4 py-4 border'>
            <div class="col-12">
              
                  <?php
                    $sql = "SELECT * FROM tbl_stores WHERE id = ?";
                    $statement = $conn->prepare($sql);
                    $statement->execute([$storeId]);	
                    $row = $statement->fetch();
                    $storeId = $row['id'];
                    $storeName = $row['name'];
                    $storeLogo = $row['logo'];
                    $storeAddress = $row['store_address'];
                    $sellerId = $row['user_id'];
                  ?>
                
                <!-- STORE LOG -->
                <div class="row justify-content-center mb-3">
                  <img src="<?=$storeLogo?>" alt="<?=$storeName?>" style='width:80px;max-height:80px;' class='circle'>
                </div>

                <!-- STORE NAME -->
                <div class="row justify-content-center text-purple font-weight-bold mb-3">
                  <?=$storeName?>
                </div>

                <!-- STORE ADDRESS -->
                <div class="row justify-content-center text-gray mb-2">
                  <small>
                    <i class="fas fa-map-marker-alt"></i>
                    &nbsp;<?= ucwords(strtolower($storeAddress)) ?>
                  </small>
                </div>


                <!-- LAST ACTIVITY -->
                <div class="row justify-content-center text-gray mb-2">
                  <?
                    $sql = "SELECT last_login FROM tbl_users WHERE id = ?";
                    $statement = $conn->prepare($sql);
                    $statement->execute([$sellerId]);	
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

                <!-- SELLER NAME -->
                <!-- // $sql = "SELECT * FROM tbl_users WHERE id = ?";
                    // $statement = $conn->prepare($sql);
                    // $statement->execute([$sellerId]);	
                    // $row = $statement->fetch();
                    // $fname = $row['first_name'];
                    // $fname = ucwords(strtolower($fname));
                    // $lname = $row['last_name'];
                    // $lname = ucwords(strtolower($lname));

                  // -->
                  

                <hr class='my-4'>

                <!-- STATS -->
                <div class="row text-gray">
                  <div class="col">

                    <!-- RATINGS -->
                    <small class="d-flex flex-row mb-3">
                        <?php
                        $sql = "SELECT i.id, i.store_id, AVG(product_rating) as 'averageRating' FROM tbl_ratings LEFT JOIN tbl_items i ON product_id = i.id WHERE store_id = ?";
                          $statement = $conn->prepare($sql);
                          $statement->execute([$storeId]);
                          $row = $statement->fetch();
                          $averageSellerRating = $row['averageRating'];	
                          $averageSellerRating = round($averageSellerRating,1);
                        ?>
                      <div style='width:45%;'>Rating</div>
                      <div>
                        <?= $averageSellerRating ?> out of 5
                      </div>
                    </small>

                    <!-- FOLLWERS -->
                    <small class="d-flex flex-row mb-3">
                      <div style='width:45%;'>Followers</div>
                      <div>fds</div>
                    </small>

                    <!-- PRODUCTS COUNT -->
                    <small class="d-flex flex-row mb-3">
                        <?php
                          $sql = "SELECT COUNT(*) AS 'productCount' FROM tbl_items WHERE store_id = ?";
                          $statement = $conn->prepare($sql);
                          $statement->execute([$storeId]);
                          $row = $statement->fetch();
                          $productCount = $row['productCount'];	
                        ?>
                      <div style='width:45%;'>Products</div>
                      <div>
                        <?= $productCount ?>
                      </div>
                    </small>

                    <!-- JOINED -->
                    <small class="d-flex flex-row mb-4">
                        <?php
                          $sql = "SELECT DATE_FORMAT(date_created, '%M %d, %Y') AS 'dateJoined' FROM tbl_stores WHERE id = ?";
                          $statement = $conn->prepare($sql);
                          $statement->execute([$storeId]);
                          $row = $statement->fetch();
                          $dateJoined = $row['dateJoined'];	
                          $month = substr($dateJoined,0,3);
                          $daysYear = substr(strstr($dateJoined," "), 1);
                          $dateJoined = $month." ".$daysYear
                        ?>
                      <div style='width:45%;'>Joined</div>
                      <div>
                        <?=  $dateJoined ?>
                      </div>
                      <!-- https://stackoverflow.com/questions/6823133/how-to-remove-first-word-from-a-php-string -->
                    </small>

                  </div> 
                </div>

              
                <!-- BUTTONS -->
                <div class="row">
                  <a href='#' class='btn btn-block border text-purple mx-3 py-2 modal-link' id="chatbox" data-url="../partials/templates/chatbox_modal.php">
                    <i class="far fa-comment"></i>
                    &nbsp;Chat Seller
                  </a>

                  <a href='store.php?id=<?=$storeId?>' class='btn btn-block border text-secondary mx-3 py-2'>
                    <i class="fas fa-store"></i>
                    &nbsp;View Shop
                  </a>
                </div>
              
            </div>
        

        </div>

        <div class='row border'>
        
            <a href='#' class='btn btn-block text-gray mx-3 py-2'>
              <i class="far fa-flag"></i>
                &nbsp;Report
              </a>
      
        </div>

      
      </div>
      <!-- /SELLER DETAILS -->
    

      <!-- NAV TABS FOR  -->
      <div class="col-lg-12 col-md-9 col-sm-12 mt-3 mb-5">
        <div class="row">
          <div class="col-lg-12">
            
            <!-- TABS -->
            <div class="row mb-4">
              <div class="col-12">
                <div class="tab border-bottom d-flex flex-row">
                  <button class="tablinks flex-fill active" onclick="openTab(event, 'info_content')">
                    <h3>Product Info</h3>
                  </button>
                  <button class="tablinks flex-fill progress-x" onclick="openTab(event, 'reviews_content')">
                    <h3>Reviews</h3>
                  </button>
                  <button class="tablinks flex-fill" onclick="openTab(event, 'questions_content')">
                    <h3>Q&As</h3>
                  </button>
                </div>
              </div>
            </div>

            <!-- CONTENT -->
            <div class='row'>
              <div class='col-12'>

                <div id="info_content" class="tabcontent" style='display:block'>
                  <h3>London</h3>
                  <p>London is the capital city of England.</p>
                </div>

                <div id="reviews_content" class="tabcontent">
                  <div class="container">

                    <!-- DIAGRAMS -->
                    <div class="row mb-5">
                      
                      <div class="col-lg-2"></div>
                       <!-- RATING STARS -->
                      <div class="col-lg-2 col-md-3 col-sm-12">    
                        <div class="row pt-2">
                          <div class="col-12 d-flex flex-column">
                            <div class="row">
                              
                                <h1 style='font-size:50px'>
                                  <?=number_format((float)$averageRating, 1, '.', '')?>
                                </h1>
                                <h3 class='text-gray pt-5'>&nbsp;/&nbsp;5</h3>
                             
                            </div>
                            <div class="row">
                              <div id='average_product_stars_big' class='pb-4'></div>
                            </div>
                            <div class='row text-gray'>
                              <?php 
                               

                                if (isset($_SESSION['cart_session'])) { 
                                  if ($totalProductRating === 0 || $totalProductRating == "") { ?>
                                <span class='rating-count<?=$id?>'>
                                  <!-- no value -->
                                </span>
                                <span class='rating-word'>
                                  <?= "&nbsp;No reviews yet" ?>
                                </span>
                                    <?php } elseif($totalProductRating == 1){?>
                                <span class='rating-count<?=$id?>'>
                                  <?= $totalProductRating ?>
                                </span>
                                <span class='rating-word'>
                                  <?= "&nbsp;Review" ?>
                                </span>
                                    <?php } else {?>
                                <span class='rating-count<?=$id?>'>
                                  <?= $totalProductRating ?>
                                </span>
                                <span class='rating-word'>
                                  <?= "&nbsp;Reviews" ?>
                                </span>
                              <?php } } ?>
                            </div>
                          </div>
                        </div>
                      </div>

            
                      <!-- RATINGS BARS -->
                      <div class="col-lg-5 col-md-9 col-sm-12 text-gray">

                        <?php 

                          if($totalProductRating > 0) {

                            $sql = "SELECT COUNT(*) as 'fiveStarRating' FROM tbl_ratings WHERE product_rating = 5 AND product_id = ?";
                            $statement = $conn->prepare($sql);
                            $statement->execute([$id]);
                            $row = $statement->fetch();
                            $fiveStarRatingCount = $row['fiveStarRating'];
                            $fiveStarRatingCount = (int)$fiveStarRatingCount;
                            $fiveStarRatingBar = ($fiveStarRatingCount/$totalProductRating)*100;

                            $sql = "SELECT COUNT(*) as 'fourStarRating' FROM tbl_ratings WHERE product_rating = 4 AND product_id = ?";
                            $statement = $conn->prepare($sql);
                            $statement->execute([$id]);
                            $row = $statement->fetch();
                            $fourStarRatingCount = $row['fourStarRating'];
                            $fourStarRatingCount = (int)$fourStarRatingCount;
                            $fourStarRatingBar = ($fourStarRatingCount/$totalProductRating)*100;

                            $sql = "SELECT COUNT(*) as 'threeStarRating' FROM tbl_ratings WHERE product_rating = 3 AND product_id = ?";
                            $statement = $conn->prepare($sql);
                            $statement->execute([$id]);
                            $row = $statement->fetch();
                            $threeStarRatingCount = $row['threeStarRating'];
                            $threeStarRatingCount = (int)$threeStarRatingCount;
                            $threeStarRatingBar = ($threeStarRatingCount/$totalProductRating)*100;

                            $sql = "SELECT COUNT(*) as 'twoStarRating' FROM tbl_ratings WHERE product_rating = 2 AND product_id = ?";
                            $statement = $conn->prepare($sql);
                            $statement->execute([$id]);
                            $row = $statement->fetch();
                            $twoStarRatingCount = $row['twoStarRating'];
                            $twoStarRatingCount = (int)$twoStarRatingCount;
                            $twoStarRatingBar = ($twoStarRatingCount/$totalProductRating)*100;

                            $sql = "SELECT COUNT(*) as 'oneStarRating' FROM tbl_ratings WHERE product_rating = 1 AND product_id = ?";
                            $statement = $conn->prepare($sql);
                            $statement->execute([$id]);
                            $row = $statement->fetch();
                            $oneStarRatingCount = $row['oneStarRating'];
                            $oneStarRatingCount = (int)$oneStarRatingCount;
                            $oneStarRatingBar = ($oneStarRatingCount/$totalProductRating)*100;
                          
                          // var_dump($oneStarRatingBar);die();
                        ?>

                        
                        <input type="hidden" id='rating_bar5_hidden' value='<?= $fiveStarRatingBar ?>'>
                        <input type="hidden" id='rating_bar4_hidden' value='<?= $fourStarRatingBar ?>'>
                        <input type="hidden" id='rating_bar3_hidden' value='<?= $threeStarRatingBar ?>'>
                        <input type="hidden" id='rating_bar2_hidden' value='<?= $twoStarRatingBar ?>'>
                        <input type="hidden" id='rating_bar1_hidden' value='<?= $oneStarRatingBar ?>'>
                                                

                        <div class="row pt-3 no-gutters">
                          <div class="col-2 text-center">
                            <span>5&nbsp;</span>
                            <span class='star'>★</span>
                          </div>
                          <div class="col-8">
                            <div class="product_rating_bar">
                              <div id="rating_bar5" class='rating_bar'></div>
                            </div>
                          </div>
                          <div class="col-2 text-center pt-1">
                            <span>
                              <?php 
                              if($fiveStarRatingCount == null) {
                                echo "0";
                              } else {
                                echo $fiveStarRatingCount;
                              }
                              ?>
                            </span>
                          </div>
                        </div>

                        <div class="row pt-2 no-gutters">
                          <div class="col-2 text-center">
                            <span>4&nbsp;</span>
                            <span class='star'>★</span>
                          </div>
                          <div class="col-8">
                            <div class="product_rating_bar">
                              <div id="rating_bar4" class='rating_bar'></div>
                            </div>
                          </div>
                          <div class="col-2 text-center pt-1">
                            <span>
                              <?php 
                              if($fourStarRatingCount == null) {
                                echo "0";
                              } else {
                                echo $fourStarRatingCount;
                              }
                              ?>
                            </span>
                          </div>
                        </div>

                        <div class="row pt-2 no-gutters">
                          <div class="col-2 text-center">
                            <span>3&nbsp;</span>
                            <span class='star'>★</span>
                          </div>
                          <div class="col-8">
                            <div class="product_rating_bar">
                              <div id="rating_bar3" class='rating_bar'></div>
                            </div>
                          </div>
                          <div class="col-2 text-center pt-1">
                            <span>
                              <?php 
                              if($threeStarRatingCount == null) {
                                echo "0";
                              } else {
                                echo $threeStarRatingCount;
                              }
                              ?>
                            </span>
                          </div>
                        </div>

                        <div class="row pt-2 no-gutters">
                          <div class="col-2 text-center">
                            <span>2&nbsp;</span>
                            <span class='star'>★</span>
                          </div>
                          <div class="col-8">
                            <div class="product_rating_bar">
                              <div id="rating_bar2" class='rating_bar'></div>
                            </div>
                          </div>
                          <div class="col-2 text-center pt-1">
                            <span>
                              <?php 
                              if($twoStarRatingCount == null) {
                                echo "0";
                              } else {
                                echo $twoStarRatingCount;
                              }
                              ?>
                            </span>
                          </div>
                        </div>


                        <div class="row pt-2 no-gutters">
                          <div class="col-2 text-center">
                            <span>1&nbsp;</span>
                            <span class='star'>★</span>
                          </div>
                          <div class="col-8">
                            <div class="product_rating_bar">
                              <div id="rating_bar1" class='rating_bar'></div>
                            </div>
                          </div>
                          <div class="col-2 text-center pt-1">
                            <span>
                              <?php 
                              if($oneStarRatingCount == null) {
                                echo "0";
                              } else {
                                echo $oneStarRatingCount;
                              }
                              ?>
                            </span>
                          </div>
                        </div>
                      
                        <?php }?>
                          
                      </div>

                      
                      <div class="col-lg-3"></div>
                      
                    
                    </div>

                    <!-- FUNNEL -->
                    <?php 
                       if($totalProductRating > 0) {
                    ?>
                    <div class="row">
                      <div class="col-10 text-left">
                        <h3 class='py-1'>Comments</h3>
                      </div>
                      <div class="col text-right">
                        <div>
                          <div class="flex-fill input-group" id='funnel_dropdown'>
                            <div class="input-group-prepend pt-2 px-2">
                              <i class="fas fa-filter"></i>
                              &nbsp;Filter:
                            </div>
                            <select class="custom-select border-0" id="sort_ratings" onchange="sort_ratings" data-id='<?= $id ?>' data-storeid='<?=$storeId?>'>
                              <option value="6" selected="">All stars</option>
                              <option value="5"> 5 stars </option>
                              <option value="4"> 4 stars </option>
                              <option value="3"> 3 stars </option>
                              <option value="2"> 2 stars </option>
                              <option value="1"> 1 star </option>
                            </select>
                          </div> 
                        </div>
                      </div>
                    </div>

                    <!-- COMMENTS --> 
                    <div id='ratings_view'> 
                        <?php

                          $sql ="SELECT r.*, u.first_name, u.last_name FROM tbl_ratings r JOIN tbl_users u ON r.user_id = u.id WHERE product_id = ?";
                          $statement = $conn->prepare($sql);
                          $statement->execute([$id]);	

                          $count = $statement->rowCount();
                          
                          

                            while($row = $statement->fetch()) {

                          $ratingId = $row['id'];
                          $clientId = $row['user_id'];
                          $clientFname = $row['first_name'];
                          $clientFname = ucwords(strtolower($clientFname));
                          $clientLname = $row['last_name'];
                          $clientLname = ucwords(strtolower($clientLname ));
                          $clientRating = $row['product_rating'];
                          $clientProductReview = $row['product_review'];
                          $clientRatingDate = $row['date_given'];
                          $sellerResponse = $row['seller_response'];
                          $sellerResponseDate = $row['response_date'];

                        ?>
                       
                      <div class="row border-top px-4">

                      

                        <div class="col-12 pt-4">
                        
                          <!-- CLIENT RATING -->
                          <div class="row mb-1">
                        
                              <?php 
                                if($clientRating != null || $clientRating != "") {
                              ?>
                  
                              <div class='test-container' data-rating="<?= $clientRating ?>" data-id='<?= $clientId ?>'></div>
                            
                              <?php } else { ?>
                            
                            <div class="star-gray">★</div>
                            <div class="star-gray">★</div>
                            <div class="star-gray">★</div>
                            <div class="star-gray">★</div>
                            <div class="star-gray">★</div>
                          
                              <?php } ?>

                          </div>

                          <!-- CLIENT NAME -->
                          <div class='row text-gray mb-2'>
                            <span><?=$clientFname." ".$clientLname ?></span>
                          </div>

                          

                          <!-- VERFICATION BADGE AND DATE -->
                          <div class="row mb-4">
                            <img src="../assets/images/verified-gradient.png" alt="verified_user" style='height:10px;width:10px;'>
                            <small class='text-purple'>&nbsp;Verified Purchase&nbsp;|
                              <?= date("M d, Y", strtotime($clientRatingDate));  ?>
                            </small>
                          </div>

                          

                          <!-- REVIEW -->
                              <?php 
                                if($clientRating != null || $clientRating != "") {
                              ?>  
                          <div class="row mb-2">  
                            <p style='line-height:1.5em;'><?=$clientProductReview?></p>
                          </div>
                              <?php } else { echo ""; } ?>


                          <!-- REVIEW IMAGES -->  
                          <div class="row mb-2">
                            <div class="col-12">
                              <div class="row mb-2">
                              
                                  <?php 
                                    $sql = "SELECT * FROM tbl_rating_images WHERE rating_id =?";
                                    $statement2 = $conn->prepare($sql);
                                    $statement2->execute([$ratingId]);	
                                    $count2 = $statement2->rowCount();

                                    if($count2) {
                                  
                                    while($row2 = $statement2->fetch()){
                                      $reviewImageId = $row2['id'];
                                      $reviewImageUrl = $row2['url'];
                                  ?>

                                <img src="<?=$reviewImageUrl?>" 
                                    alt="review_image" style='width:50px;max-height:50px;cursor: zoom-in;' 
                                    class='review_thumbnail mr-2' 
                                    data-id='<?=$reviewImageId?>' data-clientid='<?=$clientId?>'
                                    data-url='<?=$reviewImageUrl?>'>

                                  <?php } } ?>
                              
                              </div>
                              <div class="row">
                                <div class="col-lg-6 col-md-12 col-sm-12 px-0">
                                  <div id='review_iframe<?=$clientId?>'></div>
                                </div>   
                              </div>
                            </div>
                          </div>
                            
                      
                                  
                          <!-- SELLER RESPONSE -->
                              <?php
                                if($sellerResponse) {
                              ?>
                          <div class="row my-4">
                            <div class="col-1"></div>
                            <div class="col mb-2 pt-4 px-5 seller_response_container" style='background:#eff0f5'>
                              <!-- SELLER DETAILS -->
                              <div class="row flex-row text-gray mb-4"> 
                                <a href="store.php?id=<?=$storeId ?>"></a>
                                <img src="<?=$storeLogo?>" alt="<?=$storeName?>" style='width:30px;max-height:30px;' class='circle'>
                                <div>
                                  <div>&nbsp;<?=$storeName?></div>
                                  <small class='text-purple'>
                                    &nbsp;
                                    <?= date("M d, Y", strtotime($sellerResponseDate));  ?>
                                  </small>
                                </div>
                              </div>
                              <div class="row">
                                <p style='line-height:1.5em;'><?=$sellerResponse?></p>
                              </div>

                            </div>
                          </div>
                              <?php } else { echo ""; } ?>


                        </div>
                      </div>
                      <!-- ADJUSTING FIXING THIS -->
                      <?php } ?>
                    </div>
                    

                  </div>
                  <!-- /CONTAINER -->
                  <?php } ?>
                </div>

                <div id="questions_content" class="tabcontent">
                  <h3>Tokyo</h3>
                  <p>Tokyo is the capital of Japan.</p>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <!-- /END OF FIRST ROW -->
  </div>

    <!-- THIRD ROW APPEARS DIFFERENTLY DEPENDING ON NO OF PRODUCTS -->
    <?php 

      $sql = "SELECT * FROM tbl_items WHERE id != ? AND category_id = ? LIMIT 12 ";
      $statement = $conn->prepare($sql);
      $statement->execute([$id,$categoryId]);
    
      if($statement->rowCount() >= 6){
    ?>
  <!-- RELATED PRODUCTS -->
  <div class="container mb-5">  
      <!-- LABEL -->
      <div class="row">
        <div class="col-6">
            <h2 class='pl-3'>
            &nbsp;RELATED PRODUCTS
            </h2>
        </div>
      </div>
      <!-- CARDS -->
      <div class="row no-gutters autoplay justify-content-left">

        <?php
            while($row = $statement->fetch()){
              $id = $row['id'];
              $name = $row['name'];
              $price = $row['price'];
              $description = $row['description'];
              $item_img = $row['img_path'];
        ?>

        <div class="col-lg-2 col-md-3">
          <a href="product.php?id=<?= $id ?>">
            <div class='card h-700 border-0'>
              <a href="product.php?id=<?= $row['id'] ?>">
                <img class='card-img-top' src="<?= $item_img ?>">

                <div class="card-body">

                  <div>
                    <?= $name ?>
                  </div>
                  <div>&#8369; 
                    <?= $price ?> 
                  </div>

                  <div class='d-flex flex-row mt-3'>

                    <!-- WISHLIST BUTTONS -->
                    <div class='flex-fill'>
                      <?php 
                        if(isset($_SESSION['id'])) {
                            if (checkIfInWishlist($conn,$id) == 0) {
                      ?>
                        <a class='mt-3 btn_add_to_wishlist_view' data-id='<?= $id ?>' role='button'>
                          <i class='far fa-heart' style="color:red"></i> 
                          <span class='product-wish-count<?= $id ?>'>
                            <?= getProductWishlishtCount($conn, $id) == 0 
                            ? "" 
                            : getProductWishlishtCount($conn, $id) ?>
                          </span>
                        </a>
                  
                      <?php  } else { ?>

                        <a class='mt-3 btn_already_in_wishlist_view' data-id='<?= $id ?>' disabled>
                          <i class='fas fa-heart' style='color:red'></i> 
                          <span class='product-wish-count<?= $id ?>'>
                            <?= getProductWishlishtCount($conn, $id) == 0 
                            ? "" 
                            : getProductWishlishtCount($conn, $id) ?>
                          </span>
                        </a>

                      <?php }  } else { ?>
                        <!-- IF LOGGED OUT -->
                        <a class='mt-3 btn_wishlist_logout_view' data-id='<?= $id ?>' disabled>
                          <i class='far fa-heart' style='color:gray'></i> 
                          <span class='product-wish-count<?= $id ?>'>
                            <?= getProductWishlishtCount($conn, $id) == 0 
                            ? "" 
                            : getProductWishlishtCount($conn, $id) ?>
                          </span>
                        </a>

                      <?php }  ?>
                          
                    </div>
                    
                    <!-- AVERAGE STAR RATING -->
                    <div class='flex-fill text-right'>  
                      <!-- AVERAGE PRODUCT REVIEW (STARS) -->
                      <span class='rating-average-in-stars<?=$id?>'>
                        <?php 
                        if (isset($_SESSION['cart_session'])) {
                          if(getAveProductReview($conn, $id) >= 1 && getAveProductReview($conn, $id) < 1.5 ) { ?>
                            <i class='fas fa-star' id='star1' data-productId='<?= $id ?>' data-value='1'></i>
                            <i class='far fa-star' id='star2' data-productId='<?= $id ?>' data-value='2'></i>
                            <i class='far fa-star' id='star3' data-productId='<?= $id ?>' data-value='3'></i>
                            <i class='far fa-star' id='star4' data-productId='<?= $id ?>' data-value='4'></i>
                            <i class='far fa-star' id='star5' data-productId='<?= $id ?>' data-value='5'></i>
                        <?php } elseif (getAveProductReview($conn, $id) >= 1.5 && getAveProductReview($conn, $id) < 2 ) { ?>
                            <i class='fas fa-star' id='star1' data-productId='<?= $id ?>' data-value='1'></i>
                            <i class='fas fa-star-half-alt' id='star2' data-productId='<?= $id ?>' data-value='2'></i>
                            <i class='far fa-star' id='star3' data-productId='<?= $id ?>' data-value='3'></i>
                            <i class='far fa-star' id='star4' data-productId='<?= $id ?>' data-value='4'></i>
                            <i class='far fa-star' id='star5' data-productId='<?= $id ?>' data-value='5'></i>
                        <?php } elseif (getAveProductReview($conn, $id) >= 2 && getAveProductReview($conn, $id) < 2.5 ) { ?>
                            <i class='fas fa-star' id='star1' data-productId='<?= $id ?>' data-value='1'></i>
                            <i class='fas fa-star' id='star2' data-productId='<?= $id ?>' data-value='2'></i>
                            <i class='far fa-star' id='star3' data-productId='<?= $id ?>' data-value='3'></i>
                            <i class='far fa-star' id='star4' data-productId='<?= $id ?>' data-value='4'></i>
                            <i class='far fa-star' id='star5' data-productId='<?= $id ?>' data-value='5'></i>
                        <?php } elseif (getAveProductReview($conn, $id) >= 2.5 && getAveProductReview($conn, $id) < 3 ) { ?>
                            <i class='fas fa-star' id='star1' data-productId='<?= $id ?>' data-value='1'></i>
                            <i class='fas fa-star' id='star2' data-productId='<?= $id ?>' data-value='2'></i>
                            <i class='fas fa-star-half-alt' id='star3' data-productId='<?= $id ?>' data-value='3'></i>
                            <i class='far fa-star' id='star4' data-productId='<?= $id ?>' data-value='4'></i>
                            <i class='far fa-star' id='star5' data-productId='<?= $id ?>' data-value='5'></i>
                        <?php } elseif (getAveProductReview($conn, $id) >= 3 && getAveProductReview($conn, $id) < 3.5 ) { ?>
                            <i class='fas fa-star' id='star1' data-productId='<?= $id ?>' data-value='1'></i>
                            <i class='fas fa-star' id='star2' data-productId='<?= $id ?>' data-value='2'></i>
                            <i class='fas fa-star' id='star3' data-productId='<?= $id ?>' data-value='3'></i>
                            <i class='far fa-star' id='star4' data-productId='<?= $id ?>' data-value='4'></i>
                            <i class='far fa-star' id='star5' data-productId='<?= $id ?>' data-value='5'></i>
                        <?php } elseif (getAveProductReview($conn, $id) >= 3.5 && getAveProductReview($conn, $id) < 4 ) { ?>
                            <i class='fas fa-star' id='star1' data-productId='<?= $id ?>' data-value='1'></i>
                            <i class='fas fa-star' id='star2' data-productId='<?= $id ?>' data-value='2'></i>
                            <i class='fas fa-star' id='star3' data-productId='<?= $id ?>' data-value='3'></i>
                            <i class='fas fa-star-half-alt' id='star4' data-productId='<?= $id ?>' data-value='4'></i>
                            <i class='far fa-star' id='star5' data-productId='<?= $id ?>' data-value='5'></i>
                        <?php } elseif (getAveProductReview($conn, $id) >= 4 && getAveProductReview($conn, $id) < 4.5 ) { ?> 
                            <i class='fas fa-star' id='star1' data-productId='<?= $id ?>' data-value='1'></i>
                            <i class='fas fa-star' id='star2' data-productId='<?= $id ?>' data-value='2'></i>
                            <i class='fas fa-star' id='star3' data-productId='<?= $id ?>' data-value='3'></i>
                            <i class='fas fa-star' id='star4' data-productId='<?= $id ?>' data-value='4'></i>
                            <i class='far fa-star' id='star5' data-productId='<?= $id ?>' data-value='5'></i>
                        <?php } elseif (getAveProductReview($conn, $id) >= 4.5 && getAveProductReview($conn, $id) < 5 ) { ?> 
                            <i class='fas fa-star' id='star1' data-productId='<?= $id ?>' data-value='1'></i>
                            <i class='fas fa-star' id='star2' data-productId='<?= $id ?>' data-value='2'></i>
                            <i class='fas fa-star' id='star3' data-productId='<?= $id ?>' data-value='3'></i>
                            <i class='fas fa-star' id='star4' data-productId='<?= $id ?>' data-value='4'></i>
                            <i class='fas fa-star-half-alt' id='star5' data-productId='<?= $id ?>' data-value='5'></i>
                        <?php } else { ?>
                            <i class='fas fa-star' id='star1' data-productId='<?= $id ?>' data-value='1'></i>
                            <i class='fas fa-star' id='star2' data-productId='<?= $id ?>' data-value='2'></i>
                            <i class='fas fa-star' id='star3' data-productId='<?= $id ?>' data-value='3'></i>
                            <i class='fas fa-star' id='star4' data-productId='<?= $id ?>' data-value='4'></i>
                            <i class='fas fa-star' id='star5' data-productId='<?= $id ?>' data-value='5'></i>
                        <?php } } ?>
                      
                      </span>

                      <!-- RATING COUNT PER PRODUCT -->
                      <?php 
                        if ($totalProductRating == 0) {
                          echo "<span class='rating-count<?=$id?>'></span>";
                        } else { 
                          echo "&#40;<span class='rating-count<?=$id?>'>" . 
                            $totalProductRating .
                          "&#41;</span>"; 
                        }
                      ?>
                    </div>
                    <!-- /AVERAGE STAR RATING -->

                  </div>

                </div>
                <!-- /CARD BODY -->
              </a> 
            </div>
          </a>
        </div>
            
        <?php } ?>

      </div>
      <!-- /CARDS -->
  </div>
  
    <?php } elseif($statement->rowCount() > 0 && $statement->rowCount() < 6) { ?>
  <div class="container mb-5">
    <!-- LABEL -->
    <div class="row">
      <div class="col-6">
        <h2>
        &nbsp;RELATED PRODUCTS
        </h2>
      </div>
      <div class="col-6 text-right pt-2">View All&nbsp;<i class="fas fa-angle-double-right"></i></i></div>
    </div>
    <!-- CARDS -->
    <div class="row no-gutters">
        <?php 
          while($row = $statement->fetch()){
            $id = $row['id'];
            $name = $row['name'];
            $price = $row['price'];
            $description = $row['description'];
            $item_img = $row['img_path'];
        ?>
      <div class="col-lg-2 col-md-3 col-sm-6 px-1 pb-2">
        <a href="product.php?id=<?= $id ?>">
          <div class = 'card h-700 border-0' style='width:150px;'>
            <img class='card-img-top' src="<?= $item_img ?>" > 
            <div class="card-body">

              <div class='font-weight-bold'>
                <?= $name ?>
              </div>

              <div>&#8369; 
                <?= $price ?> 
              </div>

              <div class='d-flex flex-row mt-3'>
                <!-- WISHLIST BUTTONS -->
                <div class='flex-fill'>
                    <?php 
                      if(isset($_SESSION['id'])) {
                          if (checkIfInWishlist($conn,$id) == 0) {
                    ?>
                  <a class='mt-3 btn_add_to_wishlist_view' data-id='<?= $id ?>' role='button'>
                    <i class='far fa-heart' style="color:red"></i> 
                    <span class='product-wish-count<?= $id ?>'>
                      <?= getProductWishlishtCount($conn, $id) == 0 
                      ? "" 
                      : getProductWishlishtCount($conn, $id) ?>
                    </span>
                  </a>
                  
                    <?php  } else { ?>

                  <a class='mt-3 btn_already_in_wishlist_view' data-id='<?= $id ?>' disabled>
                    <i class='fas fa-heart' style='color:red'></i> 
                    <span class='product-wish-count<?= $id ?>'>
                      <?= getProductWishlishtCount($conn, $id) == 0 
                      ? "" 
                      : getProductWishlishtCount($conn, $id) ?>
                    </span>
                  </a>

                      <?php }  } else { ?>

                        <!-- IF LOGGED OUT -->
                  <a class='mt-3 btn_wishlist_logout_view' data-id='<?= $id ?>' disabled>
                    <i class='far fa-heart' style='color:gray'></i> 
                    <span class='product-wish-count<?= $id ?>'>
                      <?= getProductWishlishtCount($conn, $id) == 0 
                      ? "" 
                      : getProductWishlishtCount($conn, $id) ?>
                    </span>
                  </a>
                      
                  <?php }  ?>
                          
                </div>
                <!-- /WISH LIST BUTTONS -->

                <!-- AVERAGE STAR RATING -->
                <div class='flex-fill text-right'>
                      
                  <!-- AVERAGE PRODUCT REVIEW (STARS) -->
                  <span class='rating-average-in-stars<?=$id?>'>
                    <?php 
                    if (isset($_SESSION['cart_session'])) {
                      if(getAveProductReview($conn, $id) >= 1 && getAveProductReview($conn, $id) < 1.5 ) { ?>
                        <i class='fas fa-star' id='star1' data-productId='<?= $id ?>' data-value='1'></i>
                        <i class='far fa-star' id='star2' data-productId='<?= $id ?>' data-value='2'></i>
                        <i class='far fa-star' id='star3' data-productId='<?= $id ?>' data-value='3'></i>
                        <i class='far fa-star' id='star4' data-productId='<?= $id ?>' data-value='4'></i>
                        <i class='far fa-star' id='star5' data-productId='<?= $id ?>' data-value='5'></i>
                    <?php } elseif (getAveProductReview($conn, $id) >= 1.5 && getAveProductReview($conn, $id) < 2 ) { ?>
                        <i class='fas fa-star' id='star1' data-productId='<?= $id ?>' data-value='1'></i>
                        <i class='fas fa-star-half-alt' id='star2' data-productId='<?= $id ?>' data-value='2'></i>
                        <i class='far fa-star' id='star3' data-productId='<?= $id ?>' data-value='3'></i>
                        <i class='far fa-star' id='star4' data-productId='<?= $id ?>' data-value='4'></i>
                        <i class='far fa-star' id='star5' data-productId='<?= $id ?>' data-value='5'></i>
                    <?php } elseif (getAveProductReview($conn, $id) >= 2 && getAveProductReview($conn, $id) < 2.5 ) { ?>
                        <i class='fas fa-star' id='star1' data-productId='<?= $id ?>' data-value='1'></i>
                        <i class='fas fa-star' id='star2' data-productId='<?= $id ?>' data-value='2'></i>
                        <i class='far fa-star' id='star3' data-productId='<?= $id ?>' data-value='3'></i>
                        <i class='far fa-star' id='star4' data-productId='<?= $id ?>' data-value='4'></i>
                        <i class='far fa-star' id='star5' data-productId='<?= $id ?>' data-value='5'></i>
                    <?php } elseif (getAveProductReview($conn, $id) >= 2.5 && getAveProductReview($conn, $id) < 3 ) { ?>
                        <i class='fas fa-star' id='star1' data-productId='<?= $id ?>' data-value='1'></i>
                        <i class='fas fa-star' id='star2' data-productId='<?= $id ?>' data-value='2'></i>
                        <i class='fas fa-star-half-alt' id='star3' data-productId='<?= $id ?>' data-value='3'></i>
                        <i class='far fa-star' id='star4' data-productId='<?= $id ?>' data-value='4'></i>
                        <i class='far fa-star' id='star5' data-productId='<?= $id ?>' data-value='5'></i>
                    <?php } elseif (getAveProductReview($conn, $id) >= 3 && getAveProductReview($conn, $id) < 3.5 ) { ?>
                        <i class='fas fa-star' id='star1' data-productId='<?= $id ?>' data-value='1'></i>
                        <i class='fas fa-star' id='star2' data-productId='<?= $id ?>' data-value='2'></i>
                        <i class='fas fa-star' id='star3' data-productId='<?= $id ?>' data-value='3'></i>
                        <i class='far fa-star' id='star4' data-productId='<?= $id ?>' data-value='4'></i>
                        <i class='far fa-star' id='star5' data-productId='<?= $id ?>' data-value='5'></i>
                    <?php } elseif (getAveProductReview($conn, $id) >= 3.5 && getAveProductReview($conn, $id) < 4 ) { ?>
                        <i class='fas fa-star' id='star1' data-productId='<?= $id ?>' data-value='1'></i>
                        <i class='fas fa-star' id='star2' data-productId='<?= $id ?>' data-value='2'></i>
                        <i class='fas fa-star' id='star3' data-productId='<?= $id ?>' data-value='3'></i>
                        <i class='fas fa-star-half-alt' id='star4' data-productId='<?= $id ?>' data-value='4'></i>
                        <i class='far fa-star' id='star5' data-productId='<?= $id ?>' data-value='5'></i>
                    <?php } elseif (getAveProductReview($conn, $id) >= 4 && getAveProductReview($conn, $id) < 4.5 ) { ?> 
                        <i class='fas fa-star' id='star1' data-productId='<?= $id ?>' data-value='1'></i>
                        <i class='fas fa-star' id='star2' data-productId='<?= $id ?>' data-value='2'></i>
                        <i class='fas fa-star' id='star3' data-productId='<?= $id ?>' data-value='3'></i>
                        <i class='fas fa-star' id='star4' data-productId='<?= $id ?>' data-value='4'></i>
                        <i class='far fa-star' id='star5' data-productId='<?= $id ?>' data-value='5'></i>
                    <?php } elseif (getAveProductReview($conn, $id) >= 4.5 && getAveProductReview($conn, $id) < 5 ) { ?> 
                        <i class='fas fa-star' id='star1' data-productId='<?= $id ?>' data-value='1'></i>
                        <i class='fas fa-star' id='star2' data-productId='<?= $id ?>' data-value='2'></i>
                        <i class='fas fa-star' id='star3' data-productId='<?= $id ?>' data-value='3'></i>
                        <i class='fas fa-star' id='star4' data-productId='<?= $id ?>' data-value='4'></i>
                        <i class='fas fa-star-half-alt' id='star5' data-productId='<?= $id ?>' data-value='5'></i>
                    <?php } else { ?>
                        <i class='fas fa-star' id='star1' data-productId='<?= $id ?>' data-value='1'></i>
                        <i class='fas fa-star' id='star2' data-productId='<?= $id ?>' data-value='2'></i>
                        <i class='fas fa-star' id='star3' data-productId='<?= $id ?>' data-value='3'></i>
                        <i class='fas fa-star' id='star4' data-productId='<?= $id ?>' data-value='4'></i>
                        <i class='fas fa-star' id='star5' data-productId='<?= $id ?>' data-value='5'></i>
                    <?php } } ?>
                  
                  </span>

                    <!-- RATING COUNT PER PRODUCT -->
                    <?php 
                      if ($totalProductRating == 0) {
                          echo "<span class='rating-count<?=$id?>'></span>";
                      } else { 
                          echo "&#40;<span class='rating-count<?=$id?>'>" . 
                            $totalProductRating .
                          "&#41;</span>";
                      }
                    ?>

                </div>       
              </div>

            </div>
            <!-- /.CARD BODY -->
          </div>
          <!-- /.CARD -->
        </a>
      </div>
              
        <?php } ?>
    </div>
  </div>

    <? } else { echo ""; } ?>
  <!-- /RELATED PRODUCTS -->
 

  <!-- PRODUCTS FROM SELLER -->
  <div class="container mb-5" style="width:100%;">
    <div class="pl-3 row">
      <div class="col-6">
        <h2>
          &nbsp;OTHER PRODUCTS FROM SHOP
        </h2>
      </div>
    </div>
    <div class="row no-gutters autoplay justify-content-left">
        <?php 

          $sql = "SELECT * FROM tbl_items WHERE id != ? AND store_id = ? LIMIT 12 ";
          $statement = $conn->prepare($sql);
          $statement->execute([$id,$storeId]);

          if($statement->rowCount()){
            while($row = $statement->fetch()){
              $id = $row['id'];
              $name = $row['name'];
              $price = $row['price'];
              $description = $row['description'];
              $item_img = $row['img_path'];
        ?>
      
      <div class="col-lg-2 col-md-3">
        <a href="product.php?id=<?= $id ?>">
          <div class='card h-700 border-0' style='width:100%;'>
            <a href="product.php?id=<?= $row['id'] ?>">
              <img class='card-img-top' src="<?= $item_img ?>">

              <div class="card-body">
                <div>
                  <?= $name ?>
                </div>
                <div>&#8369; 
                  <?= $price ?> 
                </div>

                <div class='d-flex flex-row mt-3'>
                  <!-- WISHLIST BUTTONS -->
                  <div class='flex-fill'>
                      <?php 
                        if(isset($_SESSION['id'])) {
                            if (checkIfInWishlist($conn,$id) == 0) {
                      ?>

                    <a class='mt-3 btn_add_to_wishlist_view' data-id='<?= $id ?>' role='button'>
                      <i class='far fa-heart' style="color:red"></i> 
                        <span class='product-wish-count<?= $id ?>'>
                          <?= getProductWishlishtCount($conn, $id) == 0 
                          ? "" 
                          : getProductWishlishtCount($conn, $id) ?>
                        </span>
                    </a>
                
                      <?php  } else { ?>

                    <a class='mt-3 btn_already_in_wishlist_view' data-id='<?= $id ?>' disabled>
                      <i class='fas fa-heart' style='color:red'></i> 
                        <span class='product-wish-count<?= $id ?>'>
                          <?= getProductWishlishtCount($conn, $id) == 0 
                          ? "" 
                          : getProductWishlishtCount($conn, $id) ?>
                        </span>
                    </a>

                      <?php }  } else { ?>

                    <!-- IF LOGGED OUT -->
                    <a class='mt-3 btn_wishlist_logout_view' data-id='<?= $id ?>' disabled>
                      <i class='far fa-heart' style='color:gray'></i> 
                        <span class='product-wish-count<?= $id ?>'>
                          <?= getProductWishlishtCount($conn, $id) == 0 
                          ? "" 
                          : getProductWishlishtCount($conn, $id) ?>
                        </span>
                    </a>

                      <?php }  ?>
                        
                  </div>

                  <!-- AVERAGE STAR RATING -->
                  <div class='flex-fill text-right'>
                    
                    <!-- AVERAGE PRODUCT REVIEW (STARS) -->
                    <span class='rating-average-in-stars<?=$id?>'>
                      <?php 
                      if (isset($_SESSION['cart_session'])) {
                        if(getAveProductReview($conn, $id) >= 1 && getAveProductReview($conn, $id) < 1.5 ) { ?>
                          <i class='fas fa-star' id='star1' data-productId='<?= $id ?>' data-value='1'></i>
                          <i class='far fa-star' id='star2' data-productId='<?= $id ?>' data-value='2'></i>
                          <i class='far fa-star' id='star3' data-productId='<?= $id ?>' data-value='3'></i>
                          <i class='far fa-star' id='star4' data-productId='<?= $id ?>' data-value='4'></i>
                          <i class='far fa-star' id='star5' data-productId='<?= $id ?>' data-value='5'></i>
                      <?php } elseif (getAveProductReview($conn, $id) >= 1.5 && getAveProductReview($conn, $id) < 2 ) { ?>
                          <i class='fas fa-star' id='star1' data-productId='<?= $id ?>' data-value='1'></i>
                          <i class='fas fa-star-half-alt' id='star2' data-productId='<?= $id ?>' data-value='2'></i>
                          <i class='far fa-star' id='star3' data-productId='<?= $id ?>' data-value='3'></i>
                          <i class='far fa-star' id='star4' data-productId='<?= $id ?>' data-value='4'></i>
                          <i class='far fa-star' id='star5' data-productId='<?= $id ?>' data-value='5'></i>
                      <?php } elseif (getAveProductReview($conn, $id) >= 2 && getAveProductReview($conn, $id) < 2.5 ) { ?>
                          <i class='fas fa-star' id='star1' data-productId='<?= $id ?>' data-value='1'></i>
                          <i class='fas fa-star' id='star2' data-productId='<?= $id ?>' data-value='2'></i>
                          <i class='far fa-star' id='star3' data-productId='<?= $id ?>' data-value='3'></i>
                          <i class='far fa-star' id='star4' data-productId='<?= $id ?>' data-value='4'></i>
                          <i class='far fa-star' id='star5' data-productId='<?= $id ?>' data-value='5'></i>
                      <?php } elseif (getAveProductReview($conn, $id) >= 2.5 && getAveProductReview($conn, $id) < 3 ) { ?>
                          <i class='fas fa-star' id='star1' data-productId='<?= $id ?>' data-value='1'></i>
                          <i class='fas fa-star' id='star2' data-productId='<?= $id ?>' data-value='2'></i>
                          <i class='fas fa-star-half-alt' id='star3' data-productId='<?= $id ?>' data-value='3'></i>
                          <i class='far fa-star' id='star4' data-productId='<?= $id ?>' data-value='4'></i>
                          <i class='far fa-star' id='star5' data-productId='<?= $id ?>' data-value='5'></i>
                      <?php } elseif (getAveProductReview($conn, $id) >= 3 && getAveProductReview($conn, $id) < 3.5 ) { ?>
                          <i class='fas fa-star' id='star1' data-productId='<?= $id ?>' data-value='1'></i>
                          <i class='fas fa-star' id='star2' data-productId='<?= $id ?>' data-value='2'></i>
                          <i class='fas fa-star' id='star3' data-productId='<?= $id ?>' data-value='3'></i>
                          <i class='far fa-star' id='star4' data-productId='<?= $id ?>' data-value='4'></i>
                          <i class='far fa-star' id='star5' data-productId='<?= $id ?>' data-value='5'></i>
                      <?php } elseif (getAveProductReview($conn, $id) >= 3.5 && getAveProductReview($conn, $id) < 4 ) { ?>
                          <i class='fas fa-star' id='star1' data-productId='<?= $id ?>' data-value='1'></i>
                          <i class='fas fa-star' id='star2' data-productId='<?= $id ?>' data-value='2'></i>
                          <i class='fas fa-star' id='star3' data-productId='<?= $id ?>' data-value='3'></i>
                          <i class='fas fa-star-half-alt' id='star4' data-productId='<?= $id ?>' data-value='4'></i>
                          <i class='far fa-star' id='star5' data-productId='<?= $id ?>' data-value='5'></i>
                      <?php } elseif (getAveProductReview($conn, $id) >= 4 && getAveProductReview($conn, $id) < 4.5 ) { ?> 
                          <i class='fas fa-star' id='star1' data-productId='<?= $id ?>' data-value='1'></i>
                          <i class='fas fa-star' id='star2' data-productId='<?= $id ?>' data-value='2'></i>
                          <i class='fas fa-star' id='star3' data-productId='<?= $id ?>' data-value='3'></i>
                          <i class='fas fa-star' id='star4' data-productId='<?= $id ?>' data-value='4'></i>
                          <i class='far fa-star' id='star5' data-productId='<?= $id ?>' data-value='5'></i>
                      <?php } elseif (getAveProductReview($conn, $id) >= 4.5 && getAveProductReview($conn, $id) < 5 ) { ?> 
                          <i class='fas fa-star' id='star1' data-productId='<?= $id ?>' data-value='1'></i>
                          <i class='fas fa-star' id='star2' data-productId='<?= $id ?>' data-value='2'></i>
                          <i class='fas fa-star' id='star3' data-productId='<?= $id ?>' data-value='3'></i>
                          <i class='fas fa-star' id='star4' data-productId='<?= $id ?>' data-value='4'></i>
                          <i class='fas fa-star-half-alt' id='star5' data-productId='<?= $id ?>' data-value='5'></i>
                      <?php } else { ?>
                          <i class='fas fa-star' id='star1' data-productId='<?= $id ?>' data-value='1'></i>
                          <i class='fas fa-star' id='star2' data-productId='<?= $id ?>' data-value='2'></i>
                          <i class='fas fa-star' id='star3' data-productId='<?= $id ?>' data-value='3'></i>
                          <i class='fas fa-star' id='star4' data-productId='<?= $id ?>' data-value='4'></i>
                          <i class='fas fa-star' id='star5' data-productId='<?= $id ?>' data-value='5'></i>
                      <?php } } ?>
                    
                    </span>

                    <!-- RATING COUNT PER PRODUCT -->
                    <?php 
                      if ($totalProductRating == 0) {
                          echo "<span class='rating-count<?=$id?>'></span>";
                      } else { 
                          echo "&#40;<span class='rating-count<?=$id?>'>" . 
                            $totalProductRating .
                          "&#41;</span>";
                      }
                    ?>
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


</body>
</html> 

  
    

<?php require_once "../partials/footer.php";?>
<?php require_once "../partials/modal_container.php"; ?>
