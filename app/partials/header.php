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

    <!-- FONTAWESOME -->
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.5.0/css/all.css" integrity="sha384-B4dIYHKNBt8Bc12p+WXckhzcICo0wtJAoU8YZTY5qE0Id1GSseTk6S+L3BlXeVIU" crossorigin="anonymous">

    <!-- ANIMATE CSS -->

    <!-- Bootstrap CSS -->
    <link href="../vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">

    <!-- EXTERNAL CSS -->
    <link href="../assets/css/style.css" rel="stylesheet">
    
  </head>

  
  <body>

    <!-- NAVIGATION  -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark text-light fixed-top mb-5">
      <div class="container">
        <a class="navbar-brand font-weight-bold" href="index.php">Demo Shop</a>
        
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>
       
        <div class="collapse navbar-collapse" id="navbarResponsive">

          <ul class="navbar-nav ml-auto" id="navbar-nav">

             

            <!-- CATALOG -->
            <li class="nav-item mr-5">
              <a class="nav-link text-light" href="catalog.php">
                <i class="fas fa-book"></i>
                Catalog
              </a>
            </li>

            <!-- CART -->
            <li class="nav-item mr-5">
              <a class='nav-link modal-link text-light' 
                  href='#' 
                  data-id='<?= $_GET['id'] ?>' 
                  data-url='../partials/templates/cart_modal.php' 
                  role='button'
                  id='cartModal'>
                
                <i class="fas fa-cart-plus"></i>
                Cart
                <span id="item-count">

                  <?php 
                    if ($productCount) {
                      echo "<span class='badge badge-primary text-light'>" . $productCount . "</span>";
                    } elseif ($productCount == 0){
                      echo "";
                    } else {
                      echo "";
                    }
                  ?>

                </span>
              </a>
            </li>
           
            <!-- LOGOUT AND REGISTER -->
                  <?php if(isset($_SESSION['id'])) { 
                    $id = $_SESSION['id']; ?>


            <li class='nav-item dropdown'>
              <a class='nav-link dropdown-toggle text-light' id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <?= "<img src='../../".getProfilePic($conn, $id)."_80x80.jpg' height='20' class='circle mr-1'>" ?>
                <?= getUsername($conn,$id) ?>

                <!-- <img src="../../uploads/2/5c088b195adb0.jpg" height="80"> -->
              </a>

              <div class="dropdown-menu mt-2" aria-labelledby="navbarDropdown">
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
                              echo "<span class='badge badge-danger text-light'>" . getWishlishtCount($conn) . "</span>";
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

            </li>

                <?php } else { ?>

            <li class='nav-item mr-5'>
              <a class='nav-link modal-link text-light' href='#' data-url='../partials/templates/login_modal.php' role='button'>
                <i class='fas fa-sign-in-alt'></i>
                Login
              </a>
            </li>

            <li class='nav-item'>
              <a class='nav-link text-light' href='register.php'>
                <i class='fas fa-user'></i>
                Register
              </a>
            </li>

               <?php  } ?>

          </ul>
        </div>
        <!-- /NAVBAR COLLAPSE -->
      </div>
      <!-- /CONTAINER -->
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

    
   
