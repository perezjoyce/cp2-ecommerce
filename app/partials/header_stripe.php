<?php 
//   require_once '../../../config.php';
  require_once "../../config.php";
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
            $prefix = "rounded-";
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

    <title>Mamaroo</title>

    <!-- ICON -->
    <link rel="shortcut icon" href="../assets/images/kangaroo-logo.png">

    <!-- GOOGLE FONTS -->
    <!-- <link href="https://fonts.googleapis.com/css?family=Fredoka+One|Open+Sans:300,400,400italic,600|Roboto" rel="stylesheet"> -->
    <link href="https://fonts.googleapis.com/css?family=Lato|Rubik|Pacifico|Fredoka+One" rel="stylesheet">

    <!-- FONTAWESOME -->
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.5.0/css/all.css" integrity="sha384-B4dIYHKNBt8Bc12p+WXckhzcICo0wtJAoU8YZTY5qE0Id1GSseTk6S+L3BlXeVIU" crossorigin="anonymous">

    <!-- ANIMATE CSS -->

   
    <!-- MATERIAL ICONS -->
    <!-- https://google.github.io/material-design-icons/#getting-icons -->
    <!-- <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet"> -->

    <!-- SLICK -->
    <link rel="stylesheet" type="text/css" href="<?= BASE_URL ?>/app/sources/slick/slick.css"/>
    <link href="../sources/slick/slick-theme.css" rel="stylesheet"/>

     <!-- Bootstrap CSS -->
     <link href="../vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">

    <!-- EXTERNAL CSS -->
    <link href="../assets/css/style.css" rel="stylesheet" />
    
  </head>

  

  
  <body>
    <!-- <div id="loader"></div> -->

    <!-- NAVIGATION  -->
    <div class="container">
        <div class="row py-0">
            <?php if(!isset($_SESSION['id'])){ ?>
            <div class="col-6">
            </div>
            <div class="col px-lg-0">
                <div class="d-flex flex-row">
                
                    
                    <div class='flex-fill text-lg-right text-md-right text-sm-right'>
                        <a class='nav-link modal-link border-0 text-lg-right text-md-right text-sm-right py-1' data-url='../partials/templates/login_modal.php' role='button'> 
                            <img src='<?= DEFAULT_PROFILE ?>' height='20' class='rounded-circle mr-1'>
                            <small>
                                LOGIN | REGISTER
                            </small>
                        </a>
                    </div>

                <?php } ?>
                

                </div>
            </div>
        </div>
    </div>

    <nav class="navbar navbar-main sticky-top">
        <div class="container px-0">

                <!-- <div class=""> -->
                    <div class="col px-0">
                        <!-- <div class="col-6"> -->
                                <div class="d-flex align-items-center">
                                    <div id='header-logo'></div>&nbsp;
                                    <a class="navbar-brand" href="index.php" id="website_name" style='font-family:Fredoka One;color:#3F3F3F'>
                                      Mamaroo  
                                    </a>
                                   
                                </div>
                            
                        <!-- </div> -->
                    </div>
                   
     
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

    