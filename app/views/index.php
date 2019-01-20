<?php require_once "../../config.php";?>
<?php require_once BASE_DIR . "/app/partials/header.php";?>
<?php // require_once "../controllers/connect.php"; ?>

    <!-- ========== PAGE CONTENT =========== -->

    <!-- BANNER 1 -->
    <div class="container-fluid">
      <div class="row">
        <div class="col-12 px-0 mx-0" id='banner_ad-1'>
        </div>
      </div>
    </div>

    


    <?php require_once "../partials/categories.php"; ?>

    
    <!-- COMPANY DESCRIPTIONS -->
    <div class="container my-5 py-3">
      <div class="row my-lg-5 mx-0 text-center">
        <div class="col d-flex flex-lg-row flex-md-row flex-sm-column p-4 rounded shadow" style='background:white;'>

          <div class="flex-fill py-3">
            <div class="d-flex justify-content-center">
              <div id='description-quality'>
              </div>
            </div>

            <div class="d-flex flex-column pt-3">
              <div class='description-header'>
                100% Mom-Approved
              </div>
              <small class="description-supporting-text">
                Guaranteed Safe Products
              </small>
            </div>
          </div>


          <div class="flex-fill py-3">
            <div class="d-flex justify-content-center">
              <div id='description-save'>
              </div>
            </div>

            <div class="d-flex flex-column pt-3">
              <div class='description-header'>
                Lowest Prices
              </div>
              <small class="description-supporting-text">
                Shop & Save Money
              </small>
            </div>
          </div>


          <div class="flex-fill py-3">
            <div class="d-flex justify-content-center">
              <div id='description-shipping'>
              </div>
            </div>

            <div class="d-flex flex-column pt-3">
              <div class='description-header'>
                Free Delivery Available
              </div>
              <small class="description-supporting-text">
                Minimum Spend Required
              </small>
            </div>
          </div>


          <div class="flex-fill py-3">
            <div class="d-flex justify-content-center">
              <div id='description-hours'>
              </div>
            </div>

            <div class="d-flex flex-column pt-3">
              <div class='description-header'>
                Same Day Shipping
              </div>
              <small class="description-supporting-text">
                Get Your Order in 24 Hours
              </small>
            </div>
          </div>

          
          

        </div>
      </div>
    </div>

 
    
    <!-- FEATURED SHOPS -->
    <div class="container my-5">

      <div class="row">
        <div class="col-lg-6 col-md-6 col-sm-8">
            <h5>
              FEATURED SHOPS
            </h5>
        </div>
        <div class="col-lg-6 col-md-6 col-sm-4 text-right pt-2">
          <a href="catalog.php?id=<?=$_GET['id'] = 41 ?>">
            View All&nbsp;<i class="fas fa-angle-double-right"></i>
          </a>
        </div>
      </div>

      <div class="row no-gutters">
        <?php 

          $sql = " SELECT * FROM tbl_stores WHERE with_permit = 2 LIMIT 6 ";
          $statement = $conn->prepare($sql);
          $statement->execute();
          //$result = mysqli_query($conn,$sql);

          //CHECK IF THERE'S DATA
          if($statement->rowCount()){
            //CREATE A ROW VARIABLE
            while($row = $statement->fetch()){
              $id = $row['id'];
              $name = $row['name'];
              $logo = $row['logo'];
          ?>
          <div class="col-lg-2 col-md-2 col-sm-4">
            <div class = 'card h-700 border-0'> 
              <div class='card border' style='height:auto;'>
                <a href="store-profile.php?id=<?= $row['id'] ?>">
                  <img class='card-img-top' src='<?= BASE_URL ."/".$logo .".jpg" ?>'>
                </a> 
              </div>
            </div>
          </div>
              
        <?php } } ?>
      </div>
    </div>

    <br class='vanish-sm'>

   
    <!-- CART ITEMS THAT WERE NOT PURCHASED, ORDERED BY THE NUMBER OF TIMES THEY WERE PUT IN CART -->
    <?php if(isset($_SESSION['id'])){ 
      
      $sql = "SELECT c.variation_id, i.id, 
        COUNT(i.id) 
        AS numberOfTimesInCart, i.name, i.price, pi.url 
        FROM tbl_carts c 
        JOIN tbl_variations v 
        ON c.variation_id=v.id 
        JOIN tbl_items i 
        ON v.product_id=i.id 
        JOIN tbl_product_images pi 
        ON pi.product_id=i.id  
        WHERE status_id IS NULL 
        AND user_id =? 
        AND is_primary = 1 
        GROUP BY i.id 
        ORDER BY numberOfTimesInCart 
        DESC 
        LIMIT 4";
        $statement = $conn->prepare($sql);
        $statement->execute([$_SESSION['id']]);
        //$result = mysqli_query($conn,$sql);

      //CHECK IF THERE'S DATA
      if($statement->rowCount()){
    ?>
    <div class="container mb-5">

      <div class="row">
        <div class="col-lg-6 col-md-6 col-sm-8 pr-0">
            <h5>
              FOR YOU
            </h5>
        </div>
        <div class="col-lg-6 col-md-6 col-sm-4 text-right pt-2">
          <a href="catalog.php?id=<?=$_GET['id'] = 41 ?>">
            View All&nbsp;<i class="fas fa-angle-double-right"></i>
          </a>
        </div>
      </div>

      <div class="row no-gutters">
        <?php 
            //CREATE A ROW VARIABLE
            while($row = $statement->fetch()){
              $productId = $row['id'];
              $name = $row['name'];
              $price = $row['price'];
              $logo = $row['url'];
              $logo = BASE_URL . "/".$logo .".jpg";
          ?>
          <div class="col-lg-3 col-md-4 col-sm-6 pb-2 px-1">
            <a href="product.php?id=<?= $productId ?>">
              <div class = 'card h-700 border-0'>
                <!-- IMGS WON'T RESPOND TO CSS TARGETING ... -->
                <img class='card-img-top card-profilepic' src="<?= $logo ?>"> 
                <div class="card-body pr-0">
                  <div class='font-weight-bold'>
                    <?= $name ?>
                  </div>
                  <div>&#36; <?= $price ?> </div>

                  <div class='d-flex flex-row mt-3'>
                 
                    <!-- WISHLIST BUTTONS -->
                    <div class='flex-fill' style='cursor:default;'>

                      <?php 
                        $wishCount = getProductWishlishtCount($conn,$productId);
                        if(isset($_SESSION['id'])) {
                            if (checkIfInWishlist($conn,$id)) {
                      ?>
                        <a class='heart-toggler' data-id='<?= $productId ?>' role='button' data-enabled="0" style='float:left'>
                          <span class='wish_heart'><i class='fas fa-heart text-purple' id></i></span>
                          <span class='product_wish_count'>
                            <small>
                              <?= $wishCount ?>
                            </small>
                          </span>
                        </a>
                  
                      <?php  } else { ?>

                        <a class='heart-toggler' data-id='<?= $productId ?>' data-enabled="1" style='float:left'>
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
                        
                        <a class='btn_wishlist_logout_view' data-id='<?= $productId ?>' disabled style='cursor:default; float:left'>
                          <i class='far fa-heart text-purple'></i> 
                          <span class='product_wish_count'>
                            <small>
                              <?= $wishCount ?>
                            </small>
                          </span>
                        </a>
                        
                      <?php } else { ?>
                        <a class='btn_wishlist_logout_view' data-id='<?= $productId ?>' disabled style='cursor:default; float:left'>
                          <i class='far fa-heart text-gray'></i> 
                        </a>
                        
                      <?php } } ?>
                    </div>

                    <!-- AVERAGE STAR RATING -->
                    <div class='flex-fill pr-3' style="display:flex; flex-direction: column; width:81%; align-items:flex-end">  
                      <div class='stars-outer' 
                        data-productrating='<?=getAveProductReview($conn, $productId)?>' 
                        data-productid='<?=$productId?>' 
                        id='average_product_stars2<?=$productId?>'>
                        <span class='stars-inner'></span>
                      </div>
                    </div>
                    <!-- /AVERAGE STAR RATING -->
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
    <?php } } ?>
   
    <br class='vanish-sm'>
    <!-- TRENDING PRODUCTS: SHOW TRENDING PRODUCTS BASED ON THE NUMBER OF TIMES PEOPLE ADDED THEM TO THEIR CART AND PROCEEDED TO CHECKOUT -->
    <div class="container mb-5">

      <div class="row">
        <div class="col-lg-6 col-md-6 col-sm-8 pr-0">
            <h5>
              TRENDING PRODUCTS
            </h5>
        </div>
        <div class="col-lg-6 col-md-6 col-sm-4 text-right pt-2">
          <a href="catalog.php?id=<?=$_GET['id'] = 41 ?>">
            View All&nbsp;<i class="fas fa-angle-double-right"></i>
          </a>
        </div>
      </div>

      <div class="row no-gutters">
        <?php 

          $sql = " SELECT COUNT(variation_id) 
                  AS 'popularity', c.*,v.product_id,i.name,i.price, pi.url, pi.is_primary 
                  FROM tbl_carts c 
                  JOIN tbl_items i 
                  JOIN tbl_variations v 
                  JOIN tbl_product_images pi 
                  ON c.variation_id=v.id 
                  AND v.product_id=i.id 
                  AND pi.product_id=i.id 
                  WHERE status_id IS NOT NULL 
                  AND is_primary = 1 
                  GROUP BY product_id 
                  ORDER BY COUNT(variation_id) 
                  DESC LIMIT 12";
          $statement = $conn->prepare($sql);
          $statement->execute();
          //$result = mysqli_query($conn,$sql);

          //CHECK IF THERE'S DATA
          if($statement->rowCount()){
            //CREATE A ROW VARIABLE
            while($row = $statement->fetch()){
              $productId = $row['product_id'];
              $name = $row['name'];
              $price = $row['price'];
              $logo = $row['url'];
              $logo = BASE_URL . "/".$logo .".jpg";
          ?>
          <div class="col-lg-3 col-md-4 col-sm-6 pb-2 px-1">
            <a href="product.php?id=<?= $productId ?>">
              <div class = 'card h-700 border-0'>
                <!-- IMGS WON'T RESPOND TO CSS TARGETING ... -->
                <img class='card-img-top card-profilepic' src="<?= $logo ?>"> 
                <!-- <img class='card-img-top card-img vanish-md vanish-sm' src="<?= $logo ?>" style='height:370px;'> 
                <img class='card-img-top card-img vanish-lg vanish-sm' src="<?= $logo ?>" style='height:320px;'> 
                <img class='card-img-top card-img vanish-lg vanish-md' src="<?= $logo ?>" style='height:230px;'>  -->
                <div class="card-body pr-lg-0 pr-md-0 p-sm-2">
                  <div class='font-weight-bold'>
                    <?= $name ?>
                  </div>
                  <div>&#36; <?= $price ?> </div>

                  <div class='d-flex flex-row mt-3'>
                 
                    <!-- WISHLIST BUTTONS -->
                    <div class='flex-fill vanish-sm' style='cursor:default;'>

                      <?php 
                        $wishCount = getProductWishlishtCount($conn,$productId);
                        if(isset($_SESSION['id'])) {
                            if (checkIfInWishlist($conn,$id)) {
                      ?>
                        <a class='heart-toggler' data-id='<?= $productId ?>' role='button' data-enabled="0" style='float:left'>
                          <span class='wish_heart'><i class='fas fa-heart text-purple' id></i></span>
                          <span class='product_wish_count'>
                            <small>
                              <?= $wishCount ?>
                            </small>
                          </span>
                        </a>
                  
                      <?php  } else { ?>

                        <a class='heart-toggler' data-id='<?= $productId ?>' data-enabled="1" style='float:left'>
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
                        
                        <a class='btn_wishlist_logout_view' data-id='<?= $productId ?>' disabled style='cursor:default; float:left'>
                          <i class='far fa-heart text-purple'></i> 
                          <span class='product_wish_count'>
                            <small>
                              <?= $wishCount ?>
                            </small>
                          </span>
                        </a>
                        
                      <?php } else { ?>
                        <a class='btn_wishlist_logout_view' data-id='<?= $productId ?>' disabled style='cursor:default; float:left'>
                          <i class='far fa-heart text-gray'></i> 
                        </a>
                        
                      <?php } } ?>
                    </div>

                    <!-- WISHLIST BUTTONS -->
                    <div class='flex vanish-lg vanish-md' style='cursor:default;'>

                      <?php 
                        $wishCount = getProductWishlishtCount($conn,$productId);
                        if(isset($_SESSION['id'])) {
                            if (checkIfInWishlist($conn,$id)) {
                      ?>
                        <a class='heart-toggler' data-id='<?= $productId ?>' role='button' data-enabled="0" style='float:left'>
                          <span class='wish_heart'><i class='fas fa-heart text-purple' id></i></span>
                          <span class='product_wish_count'>
                            <small>
                              <?= $wishCount ?>
                            </small>
                          </span>
                        </a>
                  
                      <?php  } else { ?>

                        <a class='heart-toggler' data-id='<?= $productId ?>' data-enabled="1" style='float:left'>
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
                        
                        <a class='btn_wishlist_logout_view' data-id='<?= $productId ?>' disabled style='cursor:default; float:left'>
                          <i class='far fa-heart text-purple'></i> 
                          <span class='product_wish_count'>
                            <small>
                              <?= $wishCount ?>
                            </small>
                          </span>
                        </a>
                        
                      <?php } else { ?>
                        <a class='btn_wishlist_logout_view' data-id='<?= $productId ?>' disabled style='cursor:default; float:left'>
                          <i class='far fa-heart text-gray'></i> 
                        </a>
                        
                      <?php } } ?>
                    </div>

                    <!-- AVERAGE STAR RATING -->
                    <div class='flex-fill pr-lg-3 pr-md-3 pr-sm-0' style="display:flex; flex-direction: column; width:14%; align-items:flex-end">  
                      <div class='stars-outer' 
                        data-productrating='<?=getAveProductReview($conn, $productId)?>' 
                        data-productid='<?=$productId?>' 
                        id='average_product_stars2<?=$productId?>'>
                        <span class='stars-inner'></span>
                      </div>
                    </div>
                    <!-- /AVERAGE STAR RATING -->
                  </div>

                </div>
                <!-- /.CARD BODY -->
              </div>
              <!-- /.CARD -->
            </a>
          </div>
              
        <?php } } ?>
      </div>

    </div>

    <!-- BUTTON -- LOAD MORE PRODUCTS -->
    <div class="container">
      <div class="row">
        <div class="col"></div>
        <div class="col-lg-4 col-md-6">
          <a href="catalog.php?id=<?=$_GET['id'] = 41 ?>" class="btn btn-lg btn-block border hover_btn">
            See More Products
          </a>
        </div>
        <div class="col"></div>
      </div>
    </div>
  

<?php require_once BASE_DIR . "/app/partials/footer.php";?>
<?php require_once BASE_DIR . "/app/partials/modal_container.php"; ?>
<?php require_once BASE_DIR . "/app/partials/modal_container_big.php"; ?>