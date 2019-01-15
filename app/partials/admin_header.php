<?php 
  require_once '../../config.php';

    // UPDATE LAST ACTIVITY    
    $id = $_GET['id'];
    if(empty($id)){ 
        echo "<script>window.location.href='".BASE_URL."/app/views/'</script>";
    } else {
        
        if(isset($_SESSION['id'])){
            $userId = $_SESSION['id'];

            $sql = "SELECT * FROM tbl_users WHERE id =? AND userType = 'admin' ";
            $statement = $conn->prepare($sql);
            $statement->execute([$userId]);
            $count = $statement->rowCount();

            if(!$count) {
                echo '<script>history.go(-1);</script>';
            } else {
                $row = $statement->fetch();
                $firstName = $row['first_name'];
                $lastName = $row['last_name'];
                $username = $row['username'];
                $profile_pic = $row['profile_pic'];

                if($profile_pic == "") {
                    $profile_pic = DEFAULT_PROFILE; 
                    $prefix = "rounded";
                } else {
                    $profile_pic = BASE_URL ."/". $profile_pic . "_80x80.jpg";
                    $prefix = "";
                } 

                $sql2 = "UPDATE tbl_users SET last_login = now() WHERE id = ?";
                $statement2 = $conn->prepare($sql2);
                $statement2->execute([$userId]);


            }
            
            
        } else {
            echo "<script>window.location.href='".BASE_URL."/app/views/'</script>";
        }
        
          
    }  



?>

<!DOCTYPE html>
<html lang="en">

  <head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, shrink-to-fit=no">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <title>Mamaroo</title>

    <!-- ICON -->
    <link rel="shortcut icon" href="../assets/images/logo-1.png">

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

                <div class="col px-lg-0">
                    <div class="d-flex flex-row">
                    
                    
                        <div class='flex-fill text-lg-right text-md-right text-sm-right'>
                            <a class='nav-link modal-link border-0 text-lg-right text-md-right text-sm-right py-1' data-url='../partials/templates/login_modal.php' role='button'> 
                                <i class="far fa-user-circle mr-2"></i>
                                <small>
                                    LOGIN | REGISTER
                                </small>
                            </a>
                        </div>
                    
                    </div>
                </div>

            <?php } else { ?>
                
                <div class="col-12">
                    <div class="d-flex flex-row">
                        <div class='flex-fill text-lg-right text-md-right text-sm-right'>
        
                            <div class='dropdown py-1' id='profileDropdownContainer'>
                            <a class='dropdown-toggle py-1 text-right' id="profileDropdown" role="button" data-toggle="dropdown">
                                        <img src='<?= $profile_pic ?>' height='20' class='<?= $prefix ?>circle mr-1'>
                                        <small>HELLO,&nbsp;</small>
                                        <small>
                                            <?= strtoupper($firstName) ?>
                                        </small>
                                        <small>!</small>
                                        
                                    </a>  
                                    

                                    <div class="dropdown-menu py-0" aria-labelledby="profileDropdown" id='profileDropdown_menu' >
                                        <div class="dropdown-divider py-0 my-0"></div>
                                        <a class="dropdown-item py-3" href='../controllers/process_logout.php?id=<?=$userId?>'>
                                            <i class='fas fa-sign-in-alt pr-2'></i>
                                            <small>LOG OUT</small>
                                        </a>
                                    </div>
                            </div>

                        </div>
                    </div>
                </div>

            <?php } ?>

                
            
        </div>
    </div>

    <nav class="navbar navbar-main navbar-expand-lg navbar-expand-md sticky-top">
        <div class="container">

                <!-- <div class=""> -->
                    <div class="col-lg-4 col-md-3 col-sm-12 pl-0">
                        <!-- <div class="col-6"> -->
                                <div class="d-flex align-items-center">
                                    <!-- <div id='header-logo'></div>&nbsp; -->
                                    <a class="navbar-brand flex-fill" id="website_name" style='font-family:Fredoka One;'>
                                        Mamaroo
                                    </a>

                                    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target=".navbar-collapse" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                                        <span class="navbar-toggler-icon py-1 border-0"><i class="fas fa-bars"></i></i></span>
                                    </button>
                                </div>

                            
                        <!-- </div> -->
                    </div>
                    <div class="col px-0">
                        <div class="collapse navbar-collapse" id="navbarNav">
                            <ul class="navbar-nav ml-lg-auto ml-md-auto px-sm-1">
                                <!-- <li class="nav-item pl-lg-5 pl-md-4">
                                    <a class="nav-link" href="store-profile.php?id=<$storeId?>"> 
                                        <div class=' isCurrentPage('profile') ? "underline" : "" ?>' ><span class='vanish-md vanish-sm'>SHOP&nbsp;</span>PROFILE</div>
                                    </a>
                                </li>

                                <div class='d-flex flex-row'>
                                    <li class="nav-item dropdown pl-lg-5 pl-md-4 d-flex flex-row">
                                        <a class="nav-link" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" > 
                                            <div class="d-flex flex-row">
                                                <div class='< isCurrentPage('product') ? "underline" : "" ?> dropdown-toggle'>
                                                    PRODUCTS
                                                </div>
                                            </div>
                                        </a>
                                        <div class="dropdown-menu py-0">
                                            <a class="dropdown-item py-3" href="store-products.php?id=<$storeId?>">
                                                <i class="fas fa-clipboard-list pr-2 text-secondary"></i>
                                                <small>INVENTORY</small>
                                            </a>
                                            <a class="dropdown-item py-3" href="store-add-product.php?id=<$storeId?>">
                                                <span text-secondary>&#65291;</span>
                                                <small>ADD NEW PRODUCT</small>
                                            </a>
                                        </div>
                                    </li>
                                </div> -->

                                <div class='d-flex flex-row'>
                                    <li class="nav-item dropdown pl-lg-5 pl-md-4 d-flex flex-row">
                                        <a class="nav-link" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"> 
                                            <div class="d-flex flex-row">
                                                
                                                <div class='<?= isCurrentPage('manage') ? "underline" : "" ?> dropdown-toggle'>
                                                    MANAGE 
                                                </div>
                                                
                                            </div>
                                        </a>
                                        <div class="dropdown-menu py-0">
                                            <a class="dropdown-item py-3" href="admin-manage-users.php?id=<?=$userId?>">
                                                <i class="fas fa-spinner pr-2 text-secondary"></i>
                                                <small>USERS</small>
                                            </a>
                                            <a class="dropdown-item py-3" href="admin-manage-stores.php?id=<?=$userId?>">
                                                <i class="fas fa-shipping-fast pr-1 text-secondary"></i>
                                                <small>STORES</small>
                                            </a>
                                            <a class="dropdown-item py-3" href="admin-manage-store-applications.php?id=<?=$userId?>">
                                                <i class="fas fa-clipboard-check pr-2 text-secondary"></i>
                                                <small>APPLICATIONS</small>
                                            </a>
                                            <a class="dropdown-item py-3" href="admin-manage-ads.php?id=<?=$userId?>">
                                                <i class="fas fa-eraser pr-2 text-secondary"></i>
                                                <small>ADS</small>
                                            </a>
                                        </div>
                                    </li>
                                    <!-- <li><span class='badge text-light my-0'>0</span></li> -->
                                </div>
                                

                                <div class='d-flex flex-row'>
                                    <li class="nav-item pl-lg-5 pl-md-4 d-flex flex-row">
                                        <a class="nav-link" href="admin-messages.php?id=<?=$userId?>"> 
                                            <div class='<?= isCurrentPage('messages') ? "underline" : "" ?>'>MESSAGES</div>
                                        </a>
                                    </li>
                                    <!-- <li><span class='badge text-light my-0 message-badge$storeId'>0</span></li></li> -->
                                </div>

                                <div class='d-flex flex-row'>
                                    <li class="nav-item pl-lg-5 pl-md-4 d-flex flex-row">
                                        <a class="nav-link" href="admin-account.php?id=<?=$userId?>"> 
                                            <div class='<?= isCurrentPage('account') ? "underline" : "" ?>'>ACCOUNT</div>
                                        </a>
                                    </li>
                                    <!-- <li><span class='badge text-light my-0 message-badge$storeId'>0</span></li></li> -->
                                </div>

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


    
   
