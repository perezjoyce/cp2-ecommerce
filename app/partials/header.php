<?php 
  session_start(); 
  
  require_once "../../config.php";
  require_once "../controllers/connect.php";
  require_once "../controllers/functions.php";

    // UPDATE LAST ACTIVITY    
  if(isset($_SESSION['id'])){
    $userId = $_SESSION['id'];
    $sql = "UPDATE tbl_users SET last_login = now() WHERE id = ?";
    $statement = $conn->prepare($sql);
    $statement->execute([$userId]);
  }

  if(!isset($_SESSION['cart_session'])) {
    $_SESSION['cart_session'] = uniqid();
    
  }

  $cartSession = $_SESSION['cart_session'];
  $sql = " SELECT * FROM tbl_carts WHERE cart_session=?";
  $statement = $conn->prepare($sql);
  $statement->execute([$cartSession]);
//   $productCount = $statement->rowCount();

?>

<!DOCTYPE html>
<html lang="en">

  <head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, shrink-to-fit=no">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <title>Shoperoo</title>

    <!-- ICON -->
    <link rel="shortcut icon" href="../assets/images/logo.png">

    <!-- GOOGLE FONTS -->
    <link href="https://fonts.googleapis.com/css?family=Fredoka+One|Open+Sans:300,400,400italic,600|Roboto" rel="stylesheet">

    <!-- FONTAWESOME -->
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.5.0/css/all.css" integrity="sha384-B4dIYHKNBt8Bc12p+WXckhzcICo0wtJAoU8YZTY5qE0Id1GSseTk6S+L3BlXeVIU" crossorigin="anonymous">

    <!-- ANIMATE CSS -->

    <!-- Bootstrap CSS -->
    <link href="../vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- MATERIAL ICONS -->
    <!-- https://google.github.io/material-design-icons/#getting-icons -->
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

    <!-- SLICK -->
    <link rel="stylesheet" type="text/css" href="//cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.css"/>
    <link href="../sources/slick/slick-theme.css" rel="stylesheet"/>

    <!-- EXTERNAL CSS -->
    <link href="../assets/css/style.css" rel="stylesheet" />
    
  </head>

  
  <body>

    <!-- NAVIGATION  -->
    <nav class="navbar navbar-main navbar-expand-lg navbar-expand-md navbar-expand-sm text-light py-1">

        <div class="container d-flex flex-row">

            <a class="navbar-brand text-light mr-5" href="index.php" id="website_name">
                Shoperoo
            </a>

            <form id="search_form" class="form-inline flex-grow-1">
                <div class="input-group input-group-lg">
                    <input type="text" class="form-control border border-0 text-responsive" 
                        id="search-header" 
                        placeholder="Search for products..." 
                        aria-label="Search"
                        value="<?= isset($_GET['searchKey']) ? $_GET['searchKey'] : "" ?>"
                    >
                    <div class='dropdown' id='livesearch'>
                        <div class="dropdown-menu" aria-labelledby="livesearch"></div>
                    </div>
                 
            
                    <!-- <div class="input-group-append">
                        <a class="btn bg-light border border-0">
                            <i class="fas fa-search"></i>
                        </a>
                    </div> -->
                </div>
            </form>
             
        
            <!-- <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button> -->
        
            <!-- <div class="collapse navbar-collapse" id="navbarResponsive"> -->

                <ul class="navbar-nav d-flex" id="navbar-nav">

                    <!-- CATALOG -->
                    <!-- <li class="nav-item mr-5">
                        <a class="nav-link text-light" href="catalog.php">
                        <i class="fas fa-book"></i>
                        Catalog
                        </a>
                    </li> -->
        
                    <!-- CART -->
                    <li class="nav-item dropdown mr-5">

                        <a class='nav-link text-light' id="cartDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <!-- <i class="fas fa-cart-plus fa-2x"></i> -->
                            <div class='d-flex flex-row'>
                               
                                <div id='cart-img'></div>
                                
                                <span>
                                    
                                    <span class='badge text-light' id="item-count"><?= itemsInCart($conn,$cartSession) ?></span>
                                    
                                </span>
                            </div>
                            
                        
                        </a>

                        <div class="dropdown-menu mt-2 pt-3" aria-labelledby="cartDropdown" style='width:17em;' id='cartDropdown_menu'>  
                            <?php 
                                $sql = "SELECT v.product_id as 'productId', v.variation_name, c.variation_id, c.quantity, c.cart_session, p.img_path, p.name, p.price
                                FROM tbl_carts c 
                                JOIN tbl_items p 
                                JOIN tbl_variations v
                                ON v.product_id = p.id AND c.variation_id=v.id WHERE cart_session= ?";
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

                            <?php } else { 
                                while($row = $statement->fetch()){
                                    $variationId = $row['variation_id'];
                                    $variationName = $row['variation_name'];
                                    $productId = $row['productId'];
                                    $name = $row['name'];
                                    $price = $row['price'];
                                    $quantity = $row['quantity'];
                                    $image = $row['img_path'];  
                            ?>
                                
                            <div class='dropdown-item' id='product-row<?=$variationId?>'>
                                <div class='row mx-1'>
                                    <div class='d-flex flex-row' style='justify-content:flex-start;width:100%;'>
                                        <div class='flex pr-2'>
                                            <img src='<?=$image?>' style='width:35px;height:35px;'> 
                                        </div>   
                                        <div class='flex-fill'>
                                            <div class='d-flex flex-column'>
                                                <small><?= $name ?></small>
                                                <small class='text-gray italics'><?= $variationName ?></small>
                                                <small class='text-gray'>
                                                    <?=$price?>
                                                     <?php if($quantity > 1) { ?>
                                                    &nbsp;x&nbsp;<?=$quantity?>
                                                    <?php } ?>
                                                </small>
                                            </div>
                                        </div>
                                        <div class='flex-fill text-right' style='align-self:end;'>
                                            <a data-productid='<?=$productId?>' data-vname='<?=$variationName?>' data-variationid='<?= $variationId ?>' data-quantity='<?=$quantity?>' role='button' class='btn_delete_item text-gray flex-fill font-weight-light' style='font-size:16px;'>
                                            &times;
                                            </a>
                                        </div>
                                        
                                    </div>
                                </div>
                            </div>
                        
                            
                                <?php } ?>
                        
                            <div class="dropdown-divider my-3"></div>
                            <a class='dropdown-item mb-3'>
                            <!-- data-id='$_GET['id']' REMOVED FROM THIS BUTTON-->
                                <button class='modal-link-big btn btn-block btn-gradient' 
                                    href='#' 
                                     
                                    data-url='../partials/templates/cart_modal.php' 
                                    role='button'
                                    id='cartModal'>
                                    Go To Cart
                                </button>
                            </a>        
                            <?php } ?>
                        </div>
                    </li>
                    
                    <!-- LOGOUT AND REGISTER -->
                            <?php if(isset($_SESSION['id'])) { 
                                $id = $_SESSION['id']; ?>

                
                    <li class='nav-item dropdown'>
                        <a class='nav-link dropdown-toggle text-light' id="profileDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <?= "<img src='../../".getProfilePic($conn, $id)."_80x80.jpg' height='20' class='circle mr-1'>" ?>
                            <?= getUsername($conn,$id) ?>

                            <!-- <img src="../../uploads/2/5c088b195adb0.jpg" height="80"> -->
                        </a>

                        <div class="dropdown-menu" aria-labelledby="profileDropdown" id='profileDropdown_menu' style='min-width:1rem;'>
                            <a class="dropdown-item my-3" href='profile.php?id=$id'>
                                <i class="far fa-user-circle mr-2"></i>
                                Profile
                            </a>
                            <a class="dropdown-item mb-3 btn_view_addresses" data-id='<?= $id ?>'>
                                <i class="far fa-address-book mr-2"></i>
                                Addresses
                            </a>
                            <a class="dropdown-item mb-4 btn_view_wishList" data-id='<?= $id ?>'>
                                <i class="far fa-heart mr-2"></i>
                                Wish List
                                <span class='badge text-light user_wish_count'><?= getWishlishtCount($conn) ?></span>
                            </a>
                            <div class="dropdown-divider my-3"></div>
                            <a class="dropdown-item mb-3" href='../controllers/process_logout.php?id=<?=$id?>'>
                                <i class='fas fa-sign-in-alt mr-2'></i>
                                Logout
                            </a>
                        </div>
                    </li>

                            <?php } else { ?>
                    
                    <li class='nav-item'>
                        <a class='nav-link text-light modal-link btn-border text-center' id='login-reg-header' data-url='../partials/templates/login_modal.php' role='button'> 
                            <!-- <i class="far fa-user"></i> -->
                            Log In | Register
                        </a>
                    </li>
                            <?php } ?>
                </ul>
            
            <!-- </div> -->
                
        </div>
    </nav>

    <!-- ALERTS -->
    <div class="container">
      <div class="row">
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


    
   
