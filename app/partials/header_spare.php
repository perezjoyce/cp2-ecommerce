<?php 
  session_start(); 

  require_once "../controllers/connect.php";
  require_once "../controllers/functions.php";

  if(!isset($_SESSION['cart_session'])) {
    $_SESSION['cart_session'] = uniqid();
    
  }

  $cartSession = $_SESSION['cart_session'];
  $sql = " SELECT * FROM tbl_carts WHERE cart_session=?";
  $statement = $conn->prepare($sql);
  $statement->execute([$cartSession]);
  $productCount = $statement->rowCount();
?>

<!DOCTYPE html>
<html lang="en">

  <head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, shrink-to-fit=no">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <title>Shopperoo</title>

    <!-- ICON -->
    <link rel="shortcut icon" href="#">

    <!-- GOOGLE FONTS -->
    <link href="https://fonts.googleapis.com/css?family=Fredoka+One" rel="stylesheet">

    <!-- FONTAWESOME -->
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.5.0/css/all.css" integrity="sha384-B4dIYHKNBt8Bc12p+WXckhzcICo0wtJAoU8YZTY5qE0Id1GSseTk6S+L3BlXeVIU" crossorigin="anonymous">

    <!-- ANIMATE CSS -->

    <!-- Bootstrap CSS -->
    <link href="../vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">

    <!-- EXTERNAL CSS -->
    <link href="../assets/css/style.css" rel="stylesheet">
    
  </head>

  
  <body>

    <!-- NAVIGATION -->
    <nav class="navbar navbar-expand-lg navbar-expand-md text-light p-0">

          <div class="col-lg-1 vanish-md vanish-sm"></div>

          <!-- LOGO, WITH CART AND REG ON SM -->
          <div class="col-lg-1 col-md-2 col-sm-6">
            <div class="d-flex flex-row">
              <div class="flex-grow-1">
                <a class="navbar-brand font-weight-bold text-light" href="index.php" id='website_name'>Tenda</a>
              </div>
              
              <!-- LINKS ON SM -->
              <div class='d-flex flex-row'>
                <span class='vanish-lg vanish-md mr-3'>
                  <a class='modal-link btn text-light' 
                          href='#' 
                          data-id='<?= $_GET['id'] ?>' 
                          data-url='../partials/templates/cart_modal.php' 
                          role='button'
                          id='cartModal'>
                    <i class="fas fa-cart-plus pt-1 mt-2 fa-2x"></i>
                    <span id="item-count">
                      <?php 
                        if ($productCount) {
                          echo "<span class='badge border-0 circle'>" . $productCount . "</span>";
                        } elseif ($productCount == 0){
                          echo "";
                        } else {
                          echo "";
                        }
                      ?>
                    </span>
                  </a>
                </span>
                       
                  <!-- WHEN LOGGED IN, SHOW LOGOUT ICON -->
                  <?php if(isset($_SESSION['id'])) { 
                    $id = $_SESSION['id']; ?>
                
                <span class='vanish-lg vanish-md mr-4'>
                  <a class='nav-link text-light mx-0 px-0' href='profile.php?id=$id'>
                    <i class='fas fa-user pt-1 mt-2 fa-2x'></i> 
                  </a>
                </span>

                <span class='vanish-lg vanish-md'>
                  <a class='nav-link text-light mx-0 px-0' href='../controllers/process_logout.php?id=$id'>
                    <i class='fas fa-sign-in-alt pt-1 mt-2 fa-2x'></i>
                  </a>
                </span>
                    
                  <!-- WHEN LOGGED OUT, SHOW LOGIN ICON -->
                  <?php } else { ?>

                <span class='vanish-lg vanish-md'>
                  <a class='nav-link modal-link text-light' href='#' data-url='../partials/templates/login_modal.php' role='button'>
                    <i class='far fa-user pt-1 mt-2 fa-2x'></i>  
                  </a>
                </span>

                  <?php  } ?>

              </div>
            </div>
          </div>

          <div class="mr-5 vanish-md vanish-sm"></div>

          <!-- SEARCH -->
          <div class="col-lg-6 col-md-8 col-sm-6">
            <div class="input-group input-group-lg vanish-sm">

              <input type="text" class="form-control border border-0" id="search" placeholder="Search for products...">
              
              <div class="input-group-append">
                <a class="btn bg-light">
                  <i class="fas fa-search"></i>
                </a>
              </div>
            </div>

            <div class="input-group input-group-lg vanish-lg vanish-md mb-2">
              <input type="text" class="form-control border border-0 mb-3" id="search" placeholder="Search for products...">
              <div class="input-group-append">
                <a class="btn bg-light">
                  <i class="fas fa-search"></i>
                </a>
              </div>
            </div>
          </div>

          <div class="mr-5 vanish-md vanish-sm"></div>

          <!-- CART & REG ON MD & LG -->
          <div class='col-md-2 vanish-sm p-0'>
            <div class="d-flex flex-row">
              <!-- CART MD -->
              <div class='ml-5 vanish-sm'></div>
              <div class='p-0'>
                <span class='nav-item dropdown vanish-sm'>
                  <a class='text-light' id="cartDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="fas fa-cart-plus mt-2 fa-2x"></i>
                    <span id="item-count">
                      <?php 
                        if ($productCount) {
                          echo "<span class='badge border-0 circle'>" . $productCount . "</span>";
                        } elseif ($productCount == 0){
                          echo "&nbsp;&nbsp;&nbsp;&nbsp;";
                        } else {
                          echo "&nbsp;&nbsp;&nbsp;&nbsp;";
                        }
                      ?>
                    </span>
                  </a>

                  <!-- CART MD DROPDOWM -->
                  <div class="dropdown-menu dropdown-menu-right" aria-labelledby="cartDropdown" id='cartDropdown_menu'>  
                      <?php 
                          $sql = "SELECT c.item_id, c.quantity, p.img_path, p.name, p.price, p.id as productId
                          FROM tbl_carts c 
                          JOIN tbl_items p on p.id=c.item_id 
                          WHERE cart_session=?";
                          //$result = mysqli_query($conn, $sql);
                          $statement = $conn->prepare($sql);
                          $statement->execute([$cartSession]);

                          $count = $statement->rowCount();
                          // $subtotalPrice = 0;
                          if(!$count) {
                      ?>
                        
                    <a class="dropdown-item pb-5 text-center" href='#' id='header-empty-cart'>
                      <img src="http://www.aimilayurveda.com/catalog/view/theme/aimil/assets/images/empty.png" alt="empty_cart" style='width:10em;'>
                      <div><small> Your shopping cart is empty</small></div>
                    </a>

                        <?php } else { ?>          
                        <?php 
                            while($row = $statement->fetch()){
                              $productId = $row['item_id'];
                              $name = $row['name'];
                              $price = $row['price'];
                              $quantity = $row['quantity'];
                              $image = $row['img_path'];  
                        ?>
                          
                    <div class='dropdown-item my-3' id='product-row<?=$productId?>'>
                      <div class='row'>
                        <div class='col-3'>
                          <img src='<?=$image?>' style='width:30px;height:30px;'>
                        </div>

                        <div class='col-7'>
                          <div class='row'>
                            <small><?= $name ?></small>
                          </div>
                          <div class='row text-secondary'>
                            <small><?= $price ?></small>
                            <?php if($quantity > 1) { ?>
                              <small> &nbsp;x&nbsp;<?=$quantity?></small>
                            <?php } ?>
                          </div>
                          
                        </div>
                        
                        <div class='col-2'>
                          <a data-productid='<?= $productId ?>' role='button' class='btn_delete_item text-danger'>
                            &times;
                          </a>
                        </div>
                      </div>
                    </div>
                          
                        <?php } ?>
                    <div class="dropdown-divider my-3"></div>
                      <a class='dropdown-item mb-3'>
                        <button class='modal-link btn btn-block btn-primary' 
                          href='#' 
                          data-id='<?= $_GET['id'] ?>' 
                          data-url='../partials/templates/cart_modal.php' 
                          role='button'
                          id='cartModal'>
                          Go To Cart
                        </button>
                      </a>        
                        <?php } ?>
                  </div>

                </span>
              </div>
              <div class='ml-5 vanish-sm vanish-md'></div>
              
              <!-- LOGIN ON MD -->
              <div>
                <span class='nav-item dropdown vanish-sm'>
                  <!-- WHEN LOGGED IN, SHOW LOGOUT ICON -->
                  <?php if(isset($_SESSION['id'])) { 
                    $id = $_SESSION['id']; ?>

                  <a class='text-light mx-0' id='profileDropdown' role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class='fas fa-user mt-2 fa-2x'></i>
                  </a>

                  <!-- LOGIN DROPDOWN -->
                  <div class="dropdown-menu dropdown-menu-right" aria-labelledby="profileDropdown" id='profileDropdown_menu'>
                    <a class="dropdown-item my-3" href='profile.php?id=$id'>
                      <i class='far fa-user mr-2'></i> 
                      Profile
                    </a>
                    <a class="dropdown-item mb-3 btn_view_addresses" data-id='<?= $id ?>'>
                      <i class="far fa-address-book mr-2"></i>
                        Shipping Addresses
                    </a>
                    <a class="dropdown-item mb-4 btn_view_wishList" data-id='<?= $id ?>'>
                      <i class="far fa-heart mr-2"></i>
                      Wish List
                      <span id='wish-count-header'>
                        <?php 
                            if (getWishlishtCount($conn) == 0) {
                                echo "<span></span>";
                            } else {
                                echo "<span class='badge border-0 circle'>" . getWishlishtCount($conn) . "</span>";
                            }
                        ?>
                      </span>
                    </a>
                    
                    <div class="dropdown-divider my-3"></div>
                      <a class="dropdown-item mb-3" href='../controllers/process_logout.php?id=$id'>
                        <i class='fas fa-sign-in-alt mr-2'></i>
                        Logout
                      </a>
                  </div>
                </span>
                
                  <!-- WHEN LOGGED OUT, SHOW LOGIN ICON -->
                  <?php } else { ?>

                <span class='vanish-sm'>
                  <a class='modal-link text-light' href='#' data-url='../partials/templates/login_modal.php' role='button'>
                    <i class='fas fa-user mt-2 fa-2x'></i>  
                  </a>
                </span>

                  <?php  } ?>

              </div>
            </div>
          </div>

          <div class="col-lg-1 vanish-md vanish-sm"></div>
   
    </nav>


    <!-- ALERTS -->
    <div class="container">
      <div class="row my-5">
        <div class="col-lg-12">
          
          <?php if(isset($_GET['uploadError'])): ?>
            <div class="alert alert-info"><?= $_GET['uploadError'] ?></div>
          <?php endif; ?> 
          
          <?php if(isset($_GET['msg'])): ?>
            <div class="alert alert-info">Please log in using your account first.</div>
          <?php endif; ?> 

        </div>
      </div>
    </div>

    
   
