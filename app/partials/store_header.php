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

  if(isset($_SESSION['id'])) {
    $id = $_SESSION['id'];
}

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
                            <i class="far fa-user-circle mr-2"></i>
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
                                    <?= "<img src='../../".getProfilePic($conn, $id)."_80x80.jpg' height='20' class='circle mr-1'>" ?>
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
    
                        <div class='dropdown' id='profileDropdownContainer'>
                        <a class='dropdown-toggle py-1 text-right' id="profileDropdown" role="button" data-toggle="dropdown">
                                    <?= "<img src='../../".getProfilePic($conn, $id)."_80x80.jpg' height='20' class='circle mr-1'>" ?>
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
                                     <a class="dropdown-item mb-3" href='store.php?id=<?= getStoreId ($conn,$userId) ?>'>
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

    <nav class="navbar navbar-main navbar-expand-lg  navbar-expand-md sticky-top">
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
                        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                            <span class="navbar-toggler-icon"></span>
                        </button>
                        <div class="collapse navbar-collapse" id="navbarNav">
                            <ul class="navbar-nav ml-auto">
                                <li class="nav-item px-5">
                                    <a class="nav-link text-light font-weight-bold" href="#"> 
                                        <div class='border-bottom'>SHOP PROFILE</div>
                                    </a>
                                </li>
                                <li class="nav-item px-5">
                                    <a class="nav-link text-light" href="#">PRODUCTS</a>
                                </li>
                                <li class="nav-item pl-5">
                                    <a class="nav-link text-light" href="#">ORDERS</a>
                                </li>
                            </ul>
                        </div>
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


    
   
