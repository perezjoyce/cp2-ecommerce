<?php require_once "../partials/header.php";?>
<?php include_once "../partials/categories.php"; ?>
<?php require_once "../controllers/connect.php";?>



<?php 
$id = $_GET['id'];

if(isset($_SESSION['id'])) {
  $userId = $_SESSION['id'];
}

?>

<div class="container my-5">
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
            
          <div class="card h700">
              
            <img src='<?=$url?>' style='width:100%;max-height:90px;' class='product_thumbnail' data-id='<?=$img_id?>' data-url='<?=$url?>'>
            
          </div>

              <?php 
              endwhile;
              ?>

        </div>
        <!-- IFRAME -->
        <div class="col position-relative" id="product_iframe">
          <img src='<?=$url?>' style='width:100%;height:65vh;' iframe_img_id='<?= $img_id ?>'>
        </div>
      </div>

      <!-- <div class="row border mt-3"> -->
      
        <?php

        $sql = " SELECT * FROM tbl_carts WHERE cart_session=? AND item_id=?";
        //$result = mysqli_query($conn, $sql);
        $statement = $conn->prepare($sql);
        $statement->execute([$cartSession, $id]);
        $count = $statement->rowCount();

        ?>

      <!-- <div class="d-flex flex-row offset-2 pl-1"> -->
      <div class="d-flex flex-row">
        <!-- ADD TO CART BUTTON -->
        <?php
          if($count) {
        ?>
          <button class='btn btn-lg btn-purple py-3' style="width:50%;" data-id='<?= $id ?>' role='button' id="btn_delete_from_cart" disabled>
            <i class='fas fa-cart-plus'></i>
              &nbsp;Item is in bag
          </button>
        <?php } else { ?>
          <a class='btn btn-lg btn-purple py-3' style="width:50%;" data-id='<?= $id ?>' role='button' id="btn_add_to_cart">
            <i class='fas fa-cart-plus'></i>
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
      

      <!-- </div> -->
    </div>
    <!-- /FIRST COLUMN (PICS) -->

    <!-- SECOND COLUMN (PRODUCT DETAILS) -->
    <div class="col border" style='overflow-y: scroll;'>
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
          $stocks = $row['stocks'];
        }
        ?>
      
      <!-- PRODUCT NAME -->
      <div class="row">
        <h1 class='font-weight-bold'><?= $name?></h1>
      </div>
        
      <!-- PRODUCT RATING -->
     
      <div class="row">
        <?
        $sql = "SELECT AVG(product_rating) as averageProductRating FROM tbl_ratings WHERE product_id = ?";
        $statement = $conn->prepare($sql);
        $statement->execute([$id]);
        $row = $statement->fetch();
        $averageRating = $row['averageProductRating'];
        ?>
        <input type="hidden" value='<?=  $averageRating ?>'>
        <input type="hidden" value='<?= ($averageRating/5)*100 ?>' id='average_product_rating'>
        <div id='average_product_rating_in_stars'>

        </div>

      </div>
     
    </div>
    <!-- /PRODUCT DETAILS -->
  </div>

</div>



    
    

<?php require_once "../partials/footer.php";?>
<?php require_once "../partials/modal_container.php"; ?>
