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

    <title>Shoperoo</title>

    <!-- ICON -->
    <link rel="shortcut icon" href="../assets/images/logo.png">

    <!-- GOOGLE FONTS -->
    <link href="https://fonts.googleapis.com/css?family=Fredoka+One|Open+Sans|Roboto" rel="stylesheet">

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
    <nav class="navbar navbar-main navbar-expand-lg navbar-expand-md navbar-expand-sm text-light py-1">

        <div class="container d-flex flex-row">

            <a class="navbar-brand text-light mr-5" href="index.php" id="website_name">
                Shoperoo
            </a>

            <form class="form-inline flex-grow-1">
                <div class="input-group input-group-lg">
                    <input type="text" class="form-control border border-0" id="search-header" onkeyup="showResult(this.value)" placeholder="Search for products..." aria-label="Search">
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
                            <div id='cart-img'>

                                <span id="item-count">
                                    <?php 
                                        if ($productCount) {
                                        echo "<span class='badge circle text-light'>" . $productCount . "</span>";
                                        } elseif ($productCount == 0){
                                        echo "";
                                        } else {
                                        echo "";
                                        }
                                    ?>
                                </span>
                            </div>
                        
                        </a>

                        <div class="dropdown-menu mt-2" aria-labelledby="cartDropdown" style='width:17em;' id='cartDropdown_menu'>  
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

                            <?php } else { 
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

                        <div class="dropdown-menu mt-2" aria-labelledby="profileDropdown">
                            <a class="dropdown-item my-3" href='profile.php?id=$id'>
                                <i class="far fa-user-circle mr-2"></i>
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
                    
                    <li class='nav-item'>
                        <a class='nav-link text-light modal-link btn-border text-center' id='login-reg-header' data-id='<?= $userId ?>' data-url='../partials/templates/login_modal.php' role='button'> 
                            <!-- <i class="far fa-user"></i> -->
                            Login | Register
                        </a>
                    </li>
                            <?php } ?>
                </ul>
            
            <!-- </div> -->
                
        </div>
    </nav>
    
    <!-- CATEGORIES: TO BE INCLUDED ONLY IN INDEX.PHP -->
    <!-- <div class="container-fluid border">
        <div class="row">
            <div class="col-1"></div>
            <div class="col-10">
                <div class="row text-center">
            
                    <?php 
                        $sql = " SELECT * FROM tbl_categories LIMIT 7";
                        $statement = $conn->prepare($sql);
                        $statement->execute();
                        
                        while($row = $statement->fetch()){  
                    ?>
                    
                    <div class='border flex-fill py-3 dropdown'><?= $row['name']?></div>
                    <?php }?>
                </div>
            </div>
            <div class="col-1"></div>
        </div>  
    </div> -->


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

    
   
