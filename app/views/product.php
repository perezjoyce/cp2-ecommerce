<?php include "../partials/header.php";?>
<?php require_once "../controllers/connect.php";?>
<?php require_once "../controllers/functions.php";?>

<?php 
           
  $id = $_GET['id'];

  if(isset($_SESSION['id'])) {
    $userId = $_SESSION['id'];
  }

  $sql = "SELECT * FROM tbl_items WHERE id = $id";

  $result = mysqli_query($conn,$sql);
  $row = mysqli_fetch_assoc($result);

  $id = $row['id'];    
  $name = $row['name'];     
  $price = $row['price'];
  $description = $row['description'];
  $item_img = $row['img_path'];


  $sql = " SELECT * FROM tbl_carts WHERE cart_session='$cartSession' AND item_id=$id";
	$result = mysqli_query($conn, $sql);
  $count = mysqli_num_rows($result);

  
?>
    <!-- PAGE CONTENT -->
    <div class="container">

      <!-- PRODUCT OVERVIEW -->
      <div class="row pt-5 my-5">
      <input type="hidden" id="$id">
 
        <div class='container d-inline-flex'>
          <div class='d-flex flex-row mr-5'>
              <img src=' <?= $item_img ?> '>
          </div>
          
          <div class='d-flex flex-row'>
            <div class='d-flex flex-column'>
              <div class='card-title font-weight-bold'> <?= $name ?> </div>
              <!-- AVERAGE RATINGS AREA -->

              
              <div class="mb-5">
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
                    <i class='far fa-star' id='star4' data-productId='<?= $id ?>"' data-value='4'></i>
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
              <span>&nbsp;|&nbsp; </span>
              
                <!-- RATING COUNT OF PRODUCT -->
                  <?php 
                  if (isset($_SESSION['cart_session'])) { 
                    if (countRatingsPerProduct($conn, $id) === 0 || countRatingsPerProduct($conn, $id) == "") { ?>
                      <span class='rating-count<?=$id?>'>
                        <!-- no value -->
                      </span>
                      <span class='rating-word'>
                        <?= "No ratings yet" ?>
                      </span>
                    <?php } elseif(countRatingsPerProduct($conn, $id) == 1){?>
                      <span class='rating-count<?=$id?>'>
                        <?= countRatingsPerProduct($conn, $id) ?>
                      </span>
                      <span class='rating-word'>
                        <?= "Rating" ?>
                      </span>
                    <?php } else {?>
                      <span class='rating-count<?=$id?>'>
                        <?= countRatingsPerProduct($conn, $id) ?>
                      </span>
                      <span class='rating-word'>
                        <?= "Ratings" ?>
                      </span>
                    <?php } } ?>
              </div>

              <!-- /AVERAGE RATINGS AREA -->
              <div class='mb-5'>&#8369; <?= $price ?> </div>
              <div class='mb-5'> <?= $description ?> </div>
              
              <div class='d-flex flex-row'>

                <!-- ADD TO CART BUTTON -->
                <?php
                  if($count) {
                ?>
                  <button class='btn btn-outline-secondary mt-3 flex-fill mr-2' data-id='<?= $id ?>' role='button' id="btn_delete_from_cart" disabled>
                    <i class='fas fa-cart-plus'></i>
                      Item is already in your cart!
                  </button>
                <?php } else { ?>
                  <a class='btn btn-outline-primary mt-3 flex-fill mr-2' data-id='<?= $id ?>' role='button' id="btn_add_to_cart">
                    <i class='fas fa-cart-plus'></i>
                      Add to Cart
                  </a>
                <?php }?>

                
                <?php 
                  if(isset($_SESSION['id'])) {
                      if (checkIfInWishlist($conn,$id) == 0) {
                ?>
                  <a class='btn btn-outline-danger mt-3 flex-fill' data-id='<?= $id ?>' role='button' id="btn_add_to_wishlist">
                    <i class="far fa-heart"></i>
                    Add to Wish List
                  </a>
                 
                <!-- ADD TO WISHLIST BUTTON -->
                <?php  } else { ?>

                  <button class='btn btn-outline-secondary mt-3 flex-fill mr-2' data-id='<?= $id ?>' disabled id="btn_already_in_wishlist">
					          <i class='far fa-heart'></i> 
                    Item is already in your wishlist. 
                  </button>

                <?php }  } else { ?>
                  <!-- WISHLIST BUTTON NOT AVAILABLE FOR LOGGED OUT AND UNREGISTERED USERS -->
                <?php }  ?>

              </div>

            </div>
          </div>
        </div>

      </div>
      <!-- /.PRODUCT OVERVIEW -->
      
      <!-- REVIEW SECTION -->
      <div class="row mb-5">
        <!-- PRODUCT REVIEWS AREA -->
        <div class="col-lg-9 mr-5 border">
          <div class="row pt-3 pl-3">User's Product Rating:</div>
          <div class="row pt-3 pl-3 mb-3">
            <!-- STAR CONTAINER -->
            <!-- VISIBLE TO USER ONLY -->
            <?php 
              if (isset($_SESSION['id'])) {
                if ((displayUserRating($conn, $userId, $id) == 0)){
            ?>
                  <div id='star-container'>
                    <i class='far fa-star fa-2x star' id='star1' data-productId='<?= $id ?>' data-value='1'></i>
                    <i class='far fa-star fa-2x star' id='star2' data-productId='<?= $id ?>' data-value='2'></i>
                    <i class='far fa-star fa-2x star' id='star3' data-productId='<?= $id ?>' data-value='3'></i>
                    <i class='far fa-star fa-2x star' id='star4' data-productId='<?= $id ?>' data-value='4'></i>
                    <i class='far fa-star fa-2x star' id='star5' data-productId='<?= $id ?>' data-value='5'></i>
                  </div>

            <?php } elseif (((displayUserRating($conn, $userId, $id) == 1))) { ?>

                  <div id='star-container'>
                    <i class='fas fa-star fa-2x star' id='star1' data-productId='<?= $id ?>' data-value='1'></i>
                    <i class='far fa-star fa-2x star' id='star2' data-productId='<?= $id ?>' data-value='2'></i>
                    <i class='far fa-star fa-2x star' id='star3' data-productId='<?= $id ?>' data-value='3'></i>
                    <i class='far fa-star fa-2x star' id='star4' data-productId='<?= $id ?>' data-value='4'></i>
                    <i class='far fa-star fa-2x star' id='star5' data-productId='<?= $id ?>' data-value='5'></i>
                  </div>

            <?php } elseif (((displayUserRating($conn, $userId, $id) == 2))) { ?>

                  <div id='star-container'>
                    <i class='fas fa-star fa-2x star' id='star1' data-productId='<?= $id ?>' data-value='1'></i>
                    <i class='fas fa-star fa-2x star' id='star2' data-productId='<?= $id ?>' data-value='2'></i>
                    <i class='far fa-star fa-2x star' id='star3' data-productId='<?= $id ?>' data-value='3'></i>
                    <i class='far fa-star fa-2x star' id='star4' data-productId='<?= $id ?>' data-value='4'></i>
                    <i class='far fa-star fa-2x star' id='star5' data-productId='<?= $id ?>' data-value='5'></i>
                  </div>

            <?php } elseif (((displayUserRating($conn, $userId, $id) == 3))) { ?>

              <div id='star-container'>
                <i class='fas fa-star fa-2x star' id='star1' data-productId='<?= $id ?>' data-value='1'></i>
                <i class='fas fa-star fa-2x star' id='star2' data-productId='<?= $id ?>' data-value='2'></i>
                <i class='fas fa-star fa-2x star' id='star3' data-productId='<?= $id ?>' data-value='3'></i>
                <i class='far fa-star fa-2x star' id='star4' data-productId='<?= $id ?>' data-value='4'></i>
                <i class='far fa-star fa-2x star' id='star5' data-productId='<?= $id ?>' data-value='5'></i>
              </div>

            <?php } elseif (((displayUserRating($conn, $userId, $id) == 4))) { ?>

              <div id='star-container'>
                <i class='fas fa-star fa-2x star' id='star1' data-productId='<?= $id ?>' data-value='1'></i>
                <i class='fas fa-star fa-2x star' id='star2' data-productId='<?= $id ?>' data-value='2'></i>
                <i class='fas fa-star fa-2x star' id='star3' data-productId='<?= $id ?>' data-value='3'></i>
                <i class='fas fa-star fa-2x star' id='star4' data-productId='<?= $id ?>' data-value='4'></i>
                <i class='far fa-star fa-2x star' id='star5' data-productId='<?= $id ?>' data-value='5'></i>
              </div>

            <?php } else { ?>

              <div id='star-container'>
                <i class='fas fa-star fa-2x star' id='star1' data-productId='<?= $id ?>' data-value='1'></i>
                <i class='fas fa-star fa-2x star' id='star2' data-productId='<?= $id ?>' data-value='2'></i>
                <i class='fas fa-star fa-2x star' id='star3' data-productId='<?= $id ?>' data-value='3'></i>
                <i class='fas fa-star fa-2x star' id='star4' data-productId='<?= $id ?>' data-value='4'></i>
                <i class='fas fa-star fa-2x star' id='star5' data-productId='<?= $id ?>' data-value='5'></i>
              </div>

            <?php }  } ?>
            
            <!-- /.STAR CONTAINER -->
          </div>
          
          <!-- AVERAGE PRODUCT REVIEW (RATING) -->
          <div class="row pt-3 pl-3">Average Product Rating:</div>
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
                    <i class='far fa-star' id='star4' data-productId='<?= $id ?>"' data-value='4'></i>
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
          <div class='row pt-3 pl-3 mb-3'>
              <!-- TOTAL AVERAGE RATING -->
              <?php 
                if (isset($_SESSION['cart_session'])) { 
                  if (getAveProductReview($conn, $id) === 0 || getAveProductReview($conn, $id) == "") { ?>
                    <span class='rating-average<?=$id?>'>
                      <!-- no value -->
                    </span>
                    <span class='rating-word'>
                    <?= "&nbsp;No reviews yet" ?>
                    </span>
              <?php } else {?>
                    <span class='rating-average<?=$id?>' style='color:red;' >
                      <?= getAveProductReview($conn, $id) ?> 
                    </span>
                    <span>
                      <?= "&nbsp;of 5" ?>
                    </span>
            <?php } } ?>
          </div>

          <div class='row pt-3 pl-3'>
            <!-- TEXT REVIEW -->
            <form action="#" method='POST'>
              <!-- FOR REVISION COZ DOESN'T WORK IN SMALLER SCREENS -->
              <textarea style="auto;resize:none" rows="3" cols="134" id='product-review' data-productId='<?= $id ?>'></textarea>
              <br>
              <button href="#"> SUBMIT REVIEW </button>
            </form>
            <!-- /.TEXT REVIEW -->
          </div>

        </div>
        <!-- /.PRODUCT REVIEWS AREA -->

        <!-- ADS -->
        <div class="col">
          <!-- dynamically added and generated ads here -->
        </div>
        <!-- /.ADS -->


      </div>
      <!-- /.REVIEW SECTION -->

    </div>
    <!-- /.PAGE CONTENT -->

<!-- /FOOTER -->
<?php include_once "../partials/footer.php";?>

<!-- MODAL -->
<?php include_once "../partials/modal_container.php"; ?>