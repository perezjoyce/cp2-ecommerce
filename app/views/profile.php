<?php require_once "../../config.php";?>
<?php require_once "../partials/header.php";?>
<?php require_once "../controllers/connect.php";?>

<?php 

    if(!isset($_SESSION['id'])) {
        // ob_clean();
        // header("location: index.php?msg=NotLoggedIn"); // doesn't work because header already exists
        // ECHO THIS TO REDIRECT YOU TO HEADER
        echo "<script>window.location.href='".BASE_URL."/app/views/'</script>";
    }

    $id = $_GET['id'];
    if(empty($id)) {
        header("location: index.php");
    } else {

        $id = $_SESSION['id'];
        $sql = "SELECT * FROM tbl_users WHERE id = ? ";

        $statement = $conn->prepare($sql);
        $statement->execute([$id]);
        $row = $statement->fetch();

        $id = $row['id'];      
        $fname = ucfirst($row['first_name']);   
        $lname = ucfirst($row['last_name']);  
        $username = $row['username'];      
        $email = $row['email'];  
        $profile_pic = $row['profile_pic'];

        if($profile_pic == "") {
            $profile_pic = DEFAULT_PROFILE; 
        } else {
            $profile_pic = BASE_URL . $profile_pic . "_80x80.jpg";
        } 
    }  
       
?>
    <!-- PAGE CONTENT -->
    <br>
    <div class="container border p-0 mt-5">
        <div class="row">

            <!-- SIDE BAR -->
            <div class="col-lg-2 col-md-4">
                <div class="container">
                    <!-- PROFILE PICTURE -->
                    <div class="row">
                        <div class="col-lg-4">
                            <img src='<?= $profile_pic ?>'  class="user-photo circle" height="80">
                        </div>
                        <div class="col">
                            <div class="mt-4"> Hello, <span class="font-weight-bold"><?= $username ?></span>! </div>
                        </div>
                    </div>
                    <hr>

                    <!-- MY PROFILE -->
                    <div class="row">
                        <a class='list-group-item btn border-0 btn-block text-left mx-3 mb-1' role='button' href="profile.php?id=<?= $id ?>" style='background:#f5f5f5;'>
                            <i class='far fa-user mr-3'></i>
                            Profile
                        </a>
                    </div>

                    <!-- SHIPPING ADDRESS -->
                    <div class="row">
                        <a class='list-group-item btn border-0 btn-block text-left mx-3 mb-1 btn_view_addresses' role='button' data-id='<?= $id ?>' style='background:#f5f5f5;'>
                            <i class="far fa-address-book mr-3"></i>
                            Shipping Addresses
                        </a>
                    </div>

                    <!-- ORDER HISTORY -->
                    <div class="row">
                        <a class='list-group-item btn border-0 btn-block text-left mx-3 btn_view_wishList' role='button' data-id='<?= $id ?>' style='background:#f5f5f5;'>
                            <i class="fas fa-history mr-3"></i>  
                            Order History
                        </a>
                    </div>

                    <!-- WISH LIST -->
                    <div class="row">
                        <a class='list-group-item btn border-0 btn-block text-left mx-3 btn_view_wishList' role='button' data-id='<?= $id ?>' style='background:#f5f5f5;'>
                            <i class="far fa-heart mr-3"></i>
                            Wish List 
                            <span class='badge text-light user_wish_count'><?= getWishlishtCount($conn) ?></span>
                        </a>
                    </div>

                    <!-- MESSAGES -->
                    <div class="row">
                        <a class='list-group-item btn border-0 btn-block text-left mx-3 btn_view_wishList' role='button' data-id='<?= $id ?>' style='background:#f5f5f5;'>
                            <i class="far fa-comments mr-3"></i>
                            Messages
                           
                        </a>
                    </div>
                </div>
            </div>
            <!-- /SIDE BAR -->

            <!-- MAIN BAR-->
            <div class="col rounded mr-5" style='background:white;'>
                <div class='container' id="main-wrapper">
                    <div class="row pt-5 pl-5 flex-column">
                        <div class="col">
                            <h4>My Profile</h4>
                            <a class='nav-link modal-link px-0' href='#' data-id='<?= $id ?>' data-url='../partials/templates/edit_user_modal.php' role='button'>
                                <i class="far fa-edit"></i>
                                Edit Profile
                            </a>
                        </div>
                    </div>

                    <hr class="mb-5">
                    
                    <div class="row">
                        <!-- LEFT OF MAIN BAR -->
                        <div class="col-lg-8 border-right pl-5">
                            <div class="container">

                                <div class="row mb-5">
                                    <div class="col-lg-3">
                                        Name
                                    </div>
                                    <div class="col">
                                        <?= $fname . " " . $lname ?>
                                    </div>
                                </div>  

                                <div class="row mb-5">
                                    <div class="col-lg-3">
                                        Username
                                    </div>
                                    <div class="col">
                                        <?= $username ?>
                                    </div>
                                </div>

                                <div class="row mb-5">
                                    <div class="col-lg-3">
                                        Email
                                    </div>
                                    <div class="col">
                                        <?= hide_email($email) ?>
                                    </div>
                                </div>

                            </div>

                        </div>
                        <!-- /LEFT OF MAIN BAR -->

                        <!-- RIGHT OF MAIN BAR -->
                        <div class="col">
                            <div class="row justify-content-center mb-5">
                                <img src='<?= $profile_pic ?>'  class="user-photo circle" height="80">
                            </div>
                            <div class="row justify-content-center">
                                <a class='nav-link modal-link btn border mb-5' href='#' data-id='<?= $id ?>' data-url='../partials/templates/upload_modal.php' role='button'>
                                    <i class="fas fa-camera"></i>
                                    Update Image
                                </a>
                            </div>

                            <div class="row text-center justify-content-center flex-column mb-5">
                                <div>File size: Max of 1MB</div>
                                <div>File extension: .JPG, .JPEG, .PNG</div>
                            </div>
                        </div>
                        <!-- /RIGHT OF MAIN BAR -->
                    </div>
                </div>
                <!-- /#MAIN-WRAPPER -->
            </div>
            <!-- /MAIN BAR -->


        </div>
        <!-- /.ROW -->
    </div>
    <!-- /.CONTAINER -->


<?php require_once "../partials/footer.php";?>
<?php require_once "../partials/modal_container.php";?>
<?php require_once "../partials/modal_container_big.php"; ?>

  