<?php require_once "../../config.php";?>
<?php require_once "../partials/header.php";?>
<?php require_once "../partials/categories.php"; ?>


<?php 
$id = $_GET['id'];

if(isset($_SESSION['id'])) {
  $userId = $_SESSION['id'];
  $currentUser = getUser($conn, $userId);
  $isSeller = $currentUser['isSeller'];
}
?>


  <!-- BREADCRUMBS -->
  <!-- <div class="container">
    <div class="row my-4">
      <div class="col-12">
     
          @$origin = $_SERVER['HTTP_REFERER'];
          displayBreadcrumbs($conn, $id, $origin); 
        
      </div>
    </div>
  </div> -->

      <!-- PRODUCT PAGE MAIN CONTAINER -->
      <div class="container mt-5">

        <!-- FIRST ROW -->
        <div class="row bg-white mb-5 rounded p-lg-5 py-md-5">
            
          <!-- FIRST COLUMN (PICS) -->
          <div class="col-lg-5 col-md-6 col-sm-12">

            <input type="hidden" id='iframeId'>
            <!-- IFRAME -->
            <div class="row mb-3">
              <?php 
                $sql = "SELECT * FROM tbl_product_images WHERE product_id = ?";
                $statement = $conn->prepare($sql);
                $statement->execute([$id]);	
                $row = $statement->fetch();
                $url = $row['url'];
                $img_id = $row['id'];
              ?>

              <div class="col position-relative" id="product_iframe">
                <img src='<?= BASE_URL . "/" .$url.".jpg" ?>' style='width:100%;height:510px;' iframe_img_id='<?= $img_id ?>'>
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
              <div class="col-2 px-0 m-0">
                <div class="card" style="border:none;">
                  <img src='<?= BASE_URL . "/" .$url.".jpg" ?>' style='width:67px;height:80px;cursor:pointer;' class='product_thumbnail' data-id='<?=$img_id?>' data-url='<?= BASE_URL . "/" .$url.".jpg" ?>'>
                </div>
              </div>

              <?php endwhile;?>
            </div>

          </div>
          <!-- /FIRST COLUMN (PICS) -->

        

          <!-- SECOND COLUMN (PRODUCT DETAILS) -->
          <div class="col-lg-7 col-md-6 col-sm-12 px-lg-5">
              <br class='vanish-lg vanish-md'>
                <?php
              
                  $sql = "SELECT * FROM tbl_items WHERE id = ?";
                  $statement = $conn->prepare($sql);
                  $statement->execute([$id]);
                  $row = $statement->fetch();
                  $name = $row['name'];     
                  $price = $row['price'];
                  $price = number_format((float)$price, 2, '.', '');
                  $description = $row['description'];
                  $item_img = $row['img_path'];
                  $categoryId = $row['category_id'];
                  $brandId = $row['brand_id'];
                  // $stocks = $row['stocks'];
                  $storeId = $row['store_id'];

              ?>

              <!-- PRODUCT NAME -->
              <div class="row pl-4">
                <h1><?= ucwords(strtolower($name)); ?></h1>
              </div>    
                
              <!-- PRODUCT RATING -->
              <div class="row pl-4 mb-4">
                <div class="col-12 pl-0">
                  <div class="d-flex flex-row">
                      <?php
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
                    <!-- AVE PRODUCT RATING AS STARS -->
                    <div class='flex' style='flex: 1 0 1%'>
                      <div id='average_product_stars'>
                      </div>
                    </div>
                  
                  
                    <div class='flex' style='flex: 2 5 50px; margin: 0 10px'>
                      <!-- NUMBER OF REVIEWS -->
                      <?php 

                        $totalProductRating = countRatingsPerProduct($conn, $id);

                        if (isset($_SESSION['cart_session'])) { 
                          if ($totalProductRating === 0 || $totalProductRating == "") { ?>
                        <span class='rating-count<?=$id?>'>
                          <!-- no value -->
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
                  

                    <div class='' style='flex:5'>
                    

                      <?php 
                        if(isset($_SESSION['id'])) {
                            if (checkIfInWishlist($conn,$id) == 0) {
                      ?>
                        <a class='heart-toggler' data-id='<?= $id ?>' role='button' data-enabled="0" style='float:right'>
                          <span class='wish_heart'><i class='far fa-heart fa-2x text-gray' id></i></span>
                        </a>
                  
                      <?php  } else { ?>

                        <a class='heart-toggler' data-id='<?= $id ?>' data-enabled="1" style='float:right'>
                          <span class='wish_heart'><i class='fas fa-heart fa-2x text-red'></i></span> 
                          <span class='product_wish_count'><?= getProductWishlishtCount($conn,$id) ?></span>
                        </a>

                      <!-- IF LOGGED OUT -->
                      <?php }  } else { 
                        if(getProductWishlishtCount($conn,$id) >= 1) {
                      ?>
                        
                        <a class='btn_wishlist_logout_view' data-id='<?= $id ?>' disabled style='cursor:default; float:right'>
                          <i class='far fa-heart fa-2x text-red'></i> 
                          <span class='product_wish_count'><?= getProductWishlishtCount($conn,$id) ?></span>
                        </a>
                        
                      <?php } else { ?>
                        <a class='btn_wishlist_logout_view' data-id='<?= $id ?>' disabled style='cursor:default; float:right'>
                          <i class='far fa-heart fa-2x text-gray'></i> 
                        </a>
                        
                      <?php } } ?>
                      
                    </div>

                  </div>

                </div>  

              </div>

              <!-- PRICE -->
              <div class="row pl-4">
                <h1 class='font-weight-bold text-purple'>&#36;&nbsp;<?= $price?></h1>
              </div>

              <hr class='mt-4 mb-5'>

              <!-- SHIPPING FEE -->
              <?php if(displayFreeShippingMinimum($conn,$id)) { ?>

              <div class="row mb-3">

                <div class="col-lg-4 col-md-4 col-sm-4">
                  <div>Shipping Fee</div>
                </div>

                <div class="col-lg-3 col-md-2 col-sm-2">
                  <div class="row">
                    &#36;&nbsp;
                    <span id='shipping_fee'><?= displayShippingFee($conn,$id) ?></span>            
                  </div>
                </div>

                <div class="col">
               
                    <div class="d-flex flex-lg-row flex-md-row flex-sm-column text-center border bg-light p-2">
                        <small class='text-purple'>FREE SHIPPING</small>
                        <small class='text-gray'>
                          With a minimum spend of &#36;&nbsp;<?=displayFreeShippingMinimum($conn,$id);?> from seller.
                        </small>
                    </div>
                  
                </div>

              </div>

                <?php } else { ?>

              <div class="row mb-5">
                <div class="col-lg-4 col-md-6 col-sm-6">
                  <div>Shipping Fee</div>
                </div>
                <div class="col-lg-8 col-md-6 col-sm-6">
                  <div class="row">
                    &#36;&nbsp;
                    <span id='shipping_fee'><?= displayShippingFee($conn,$id) ?></span>            
                  </div>
                </div>
              </div>

                <?php } ?>

              <!-- BRAND -->
              <div class="row mb-5">

                <div class="col-lg-4 col-md-4 col-sm-4">
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
                <div class="col-lg-4 col-md-4 col-sm-4">
                  <div>Variation</div>
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
                          $variationName  = ucfirst(strtolower($variationName));
                          $variationStock = $row['variation_stock'];

                          if($variationName !== 'None') {

                            // query to check if this variation is already in cart
                          $disabled = '';
                          $sql2 = "SELECT v.*, c.cart_session FROM tbl_carts c 
                                  JOIN tbl_variations v ON c.variation_id=v.id 
                                  WHERE cart_session = ? AND product_id = ? 
                                  AND v.variation_name= ? GROUP BY id";
                          $statement2 = $conn->prepare($sql2);
                          $statement2->execute([$cartSession,$id,$variationName]);
                          $countVariation = $statement2->rowCount();
                          
                          if($countVariation>0) {
                            $disabled = 'disabled';
                            
                          }
                    ?>
                    
                    <button class="btn p-2 mr-2 mb-2 bg-light text-center border btn_variation text-responsive " 
                      <?= $disabled ?> 
                      data-variationStock='<?= $variationStock ?>' 
                      data-id='<?=$variationId?>'>
                      <?= ucwords($variationName) ?>
                    </button>

                    <?php } else { 

                        echo "None";

                    } } } ?>
                    <!-- HIDDEN FOR DEBUGGING PURPOSES -->
                    <input type="hidden" id='variation_stock_hidden' value='<?
                      if(isset($variationStock)) {
                        echo $variationStock;
                      } else {
                        $variationStock = getTotalProductStocks ($conn,$productId);
                        echo $variationStock;
                      }
                      ?>'>
                    <input type="hidden" id='variation_name_hidden' value='<?$variationName?>'>
                    <input type="hidden" id='variation_id_hidden' value='<?=$variationId?>'>
                  </div>
                </div>
              </div>
              
              <!-- QUANTITY --> 
              <div class="row mb-4">
                <?php $totalStocksAvailable = getTotalProductStocks($conn,$id) ?>
                <div class="col-lg-4 col-md-3 col-sm-4">
                  <div>Quantity</div>
                </div>
                <div class="col pl-0">
                  <div class="d-flex flex-lg-row flex-md-row flex-sm-column mb-3">
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
                          if($totalStocksAvailable == 1){
                        ?>
                      <span class='variation_display'>&nbsp;piece available</span>
                        
                        <?php } else { ?>
                  
                      <span class='variation_display'>&nbsp;pieces available</span>
                      <?php } ?>
                    </div>
                  </div>
                  <div id='variation_error'>
                    <small style='color:#f5f5f5;'>I'm invisible</small>
                  </div>
                </div>
              </div>
                  
              <!-- BUTTON -->
              <div class="d-flex flex-row">
                <?php
                  $sql = " SELECT v.product_id, v.variation_name as 'var_name', c.cart_session FROM tbl_carts c JOIN tbl_variations v ON c.variation_id=v.id WHERE cart_session=? AND product_id=?";
                  //$result = mysqli_query($conn, $sql);
                  $statement = $conn->prepare($sql);
                  $statement->execute([$cartSession, $id]);
                  $row = $statement->fetch();
                        $vname = $row['var_name'];
                        $vname = ucfirst(strtolower($vname));

                          if($vname == 'None') {
                ?>
                <!-- SINCE BUTTON HAS NO VARIATION THERE IS NO NEED TO ADD IT TO CART AGAIN ANYMORE -->
                <button class='btn btn-lg btn-gray py-3' data-id='<?= $id ?>' role='button' data-variationid='<?=$variationId?>' data-name='<?=$variationName?>' id="btn_add_to_cart_again" disabled>
                    &nbsp;Item Already In Cart
                </button>

                <?php
                          } else {
                            $count = $statement->rowCount();
                            if($count) {
                ?>  

                <button class='btn btn-lg btn-purple-reverse py-3' data-id='<?= $id ?>' role='button' data-variationid='<?=$variationId?>' data-name='<?=$variationName?>' id="btn_add_to_cart_again">
                  &#65291;&nbsp;Add To Cart Again
                </button>

                <?php } else { ?>
                
                <a class='btn btn-lg btn-purple py-3' data-id='<?= $id ?>' role='button' data-variationid='<?=$variationId?>' data-name='<?=$variationName?>' id="btn_add_to_cart">
                  &#65291;&nbsp;Add To Cart
                </a>
                <?php } } ?>


              </div>

          </div>
          <!-- /PRODUCT DETAILS -->

        </div>
        <!-- /FIRST ROW -->

        <!-- SECOND ROW -->
        <div class="row bg-white rounded mb-5 px-5">

          <!-- SELLER DETAILS -->
          <div class="col-lg-2 col-md-3 col-sm-12 mr-lg-5 white-bg py-5">

            <div class='row mb-4'>
                <div class="col-12 py-5 border">
                  
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
                        $shippingFee = $row['standard_shipping'];
                      ?>
                    
                    <!-- STORE LOG -->
                    <div class="row justify-content-center mb-4">
                      <img src="<?= BASE_URL ."/". $storeLogo . ".jpg"?>" alt="<?=$storeName?>" style='width:70px;max-height:70px;' class='circle'>
                    </div>

                    <!-- STORE NAME -->
                    <div class="row justify-content-center text-purple font-weight-bold mb-4">
                      <?=$storeName?>
                    </div>

                    <!-- STORE ADDRESS -->
                    <div class="row justify-content-center text-gray mb-2 px-2">
                      <small>
                        <i class="fas fa-map-marker-alt"></i>
                        &nbsp;<?= ucwords(strtolower($storeAddress)) ?>
                      </small>
                    </div>


                    <!-- LAST ACTIVITY -->
                    <div class="row justify-content-center text-gray">
                      <?php
                        $sql = "SELECT last_login FROM tbl_users WHERE id = ?";
                        $statement = $conn->prepare($sql);
                        $statement->execute([$sellerId]);	
                        $row = $statement->fetch();
                        $lastLogin = $row['last_login'];

                      ?>
                      <small id='lastLoginTimeAgo'><?= $lastLogin ?></small>
                    </div>

                   
                    <hr class='my-4'>

                    <!-- STATS -->
                    <div class="row text-gray mb-4">
                      <div class="col ml-3">

                        <!-- RATINGS -->
                        <div class="d-flex flex-row mb-3">
                            <?php
                            $sql = "SELECT i.id, i.store_id, AVG(product_rating) as 'averageRating' 
                            FROM tbl_ratings 
                            LEFT JOIN tbl_items i ON product_id = i.id 
                            WHERE store_id = ? GROUP BY product_id";
                              $statement = $conn->prepare($sql);
                              $statement->execute([$storeId]);
                              $row = $statement->fetch();
                              $averageSellerRating = $row['averageRating'];	
                              $averageSellerRating = round($averageSellerRating,1);
                            ?>
                          <small style='width:55%;'>
                            Rating
                          </small>
                          <small>
                            <?= $averageSellerRating ?> 
                            <span class='vanish-md'>out</span> 
                            of 5
                          </small>
                        </div>

                        <!-- FOLLWERS -->
                        <div class="d-flex flex-row mb-3">
                          <small style='width:55%;'>
                            Followers
                          </small>
                          <small>
                            <?= countFollowers ($conn, $storeId) ?>
                          </small>
                        </div>

                        <!-- PRODUCTS COUNT -->
                        <div class="d-flex flex-row mb-3">
                            <?php
                              $sql = "SELECT COUNT(*) AS 'productCount' FROM tbl_items WHERE store_id = ?";
                              $statement = $conn->prepare($sql);
                              $statement->execute([$storeId]);
                              $row = $statement->fetch();
                              $productCount = $row['productCount'];	
                            ?>
                          <small style='width:55%;'>
                            Products
                          </small>
                          <small>
                            <?= $productCount ?>
                          </small>
                        </div>

                        <!-- JOINED -->
                        <div class="d-flex flex-row mb-4">
                            
                          <small style='width:55%;'>
                            Joined
                          </small>
                          <small>
                            <?=  getMembershipDate($conn, $storeId) ?>
                          </small>
                          <!-- https://stackoverflow.com/questions/6823133/how-to-remove-first-word-from-a-php-string -->
                        </div>

                      </div> 
                    </div>

                  
                    <!-- BUTTONS -->
                    <div class="row">
                      <!-- <a href='#' class='btn btn-block border text-purple mx-3 mb-2 py-2 modal-link' id="chatbox" data-url="../partials/templates/chatbox_modal.php">
                        <i class="far fa-comment"></i>
                        &nbsp;Message Seller
                      </a> -->

                      <a href='store-profile.php?id=<?=$storeId?>' class='btn btn-block border text-secondary mx-3 py-2'>
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
          <div class="col-lg-9 col-md-9 col-sm-12">
            <div class="row">
              <div class="col-lg-12">
                
                <!-- TABS -->
                <div class="row my-lg-5 mt-md-5">
                  <div class="col-12">
                    <div class="tab border-bottom d-flex flex-row">
                      <button class="tablinks flex-fill active" onclick="window.openTab(event, 'info_content')">
                        <h4>Product Details</h4>
                      </button>
                      <button class="tablinks flex-fill progress-x" onclick="window.openTab(event, 'reviews_content')">
                        <h4>Reviews</h4>
                      </button>
                      <button class="tablinks flex-fill" onclick="window.openTab(event, 'questions_content')">
                        <h4>Q&As</h4>
                      </button>
                    </div>
                  </div>
                </div>

                <!-- CONTENT -->
                <div class='row'>
                  <div class='col-12'>

                    <!-- PRODUCT INFO -->
                    <div id="info_content" class="tabcontent" style='display:block'>
                      <div class="container mt-2 pl-0">
                        <div class="row">
                          <!-- <div class="col-1"></div> -->
                          <div class="col">
                              <!-- <ul class='d-flex flex-wrap'> -->
                              <ul>
                                <?php
                                  $sql = "SELECT * FROM tbl_item_descriptions WHERE product_id = ?";
                                  $statement = $conn->prepare($sql);
                                  $statement->execute([$id]);	
                                  $count = $statement->rowCount();
                                  if($count) {
                                    while($row = $statement->fetch()) { 
                                      $description = $row['description'];
                                ?>
                                  <!-- <li style='line-height:1.8; flex: 1 0 50%' class='pb-5 pl-lg-5 pl-md-5 pl-sm-4 product_details'>$description</li> -->
                                  <li class='pb-5 pl-lg-5 pl-md-5 pl-sm-4 product_details' style='line-height:1.8;'>
                                    <?= $description ?>
                                  </li>
                                <?php } } ?>
                              </ul>
                            
                          </div>
                          
                        </div>
                      </div>
                    </div>
                    
                    <!-- Q&As -->
                    <div id="questions_content" class="tabcontent">
                      <div class="container px-4">
                        <div class="row">

                          <!-- FREQUENTLY ASKED -->
                          <div class="col-lg-6 col-md-6 col-sm-12 pt-md-4 pt-sm-4">
                            <div class="row mb-4">
                              <!-- <img src="../assets/images/question-gradient-filled.png" alt="verified_user" style='height:20px;width:20px;'> -->
                              <div class='py-1 text-secondary'>FREQUENTLY ASKED</div>
                            </div>

                            <div class="row">
                              <div class="col-12 pl-0" style="overflow-x:scroll;max-height:400px;">
                                

                                      <?php
                                        $sql = "SELECT q.*,u.username FROM tbl_questions_answers q JOIN tbl_users u ON q.user_id = u.id WHERE product_id = ? AND faq = 'yes' ";
                                        $statement = $conn->prepare($sql);
                                        $statement->execute([$id]);
                                        $count = $statement->rowCount();
                                      
                                        if($count) {
                                          while($row = $statement->fetch()) {
                                            $question = $row['question'];
                                            $answer = $row['answer'];
                                      ?>

                                    
                                      <div class="d-flex flex-column pb-5">
                                        <div>
                                          <!-- <i class="fas fa-question-circle text-purple"></i> -->
                                          <span class='text-purple'>
                                            <?=$question?>
                                          </span>
                                        </div>
                                        <div class='pt-2'>
                                          <?=$answer?>
                                        </div>
                                      </div>
                                    
                                  

                                    <?php } } ?>
                                    
                              </div>
                            </div>
   
                          </div>
                          
                          <!-- OTHER QUESTIONS-->
                          <div class="col-lg-6 col-md-6 col-sm-12 pt-md-4 pt-sm-4">
                            <div class="row mb-4">
                              <div class='py-1 text-secondary'>OTHER QUESTIONS</div>
                            </div>

                            <div class="row">
                              <div class="col-12 px-0" style="overflow-x:scroll;max-height:400px;">
                                

                                      <?php
                                        $sql = "SELECT q.*,u.username FROM tbl_questions_answers q JOIN tbl_users u ON q.user_id = u.id WHERE product_id = ? AND faq = 'no' ";
                                        $statement = $conn->prepare($sql);
                                        $statement->execute([$id]);
                                        $count = $statement->rowCount();
                                      
                                        if($count) {
                                          while($row = $statement->fetch()) {
                                            $question = $row['question'];
                                            $answer = $row['answer'];
                                            $whoAskedId = $row['user_id'];
                                            $whoAsked = $row['username'];
                                            $dateAsked = $row['date_asked'];
                                            $dateAnswered = $row['date_answered'];

                                      ?>

                                    
                                      <div class="d-flex flex-column pb-5">
                                        <div>
                                          <!-- <img src="../assets/images/question-gradient.png" alt="verified_user" style='height:20px;width:20px;'> -->
                                          <span class='text-purple'>
                                            <?=$question?>
                                          </span>
                                        </div>
                                        <div class='text-gray pb-3'>
                                          <small><?=$whoAsked?>&nbsp;</small>
                                          <small>
                                            <?php
                                              $datetime1 = new DateTime($dateAsked);
                                              $datetime2 = new DateTime();
                                              $interval = $datetime1->diff($datetime2);
                                              $ago = "";

                          
                                              if($interval->format('%w') != 0) {
                                                  $ago = $interval->format('- %w weeks ago');
                                              } else {
                                                if($interval->format('%d') != 0) {
                                                  $ago = $interval->format('- %d days ago ');
                                                } else {
                                                  if($interval->format('%h') != 0) {
                                                    $ago = $interval->format('- %h hrs ago');
                                                  } elseif($interval->format('- %i') != 0) {
                                                    $ago = $interval->format('- %i minutes ago');
                                                  } else {
                                                    $ago = "- just now";
                                                  }
                                                } 
                                              }

                                              echo $ago;
                                            ?>

                                          </small>
                                        </div>
                                          <?php if($answer != null){ ?>

                                        <div class='pt-2'>
                                          <div><?=$answer?></div>
                                          <div class='text-gray'>
                                            <small><?=$storeName?></small>
                                            <small>
                                              <?php 
                                            
                                                $datetimeA = new DateTime($dateAsked);
                                                $datetimeB = new DateTime($dateAnswered);
                                                $interval2 = $datetimeB->diff($datetimeA);
                                                $ago2 = "";

                            
                                                if($interval2->format('%w') != 0) {
                                                    $ago2 = $interval2->format('- answered within %w week/s');
                                                } else {
                                                  if($interval2->format('%d') != 0) {
                                                    $ago2 = $interval2->format('- answered within %d day/s');
                                                  } else {
                                                    if($interval2->format('%h') != 0) {
                                                      $ago2 = $interval2->format('- asnwered within %h hr/s');
                                                    } elseif($interval2->format('%i') != 0) {
                                                      $ago2 = $interval2->format('- answered within %i min/s');
                                                    } else {
                                                      $ago2 = $interval2->format('- answered within %s sec/s');
                                                    }
                                                  } 
                                                }

                                                echo $ago2;
                                            
                                            
                                              ?>
                                            </small>
                                          </div>
                                        </div>

                                          <?php } else { ?>

                                        <div class='pt-2'>
                                          <div class='text-gray'>Waiting for seller's response.</div>
                                        </div>

                                          <?php } ?>

                                      </div>
                                    
                                  

                                    <?php } } ?>
                                    
                              </div>
                            </div>

                            <!-- ASK A QUESTION -->
                            <?php 
                              if(isset($_SESSION['id'])) {
                                
                            ?>
                            <div class="row my-5">
                              <div class="col-12 px-0">
                                <form action='process_ask_about_product' method='POST'>
                                  <div class="form">
                                    <div class="form-group px-0">
                                      <label for="post_question">
                                        <h4>Ask A Question</h4>
                                      </label>
                                      <textarea class="form-control border-0" id="product_question" style='width:100%;background:#eff0f5;' rows='3'></textarea>
                                    </div>
                                  </div>

                                  <div class="d-flex flex-row">
                                    <a class="btn btn-purple" data-userid='<?=$userId?>' data-productid='<?=$id?>'role='button' id='btn_ask_question'>
                                      Send
                                      <i class="far fa-paper-plane"></i>
                                    </a>
                                    <small id='post_question_notification' class='text-red ml-4 pt-1'></small>
                                  </div>
                                </form>
                              </div>
                            </div>
                            <?php } ?>
                            
                          </div>

                        </div>
                      </div>
                    </div> 

                    <!-- PRODUCT REVIEWS -->
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
                                      <?= number_format((float)$averageRating, 1, '.', '')?>
                                    </h1>
                                    <h3 class='text-gray pt-5'>
                                      &nbsp;/&nbsp;5
                                    </h3>
                                
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
                                        <?php } else { ?>
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
                          
                            <?php } ?>
                              
                          </div>

                          <div class="col-lg-3"></div>
                          
                        
                        </div>

                        <!-- FUNNEL -->
                        <?php 
                          if($totalProductRating > 0) {
                        ?>
                        <div class="row">
                          <div class="col-lg-9 col-md-8  text-left">
                            <h4 class='py-1'>Comments</h4>
                          </div>
                          <div class="col text-right">
                            <div>
                              <div class="flex-fill input-group" id='funnel_dropdown'>
                                <div class="input-group-prepend p-2">
                                  <div class="d-flex align-items-center">
                                    <i class="fas fa-filter text-secondary"></i>
                                    &nbsp;Filter:
                                  </div>
                                </div>

                                <select class="custom-select border-0 pt-2" id="sort_ratings" onchange="sort_ratings" data-id='<?= $id ?>' data-storeid='<?=$storeId?>'>
                                  <option value="6" selected>All stars</option>
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
                              <div class="row pb-4">
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

                                    <img src="<?= BASE_URL . "/" .$reviewImageUrl?>" 
                                        alt="review_image" style='width:70px;max-height:80px;cursor: zoom-in;' 
                                        class='review_thumbnail mr-2' 
                                        data-id='<?=$reviewImageId?>' data-clientid='<?=$clientId?>'
                                        data-url='<?= BASE_URL . "/" .$reviewImageUrl ?>'>

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
                                    <a href="store-profile.php?id=<?=$storeId ?>"></a>
                                    <img src="<?= BASE_URL . '/' . $storeLogo . '.jpg' ?>" alt="<?=$storeName?>" style='width:30px;max-height:30px;' class='circle'>
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
                          <?php } } ?>
                        </div>
                        

                      </div>
                      <!-- /CONTAINER -->
                      
                    </div>
                   
                    
                    


                  </div>
                </div>

              </div>
            </div>
          </div>

        </div>
      </div>
   


      
      <!-- PRODUCTS FROM SELLER -->
      <div class="container mb-5" style="width:100%;">
        <div class="row">
          <div class="col">
            <h4>
              SIMILAR PRODUCTS
            </h4>
          </div>
        </div>
        <div class="row no-gutters justify-content-left">
            <?php 
              $productId = $_GET['id'];
              $sql2 = "SELECT * FROM tbl_items WHERE id != ? AND category_id = ? LIMIT 12 ";
              $statement2 = $conn->prepare($sql2);
              $statement2->execute([$productId,$categoryId]);

              if($statement2->rowCount()){
                while($row2 = $statement2->fetch()){
                  $id = $row2['id'];
                  $name = $row2['name'];
                  $price = $row2['price'];
                  $price = number_format((float)$price, 2, '.', '');
                  $description = $row2['description'];
                  $logo = productprofile($conn,$id);
                  $logo = BASE_URL. "/".$logo.".jpg";
            ?>
          
          <div class="col-lg-2 col-md-3 col-sm-6 pr-1 pb-1">
            <a href="product.php?id=<?= $id ?>">
              <div class='card h-700 border-0'>
                <a href="product.php?id=<?= $row2['id'] ?>">
                  <img class='card-img-top' src="<?= $logo ?>" style='height:250px;'>

                  <div class="card-body pr-0">
                    <div>
                      <?= $name ?>
                    </div>
                    <div>&#36;
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
  
  
      <?php 
      
      //CHECK IF CLIENT IS A SELLER AND THE OWNER OF THE PAGE 
      if(isset($_SESSION['id'])){
        if ($isSeller && $currentUser['id'] == $sellerId) {
          echo "";
        } else { 
          require_once '../partials/message_box.php'; 
        }
      } 
      
    ?> 


  
</body>
</html> 

<?php require_once "../partials/footer.php";?>
<?php require_once "../partials/modal_container.php"; ?>
<?php require_once "../partials/modal_container_big.php"; ?>
