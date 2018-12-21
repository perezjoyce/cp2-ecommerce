<?php require_once "../partials/header.php";?>
<?php require_once "../controllers/connect.php";?>
<?php require_once "../controllers/functions.php";?>
<?php include_once "../partials/categories.php"; ?>


<!-- THUMBNAILS AT THE SIDE -->

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
        $origin = $_SERVER['HTTP_REFERER'];
        displayBreadcrumbs($conn, $id, $origin); 
      
      ?>
    </div>
</div>
</div>

<!-- PRODUCT PAGE MAIN CONTAINER -->
<div class="container mb-5">
  <div class="row" style='height: 80vh;'>

   
    <!-- FIRST COLUMN (PICS) -->
    <div class="col-lg-6 col-md-7 col-sm-12">
      <input type="hidden" id='iframeId'>
      <div class="row mb-3 no-gutters">
        <!-- THUMBNAILS -->
        <div class="col-2 mr-3" style="overflow-y:scroll;height:65vh;" >

              <?php 
              $sql = "SELECT * FROM tbl_product_images WHERE product_id = ?";
              $statement = $conn->prepare($sql);
              $statement->execute([$id]);	
              while($row = $statement->fetch()):
                $url = $row['url'];
                $img_id =$row['id'];

              ?>
            
          <div class="card h700" style='border:none;'>
              
            <img src='<?=$url?>' style='width:100%;max-height:100px;' class='product_thumbnail pb-3' data-id='<?=$img_id?>' data-url='<?=$url?>'>
            
          </div>

              <?php 
              endwhile;
              ?>

        </div>
        <!-- IFRAME -->
        <div class="col" id="product_iframe">
          <img src='<?=$url?>' style='width:100%;height:65vh;' iframe_img_id='<?= $img_id ?>'>
        </div>
      </div>


    </div>
    <!-- /FIRST COLUMN (PICS) -->



    <!-- SECOND COLUMN (PRODUCT DETAILS) -->
    <div class="col">
    <!-- <div class="col" style='overflow-y: scroll;'> -->
        <?php
        if(isset($_SESSION['id'])) {
          $userId = $_SESSION['id'];

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
        }
        ?>
      
      <!-- PRODUCT NAME -->
      <div class="row pl-4 mb-2">
        <h1><?= ucwords($name)?></h1>
      </div>
      
        
      <!-- PRODUCT RATING -->
      <div class="row pl-4 mb-3">
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
      <div class="row pl-4 mb-3">
        <?php

          $sql = "SELECT * FROM tbl_brands WHERE id = ?";
          $statement = $conn->prepare($sql);
          $statement->execute([$brandId]);
          $row = $statement->fetch();
          $brandName = $row['brand_name'];
        ?>
        <span class='text-gray'>Brand:&nbsp;</span>
        <span><?=$brandName?></span>
      </div>

      <hr class='my-4'>

      <!-- PRICE -->
      <div class="row pl-4 mb-5">
        <h1 class='font-weight-bold text-purple'>&#8369;&nbsp;<?= $price?></h1>
      </div>

      <!-- BUTTONS -->
      <div class="d-flex flex-row">
        <?php
          $sql = " SELECT * FROM tbl_carts WHERE cart_session=? AND item_id=?";
          //$result = mysqli_query($conn, $sql);
          $statement = $conn->prepare($sql);
          $statement->execute([$cartSession, $id]);
          $count = $statement->rowCount();
        
          if($count) {
        ?>
        <button class='btn btn-lg btn-purple py-3' style="width:50%;" data-id='<?= $id ?>' role='button' id="btn_delete_from_cart" disabled>
          <i class='fas fa-shopping-bag'></i>
            &nbsp;Item is in bag
        </button>
        <?php } else { ?>
        <a class='btn btn-lg btn-purple py-3' style="width:50%;" data-id='<?= $id ?>' role='button' id="btn_add_to_cart">
          <i class='fas fa-shopping-bag'></i>  
          &nbsp;Add to Shopping Bag
        </a>
        <?php }?>


        <?php 
          if(isset($_SESSION['id'])) {
              if (checkIfInWishlist($conn,$id) == 0) {
        ?>
        <a class='btn btn-lg btn-red py-3 ml-2' style="width:50%;" data-id='<?= $id ?>' role='button' id="btn_add_to_wishlist">
          <i class="far fa-heart"></i>
          &nbsp;Add to Wish List
        </a>

        <!-- ADD TO WISHLIST BUTTON -->
        <?php  } else { ?>

        <button class='btn btn-lg btn-red py-3 ml-2' style="width:50%;" data-id='<?= $id ?>' disabled id="btn_already_in_wishlist">
          <i class='far fa-heart'></i> 
          &nbsp;Item is in wishlist 
        </button>

        <?php }  } else { ?>
          <!-- WISHLIST BUTTON NOT AVAILABLE FOR LOGGED OUT AND UNREGISTERED USERS -->
        <?php }  ?>
      </div>







    </div>
    <!-- /PRODUCT DETAILS -->

    
    
  </div>

</div>



    
    

<?php require_once "../partials/footer.php";?>
<?php require_once "../partials/modal_container.php"; ?>
