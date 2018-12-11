<?php 
  session_start(); 

  require_once "../controllers/connect.php";

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
                    $id = $_SESSION['id'];

            echo "
            <li class='nav-item mr-5'>
              <a class='nav-link text-light' href='../controllers/process_logout.php?id=$id' role='button'>
                <i class='fas fa-sign-in-alt'></i>
                Logout
              </a>
            </li>

            <li class='nav-item'>
              <a class='nav-link text-light' href='profile.php?id=$id'>
                <i class='fas fa-user'></i>
                My Account
              </a>
            </li>";

                  } else { 

            echo "
            <li class='nav-item mr-5'>
              <a class='nav-link modal-link text-light' href='#'' data-url='../partials/templates/login_modal.php' role='button'>
                <i class='fas fa-sign-in-alt'></i>
                Login
              </a>
            </li>

            <li class='nav-item'>
              <a class='nav-link text-light' href='register.php'>
                <i class='fas fa-user'></i>
                Register
              </a>
            </li>";

                } ?>

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

    
   
