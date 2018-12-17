<?php require_once "../partials/header.php";?>
<?php require_once "../controllers/connect.php";?>

    <!-- ========== PAGE CONTENT =========== -->

    <!-- CATEGORIES -->
    <?php include_once "../partials/categories.php"; ?>

    <!-- ADVERTISEMENTS -->
    <div class="container mt-4">

      <div class="row mx-0">
        <div class="col-8 p-0">

          <div id="carouselExampleIndicators" class="carousel slide" data-ride="carousel">
            <ol class="carousel-indicators">
              <li data-target="#carouselExampleIndicators" data-slide-to="0" class="active"></li>
              <li data-target="#carouselExampleIndicators" data-slide-to="1"></li>
              <li data-target="#carouselExampleIndicators" data-slide-to="2"></li>
            </ol>
            <div class="carousel-inner">
              <div class="carousel-item active">
                <img class="d-block w-100" src="https://via.placeholder.com/800x250" alt="First slide">
              </div>
              <div class="carousel-item">
                <img class="d-block w-100" src="https://via.placeholder.com/800x250" alt="Second slide">
              </div>
              <div class="carousel-item">
                <img class="d-block w-100" src="https://via.placeholder.com/800x250" alt="Third slide">
              </div>
            </div>
            <a class="carousel-control-prev" href="#carouselExampleIndicators" role="button" data-slide="prev">
              <span class="carousel-control-prev-icon" aria-hidden="true"></span>
              <span class="sr-only">Previous</span>
            </a>
            <a class="carousel-control-next" href="#carouselExampleIndicators" role="button" data-slide="next">
              <span class="carousel-control-next-icon" aria-hidden="true"></span>
              <span class="sr-only">Next</span>
            </a>
          </div>

        </div>

        <div class="col ml-1">
          <div class="row mb-1">
            <div id="index-advertisement-top">
                <img class="d-block w-100" src="https://via.placeholder.com/800x250">
            </div>
          </div>

          <div class="row">
            <div id="index-advertisement-bottom">
                <img class="d-block w-100" src="https://via.placeholder.com/800x250">
            </div>
          </div>
        </div>
      </div>
    </div>
    
    <!-- COMPANY DESCRIPTIONS -->
    <div class="container mb-3">
      <div class="row py-4 mx-0">
        <div class="col-4">
          <div class="d-flex flex-row">
            <div id='description-quality' class='mr-3 ml-5'>
            </div>
            <div class=' mt-2'>
              <div class="d-flex flex-column align-items-stretch">
                <div class="heading mb-0 pb-0">
                  <h2>100% Safe</h2>
                </div>
                <div class="description">
                  Guaranteed Mom-Approved Products
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="col-4">
          <div class="d-flex flex-row">
            <div id='description-save' class='mr-3 ml-5'>
            </div>
            <div class=' mt-2'>
              <div class="d-flex flex-column align-items-stretch">
                <div class="heading mb-0 pb-0">
                  <h2>Lowest Prices</h2>
                </div>
                <div class="description">
                  Shop Here & Save Money 
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="col-4">
          <div class="d-flex flex-row">
            <div id='description-shipping' class='mr-3'>
            </div>
            <div class=' mt-2'>
              <div class="d-flex flex-column align-items-stretch">
                <div class="heading mb-0 pb-0">
                  <h2>Free Delivery & COD</h2>
                </div>
                <div class="description">
                  Get Your Items in 24-Hours!
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>       
    </div>

    <!-- VIEWED PRODUCTS, WISHLIST ITEMS AND CART ITEMS THAT WERE NOT PURCHASED, IF LOGGED IN OR SESSION IS NOT DESTROYED -->
    
    <!-- FEATURED SHOPS -->
    <div class="container mb-5">
      <div class="row">
        <div class="col-6">
            <h2>
              FEATURED SHOPS
            </h2>
        </div>
        <div class="col-6 text-right pt-2">View All&nbsp;<i class="fas fa-angle-double-right"></i></i></div>
      </div>
      <div class="row no-gutters">
        <?php 

          $sql = " SELECT * FROM tbl_items LIMIT 12 ";
          $statement = $conn->prepare($sql);
          $statement->execute();
          //$result = mysqli_query($conn,$sql);

          //CHECK IF THERE'S DATA
          if($statement->rowCount()){
            //CREATE A ROW VARIABLE
            while($row = $statement->fetch()){
              $id = $row['id'];
              $name = $row['name'];
              $price = $row['price'];
              $description = $row['description'];
              $item_img = $row['img_path'];
          ?>
          <div class="col-lg-2 col-md-4 col-sm-6">
            <a href="product.php?id=<?= $id ?>">
              <div class = 'card'>
                <a href="store.php?id=<?= $row['id'] ?>">
                  <img class='card-img-top' src="<?= $item_img ?>">
                </a> 
              </div>
            </a>
          </div>
              
        <?php }} ?>

      </div>
    </div>

    <!-- JUST FOR YOU/ RECENT SEARCHES/ -->
    <div class="container mb-5">
      <div class="row">
        <div class="col-6">
            <h2>
              TRENDING PRODUCTS
            </h2>
        </div>
        <div class="col-6 text-right pt-2">View All&nbsp;<i class="fas fa-angle-double-right"></i></i></div>
      </div>
      <div class="row no-gutters">
        <?php 

          $sql = " SELECT * FROM tbl_items LIMIT 24";
          $statement = $conn->prepare($sql);
          $statement->execute();
          //$result = mysqli_query($conn,$sql);

          //CHECK IF THERE'S DATA
          if($statement->rowCount()){
            //CREATE A ROW VARIABLE
            while($row = $statement->fetch()){
              $id = $row['id'];
              $name = $row['name'];
              $price = $row['price'];
              $description = $row['description'];
              $item_img = $row['img_path'];
          ?>
          <div class="col-lg-2 col-md-4 col-sm-6 px-1 pb-2">
            <a href="product.php?id=<?= $id ?>">
              <div class = 'card h-700'>
                <img class='card-img-top' src="<?= $item_img ?>" > 
                <div class="card-body">
                  <div class='font-weight-bold'>
                    <?= $name ?>
                  </div>
                  <div>&#8369; <?= $price ?> </div>

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
                      if (countRatingsPerProduct($conn, $id) == 0) {
                          echo "<span class='rating-count<?=$id?>'></span>";
                      } else { 
                          echo "&#40;<span class='rating-count<?=$id?>'>" . 
                          countRatingsPerProduct($conn, $id) .
                          "&#41;</span>";
                      } ?>
                    </div>
                    <!-- /AVERAGE STAR RATING -->
                  </div>

                </div>
                <!-- /.CARD BODY -->
              </div>
              <!-- /.CARD -->
            </a>
          </div>
              
        <?php }} ?>
      </div>
    </div>

    <!-- BUTTON -- LOAD MORE PRODUCTS -->
    <div class="container">
      <div class="row">
        <div class="col-4"></div>
        <div class="col-4">
          <button type='button' href="catalog.php" class="btn btn-lg btn-block hover_btn">See More Products</button>
        </div>
        <div class="col-4"></div>
      </div>
    </div>
  

<?php require_once "../partials/footer.php";?>
<?php require_once "../partials/modal_container.php"; ?>