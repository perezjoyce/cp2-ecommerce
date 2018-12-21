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
<div class="container mb-5">

  <!-- FIRST ROW -->
  <div class="row">

    <!-- FIRST COLUMN (PICS) -->
    <div class="col-lg-4 col-md-7 col-sm-12 mb-4">
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
            <img src='<?=$url?>' style='width:50px;max-height:50px;' class='product_thumbnail' data-id='<?=$img_id?>' data-url='<?=$url?>'>
          </div>
        </div>

        <?php endwhile;?>
      </div>
    </div>
    <!-- /FIRST COLUMN (PICS) -->





    <!-- SECOND COLUMN (PRODUCT DETAILS) -->
    <div class="col pr-5">
    <!-- <div class="col" style='overflow-y: scroll;'> -->
        <?php
       
          $sql = "SELECT * FROM tbl_items WHERE id = ?";
          $statement = $conn->prepare($sql);
          $statement->execute([$id]);
          $row = $statement->fetch();
  
          $name = $row['name'];     
          $price = $row['price'];
          $description = $row['description'];
          $item_img = $row['img_path'];
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
        $averageRating = ($averageRating/5)*100;
        $averageRating = round($averageRating, 1);
        ?>

        <!-- FOR DEBUGGING PURPOSES -->
        <input type="hidden" value='<?= $averageRating ?>' id='average_product_rating'>
        
        <!-- AVE PRODUCT RATING AS STARS -->
        <!-- NUMBER OF REVIEWS -->
        <span id='average_product_rating_in_stars'>
        </span>
        <span>&nbsp;&nbsp;</span>
    
          <?php 
        if (isset($_SESSION['cart_session'])) { 
          if (countRatingsPerProduct($conn, $id) === 0 || countRatingsPerProduct($conn, $id) == "") { ?>
        <span class='rating-count<?=$id?>'>
          <!-- no value -->
        </span>
        <span class='rating-word'>
          <?= "&nbsp;No rating yet" ?>
        </span>
            <?php } elseif(countRatingsPerProduct($conn, $id) == 1){?>
        <span class='rating-count<?=$id?>'>
          <?= countRatingsPerProduct($conn, $id) ?>
        </span>
        <span class='rating-word'>
          <?= "&nbsp;Rating" ?>
        </span>
            <?php } else {?>
        <span class='rating-count<?=$id?>'>
          <?= countRatingsPerProduct($conn, $id) ?>
        </span>
        <span class='rating-word'>
          <?= "&nbsp;Ratings" ?>
        </span>
            <?php } } ?>
          
      </div>
      
      <!-- BRAND -->
      <!-- <div class="row pl-4 mb-3">
        <?php

          $sql = "SELECT * FROM tbl_brands WHERE id = ?";
          $statement = $conn->prepare($sql);
          $statement->execute([$brandId]);
          $row = $statement->fetch();
          $brandName = $row['brand_name'];
        ?>
        <span class='text-gray'>Brand:&nbsp;</span>
        <span><?=$brandName?></span>
      </div> -->


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

      <!-- VARIATION -->
      <div class="row mb-5">
        <div class="col-3">
          <div class='pt-3'>Variation</div>
        </div>
        <div class="col">
          <div class="row">
            <?php
            
              $sql = "SELECT * FROM tbl_variations WHERE product_id = ?;";
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
      <div class="d-flex flex-row">
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
    <div class="col-2">

      <div class='row border mb-4'>
        <div class="col-12 py-4">
            <?php
              $sql = "SELECT * FROM tbl_stores WHERE id = ?";
              $statement = $conn->prepare($sql);
              $statement->execute([$storeId]);	
              $row = $statement->fetch();
              $storeName = $row['name'];
              $storeLogo = $row['logo'];
              $storeAddress = $row['store_address'];
              $sellerId = $row['user_id'];
            ?>
          
          <!-- STORE LOG -->
          <div class="flex-fill row justify-content-center mb-3">
            <img src="<?=$storeLogo?>" alt="<?=$storeName?>" style='width:80px;max-height:80px;' class='circle'>
          </div>

          <!-- STORE NAME -->
          <div class="flex-fill row justify-content-center text-purple font-weight-bold mb-3">
            <?=$storeName?>
          </div>

          <!-- STORE ADDRESS -->
          <div class="flex-fill row justify-content-center text-gray mb-2">
            <small>
              <i class="fas fa-map-marker-alt"></i>
              &nbsp;<?= ucwords(strtolower($storeAddress)) ?>
            </small>
          </div>

          <!-- LAST LOGIN -->
          <div class="flex-fill row justify-content-center text-gray">
            <?
              $sql = "SELECT last_login FROM tbl_users WHERE id = ?";
              $statement = $conn->prepare($sql);
              $statement->execute([$sellerId]);	
              $row = $statement->fetch();
              $lastLogin = $row['last_login'];
              $lastLog = new DateTime($lastLogin);

              date_default_timezone_set('Asia/Manila');
              $currentDate = new DateTime();
            ?>

            <small>
              <i class="fas fa-clock"></i>
              &nbsp;Active
              <?= $lastLog->diff($currentDate)->format("%d days, %h hrs & %i mins ago"); ?>
            </small>
          </div>

          <!-- MEMBER SINCE -->
          <!-- <div class="row justify-content-center text-gray mb-3">
            <?php
              $sql = "SELECT DATE_FORMAT(date_created, '%M %d, %Y') AS 'dateJoined' FROM tbl_stores WHERE id = ?";
              $statement = $conn->prepare($sql);
              $statement->execute([$storeId]);
              $row = $statement->fetch();
              $dateJoined = $row['dateJoined'];	
            ?>
            
              &nbsp;Joined&nbsp;<?=$dateJoined?>
          </div> -->

          <hr class='my-4'>

          <!-- STATS -->
          <div class="flex-fill row text-gray">
            <div class="col">

              <!-- RATINGS -->
              <div class="d-flex flex-row mb-3">
                <div style='width:45%;'>Ratings</div>
                <div>fds</div>
              </div>

              <!-- FOLLWERS -->
              <div class="d-flex flex-row mb-3">
                <div style='width:45%;'>Followers</div>
                <div>fds</div>
              </div>

              <!-- PRODUCTS -->
              <div class="d-flex flex-row mb-3">
                <div style='width:45%;'>Products</div>
                <div>fds</div>
              </div>

              <!-- JOINED -->
              <div class="d-flex flex-row mb-5">
                <div style='width:45%;'>Joined</div>
                <div>fds</div>
              </div>

            </div>
            
          </div>
          
          <!-- BUTTONS -->
          <div class="row">
            <a href='#' class='btn btn-block border text-purple mx-3 py-2'>
              <i class="far fa-comment"></i>
              &nbsp;Chat Seller
            </a>

            <a href='#' class='btn btn-block border text-secondary mx-3 py-2'>
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


  </div>
  <!-- END OF FIRST ROW -->

</div>

    
    

<?php require_once "../partials/footer.php";?>
<?php require_once "../partials/modal_container.php"; ?>
