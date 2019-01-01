<?php 
  
  require_once "../../config.php";
  require_once "../controllers/connect.php";
  require_once "../controllers/functions.php";

    // UPDATE LAST ACTIVITY    
  if(isset($_SESSION['id'])){
    $id = $_SESSION['id']; // userId
    $sql = "UPDATE tbl_users SET last_login = now() WHERE id = ?";
    $statement = $conn->prepare($sql);
    $statement->execute([$id]);

    $sql = " SELECT * FROM tbl_users WHERE id = ? ";
    $statement = $conn->prepare($sql);
    $statement->execute([$id]);
    $row = $statement->fetch();

    
        $profile_pic = $row['profile_pic'];

        if($profile_pic == "") {
            $profile_pic = DEFAULT_PROFILE; 
            $prefix = "rounded";
        } else {
            $profile_pic = BASE_URL ."/". $profile_pic . "_80x80.jpg";
            $prefix = "";
        } 

  }

  if(!isset($_SESSION['cart_session'])) {
    $_SESSION['cart_session'] = uniqid();
    
  }

  $cartSession = $_SESSION['cart_session'];
  $sql = " SELECT * FROM tbl_carts WHERE cart_session=?";
  $statement = $conn->prepare($sql);
  $statement->execute([$cartSession]);

    
  

?>

<!DOCTYPE html>
<html lang="en">

  <head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, shrink-to-fit=no">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <title>Shoperoo</title>

    <!-- ICON -->
    <link rel="shortcut icon" href="../assets/images/kangaroo-logo.png">

    <!-- GOOGLE FONTS -->
    <link href="https://fonts.googleapis.com/css?family=Fredoka+One|Open+Sans:300,400,400italic,600|Roboto" rel="stylesheet">

    <!-- FONTAWESOME -->
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.5.0/css/all.css" integrity="sha384-B4dIYHKNBt8Bc12p+WXckhzcICo0wtJAoU8YZTY5qE0Id1GSseTk6S+L3BlXeVIU" crossorigin="anonymous">

    <!-- ANIMATE CSS -->

   
    <!-- MATERIAL ICONS -->
    <!-- https://google.github.io/material-design-icons/#getting-icons -->
    <!-- <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet"> -->

    <!-- SLICK -->
    <link rel="stylesheet" type="text/css" href="//cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.css"/>
    <link href="../sources/slick/slick-theme.css" rel="stylesheet"/>

     <!-- Bootstrap CSS -->
     <link href="../vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">

    <!-- EXTERNAL CSS -->
    <link href="../assets/css/style.css" rel="stylesheet" />
    
  </head>

  
  <body>
      

    <!-- NAVIGATION  -->
    <div class="container">
        <div class="row py-0">
            <?php if(!isset($_SESSION['id'])){ ?>
            <div class="col-10">
            </div>
            <div class="col px-lg-0">
                <div class="d-flex flex-row">
                
                  

                    <!-- <div class='flex-fill text-lg-right text-md-right text-sm-center'>
                        <a class='nav-link border-0 text-lg-right text-md-right text-sm-center py-1' href="#" role='button'> 
                            <small>
                                TRACK MY ORDER
                            </small>
                        </a>
                    </div> -->

                    
                    <div class='flex-fill text-lg-right text-md-right text-sm-right'>
                        <a class='nav-link modal-link border-0 text-lg-right text-md-right text-sm-right py-1' data-url='../partials/templates/login_modal.php' role='button'> 
                            <img src='<?= DEFAULT_PROFILE ?>' height='20' class='rounded-circle mr-1'>
                            <small>
                                LOGIN | REGISTER
                            </small>
                        </a>
                    </div>

            <?php } else { ?>
                <div class="col-lg-10 col-md-9 col-sm-12 justify-content-right vanish-sm">
                    <div class="d-flex flex-row vanish-lg vanish-md vanish-sm text-sm-center">
                        <div class='flex-fill vanish-lg vanish-md vanish-sm'>
                            <a class='border-0 text-sm-center py-1' href='profile.php?id=<?=$id?>' role='button'> 
                                    <img src='<?= $profile_pic ?>' height='20' class='rounded-circle mr-1'>
                                    <small>HELLO,&nbsp;</small>
                                    <small>
                                        <?= getUsername($conn,$id) ?>
                                    </small>
                                    <small>!</small>
                            </a>
                        </div>

                        <div class='flex-fill vanish-lg vanish-md vanish-sm text-sm-center'>
                            <a class='border-0 text-sm-center' href='../controllers/process_logout.php?id=<?=$id?>' role='button'> 
                                <i class='fas fa-sign-in-alt py-1'></i>
                                <small> LOG OUT</small>
                            </a>
                        </div>
                    </div>
                </div>
                <div class="col">
                    <div class='flex-fill text-lg-right text-md-right text-sm-right'>
    
                        <div class='dropdown py-1' id='profileDropdownContainer'>
                            <a class='dropdown-toggle py-1 text-right' id="profileDropdown" role="button" data-toggle="dropdown">
                                <img src='<?= $profile_pic ?>' height='20' class='<?= $prefix ?>circle mr-1'>
                                <small>HELLO,&nbsp;</small>
                                <small>
                                    <?= getUsername($conn,$id) ?>
                                </small>
                                <small>!</small>
                                
                            </a>  
                            

                            <div class="dropdown-menu" aria-labelledby="profileDropdown" id='profileDropdown_menu' >
                                <a class="dropdown-item my-3" href='profile.php?id=<?=$id?>'>
                                    <i class="far fa-edit mr-1"></i>
                                    <small>MY PROFILE</small>
                                </a>
                                    <a class="dropdown-item mb-3" href='store.php?id=<?= getStoreId ($conn,$id) ?>'>
                                    <i class="fas fa-store mr-1"></i>
                                    <small>MY SHOP</small>
                                </a>
                                <!-- <a class="dropdown-item mb-4 btn_view_wishList" data-id='<?= $id ?>'>
                                    <i class="far fa-heart mr-2"></i>
                                    Wish List
                                    <span class='badge text-light user_wish_count'><?= getWishlishtCount($conn) ?></span>
                                </a> -->
                                <div class="dropdown-divider my-3"></div>
                                <a class="dropdown-item mb-3" href='../controllers/process_logout.php?id=<?=$id?>'>
                                    <i class='fas fa-sign-in-alt mr-1'></i>
                                    <small> LOG OUT</small>
                                </a>
                            </div>
                        </div>

                    </div>
                    <?php } ?>

                </div>
            </div>
        </div>
    </div>

    <nav class="navbar navbar-main sticky-top">
        <div class="container">

                <!-- <div class=""> -->
                    <div class="col-lg-5 col-md-6 col-sm-12">
                        <!-- <div class="col-6"> -->
                                <div class="d-flex align-items-center">
                                    <div id='header-logo'></div>&nbsp;
                                    <a class="navbar-brand text-light" href="index.php" id="website_name">
                                        Shoperoo
                                    </a>
                                   
                                </div>
                            
                        <!-- </div> -->
                    </div>
                    <div class="col-lg-7 col-md-6 col-sm-12 px-0">
                        <!-- <div class="col p-1"> -->
                            <form id="search_form">
                                <div class='form-row align-items-center'>
                                    <!-- <div class="col-12"> -->
                            
                                        <div class="input-group input-group-lg col-12 px-0">
                                            <input type="text" class="form-control border border-0 text-responsive" 
                                                id="search-header" 
                                                placeholder="Search for products..." 
                                                aria-label="Search"
                                                value="<?= isset($_GET['searchKey']) ? $_GET['searchKey'] : "" ?>"
                                            >
                                            <div class='dropdown' id='livesearch'>
                                                <div class="dropdown-menu" aria-labelledby="livesearch"></div>
                                            </div>
                                        
                                    
                                            <div class="input-group-append">

                                                <!-- CART -->
                                                <div class="nav-item dropdown">

                                                    <a class='nav-link text-light py-0 pl-3 pr-0' id="cartDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
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
                                                            $statement = $conn->prepare($sql);
                                                            $statement->execute([$cartSession]);

                                                            $count = $statement->rowCount();
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
                                                                                <span><?=$price?></span>
                                                                                <span></span>
                                                                                <?php if($quantity > 1) { ?>
                                                                                <span>&nbsp;x&nbsp;</span>
                                                                                <span id='quantity_header<?=$variationId?>'><?=$quantity?></span>
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
                                                                data-url='../partials/templates/cart_modal.php' 
                                                                role='button'
                                                                id='cartModal'>
                                                                Go To Cart
                                                            </button>
                                                        </a>        
                                                        <?php } ?>
                                                    </div>
                                                </div>
                                                
                                            </div>
                                        </div>

                                    <!-- </div> -->


                                </div>
                            </form>

                        <!-- </div> -->
                    </div>
                    
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


    
   
