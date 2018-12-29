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
            <!-- <a class="carousel-control-prev" href="#carouselExampleIndicators" role="button" data-slide="prev">
              <span class="carousel-control-prev-icon" aria-hidden="true"></span>
              <span class="sr-only">Previous</span>
            </a> -->
            <!-- <a class="carousel-control-next" href="#carouselExampleIndicators" role="button" data-slide="next">
              <span class="carousel-control-next-icon" aria-hidden="true"></span>
              <span class="sr-only">Next</span>
            </a> -->
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
    <div class="container mb-4">
      <div class="row mx-0 text-center">
        <div class="col d-flex flex-row">

          <div class="flex-fill py-3">
            <div class="d-flex justify-content-center">
              <div id='description-quality'>
              </div>
            </div>

            <div class="d-flex flex-column">
              <div class='description-header'>
                100% Mom-Approved
              </div>
              <div class="description-supporting-text">
                Guaranteed Safe Products
              </div>
            </div>
          </div>


          <div class="flex-fill py-3">
            <div class="d-flex justify-content-center">
              <div id='description-save'>
              </div>
            </div>

            <div class="d-flex flex-column">
              <div class='description-header'>
                Lowest Prices
              </div>
              <div class="description-supporting-text">
                Shop & Save Money
              </div>
            </div>
          </div>


          <div class="flex-fill py-3">
            <div class="d-flex justify-content-center">
              <div id='description-shipping'>
              </div>
            </div>

            <div class="d-flex flex-column">
              <div class='description-header'>
                Free Delivery & COD Available
              </div>
              <div class="description-supporting-text">
                Find Amazing Deals
              </div>
            </div>
          </div>


          <div class="flex-fill py-3">
            <div class="d-flex justify-content-center">
              <div id='description-hours'>
              </div>
            </div>

            <div class="d-flex flex-column">
              <div class='description-header'>
                Same Day Shipping
              </div>
              <div class="description-supporting-text">
                Get Your Order in 24 Hours
              </div>
            </div>
          </div>

          
          

        </div>
      </div>
    </div>

    <!-- VIEWED PRODUCTS, WISHLIST ITEMS AND CART ITEMS THAT WERE NOT PURCHASED, IF LOGGED IN OR SESSION IS NOT DESTROYED -->
    
    <!-- FEATURED SHOPS -->
    <div class="container mb-5 vanish-sm">
      <div class="row">
        <div class="col-6">
            <h4>
              FEATURED SHOPS
            </h4>
        </div>
        <div class="col-6 text-right pt-2">View All&nbsp;<i class="fas fa-angle-double-right"></i></i></div>
      </div>
      <div class="row no-gutters autoplay">
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
          <div class="col-lg-2 col-md-3 slick-item">
              <div class="row">
                <a href="product.php?id=<?= $id ?>">
                  <div class='card border-0' style='width:184.5px;'>
                    <a href="store.php?id=<?= $row['id'] ?>">
                      <img class='card-img-top' src="<?= $item_img ?>">
                    </a> 
                  </div>
                </a>
              </div>

              <div class="row">
                <a href="product.php?id=<?= $id ?>">
                  <div class='card border-0' style='width:184.5px;'>
                    <a href="store.php?id=<?= $row['id'] ?>">
                      <img class='card-img-top' src="<?= $item_img ?>">
                    </a> 
                  </div>
                </a>
              </div>
            
          </div>
              
        <?php }} ?>

      </div>
    </div>

    <!-- SM FEATURED SHOPS -->
    <div class="container mb-5 vanish-lg vanish-md">
      <div class="row">
        <div class="col-6">
            <h4>
              FEATURED SHOPS
            </h4>
        </div>
        <div class="col-6 text-right pt-2">View All&nbsp;<i class="fas fa-angle-double-right"></i></i></div>
      </div>
      <div class="row no-gutters">
        <?php 

          $sql = " SELECT * FROM tbl_items LIMIT 3 ";
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
              $item_img = $row['img_path'];
          ?>
          <div class="col-lg-2 col-md-3">
            <a href="product.php?id=<?= $id ?>">
              <div class = 'card border-0'>
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
    <?php 
      if(isset($_SESSION['id'])){
        $sql = "SELECT o.purchase_date, o.status_id, c.user_id, c.variation_id, i.name, i.price,i.img_path, i.id 
            as 'productId' 
            FROM tbl_orders o 
            JOIN tbl_carts c 
            JOIN tbl_items i 
            JOIN tbl_variations v 
            ON o.cart_session=c.cart_session 
            AND c.variation_id=v.id 
            AND v.product_id=i.id 
            WHERE status_id != 2 
            AND c.user_id = ? GROUP BY productId LIMIT 12";

        $statement = $conn->prepare($sql);
        $statement->execute([$_SESSION['id']]);

        if($statement->rowCount()){
    ?>
    <div class="container mb-5">
      <div class="row">
        <div class="col-6 pl-4">
            <h4>
              JUST FOR YOU
            </h4>
        </div>
        <div class="col-6 text-right pt-2">View All&nbsp;<i class="fas fa-angle-double-right"></i></i></div>
      </div>

      <div class="row no-gutters">

        <?php
          while($row = $statement->fetch()){
            $id = $row['productId'];
            $name = $row['name'];
            $price = $row['price'];
            $item_img = $row['img_path'];
          ?>

        <div class="col-lg-2 col-md-3 col-sm-6 px-1 pb-2">
          <a href="product.php?id=<?= $id ?>">
            <div class = 'card h-700 border-0'>
              <img class='card-img-top' src="<?= $item_img ?>" > 
              <div class="card-body pr-0">
                <div class='font-weight-bold'>
                  <?= $name ?>
                </div>
                <div>&#8369; <?= $price ?> </div>

                <div class='d-flex flex-row mt-3'>
                
                  <!-- WISHLIST BUTTONS -->
                  <div class='flex-fill' style='cursor:default;'>

                    <?php if(checkIfInWishlist($conn,$id) == 1 ) { ?>

                      <i class='fas fa-heart text-red'></i> 
                      <span class='product-wish-count<?= $id ?>'>
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
              <!-- /.CARD BODY -->
            </div>
            <!-- /.CARD -->
          </a>
        </div>
        
        <?php } ?>
      </div>
    </div>
    <?php } } ?>


    <!-- TRENDING PRODUCTS -->
    <div class="container mb-5">
      <div class="row">
        <div class="col-6 pl-4">
            <h4>
              TRENDING PRODUCTS
            </h4>
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
              $item_img = $row['img_path'];
          ?>
          <div class="col-lg-2 col-md-3 col-sm-6 px-1 pb-2">
            <a href="product.php?id=<?= $id ?>">
              <div class = 'card h-700 border-0'>
                <img class='card-img-top' src="<?= $item_img ?>" > 
                <div class="card-body pr-0">
                  <div class='font-weight-bold'>
                    <?= $name ?>
                  </div>
                  <div>&#8369; <?= $price ?> </div>

                  <div class='d-flex flex-row mt-3'>
                 
                    <!-- WISHLIST BUTTONS -->
                    <div class='flex-fill' style='cursor:default;'>

                      <?php if(checkIfInWishlist($conn,$id) == 1 ) { ?>

                        <i class='fas fa-heart text-red'></i> 
                        <span class='product-wish-count<?= $id ?>'>
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
          <a href="catalog.php?id=<?=$_GET['id'] = 1 ?>" class="btn btn-lg btn-block border hover_btn">See More Products</a>
        </div>
        <div class="col-4"></div>
      </div>
    </div>
  

<?php require_once "../partials/footer.php";?>
<?php require_once "../partials/modal_container.php"; ?>
<?php require_once "../partials/modal_container_big.php"; ?>