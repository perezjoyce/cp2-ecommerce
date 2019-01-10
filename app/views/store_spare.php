<?php require_once "../../config.php";?>
<?php require_once "../partials/header.php";?>
<?php require_once "../controllers/connect.php";?>
<?php require_once "../controllers/functions.php";?>

<?php 

    if(!isset($_SESSION['id'])) {
        // ob_clean();
        // header("location: index.php?msg=NotLoggedIn"); // doesn't work because header already exists
        // ECHO THIS TO REDIRECT YOU TO HEADER
        echo "<script>window.location.href='".BASE_URL."/app/views/'</script>";
    }

    $id = $_GET['id'];
    if(empty($id)) {
        echo "<script>window.location.href='".BASE_URL."/app/views/'</script>";
        exit;
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
    <div class="container p-0 my-lg-5 mt-md-5">


        <div class="row mx-0">

            <!-- SIDE BAR -->
            <div class="col-lg-2 col-md-2 col-sm-4">
                <div class="container px-lg-0 px-md-0">
                    
                   
                    <!-- MY PROFILE -->
                    <div class="row px-0">
                        <a class='list-group-item btn border-0 pl-3 btn-block text-left' role='button' href="profile.php?id=<?= $id ?>" style='background:#f5f5f5;'>
                            <span class='pr-lg-2 pr-md-1 vanish-sm'><img src='<?= $profile_pic ?>'  class="user-photo circle" height="20"></span>
                            <span class='vanish-lg vanish-md'><img src='<?= $profile_pic ?>'  class="user-photo circle" height="20">&nbsp;&nbsp;</span>
                            <span>Hello,&nbsp;</span>   
                            <span class="font-weight-bold"><?= $username ?></span>
                            <span>!</span>
                        </a>
                    </div>

                    <!-- SHIPPING ADDRESS -->
                    <div class="row px-0">
                        <a class='list-group-item btn border-0 btn-block text-left btn_view_addresses' role='button' data-id='<?= $id ?>' style='background:#f5f5f5;'>
                            <i class="far fa-address-book pr-3"></i>
                            Addresses
                        </a>
                    </div>

                    <!-- ORDER HISTORY -->
                    <div class="row">
                        <a class='list-group-item btn border-0 btn-block text-left btn_view_wishList' role='button' data-id='<?= $id ?>' style='background:#f5f5f5;'>
                            <i class="fas fa-history pr-3"></i>  
                            Order History
                        </a>
                    </div>

                    <!-- WISH LIST -->
                    <div class="row">
                        <a class='list-group-item btn border-0 btn-block text-left btn_view_wishList' role='button' data-id='<?= $id ?>' style='background:#f5f5f5;'>
                            <i class="far fa-heart pr-3"></i>
                            Wish List 
                            <span class='badge text-light user_wish_count'><?= getWishlishtCount($conn) ?></span>
                        </a>
                    </div>

                    <!-- MESSAGES -->
                    <div class="row">
                        <a class='list-group-item btn border-0 btn-block text-left btn_view_wishList' role='button' data-id='<?= $id ?>' style='background:#f5f5f5;'>
                            <i class="far fa-comments pr-3"></i>
                            Messages
                           
                        </a>
                    </div>
                </div>
            </div>
            <!-- /SIDE BAR -->

            <!-- MAIN BAR-->
            <div class="col">
                <!-- FIRST ROW -->
                <div class='container p-5 rounded mb-5' style='background:white;'>
                    <div class="row mb-3">
                        <div class="col">
                            <div class='d-flex flex-row'>
                                <div class='flex-fill'>
                                    <div class="d-flex align-items-center">
                                        <div class='pr-3'><img src='<?= $profile_pic ?>'  class="user-photo circle" height="90"></div>
                                        
                                        <h3><?= $fname . " " . $lname ?></h3>
                                    </div>
                                   
                                </div>
                                <div class='flex-fill text-right'>
                                    <div class="d-flex flex-column">
                                        <a class='nav-link modal-link px-0' href='#' data-id='<?= $id ?>' data-url='../partials/templates/upload_modal.php' role='button'>
                                            <i class="fas fa-camera"></i>
                                            Update Image
                                        </a>
                                        <div class="text-gray">
                                            <small>File size: Max of 1MB</small>
                                        </div>
                                        <div class="text-gray">
                                            <small>File extension: jpg, jpeg, png</small>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

                <!-- SECOND ROW -->
                <div class='container p-0 mb-5'>
                    <div class="row">

                        <!-- BASIC INFO -->
                        <div class="col-6">
                            <div class="container p-5 rounded" style='background:white;'>
                                <div class="row mb-3">
                                    <div class="col">
                                        <div class='d-flex flex-row'>
                                            <div class='flex-fill'>
                                                <h4>Basic Information</h4>
                                            </div>
                                            <div class='flex-fill text-right'>
                                                <a class='nav-link modal-link' href='#' data-id='<?= $id ?>' data-url='../partials/templates/edit_user_modal.php' role='button'>
                                                    <i class="far fa-edit"></i>
                                                    Edit
                                                </a>
                                                
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="row border-top">
                                    <!-- LEFT OF MAIN BAR -->
                                    <div class="col">
                                        <div class="container px-0">

                                            <div class="row my-5">
                                                <div class="col-3">
                                                    Name
                                                </div>
                                                <div class="col">
                                                    <?= $fname . " " . $lname ?>
                                                </div>
                                            </div>  

                                            <div class="row mb-5">
                                                <div class="col-3">
                                                    Username
                                                </div>
                                                <div class="col">
                                                    <?= $username ?>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-3">
                                                    Email
                                                </div>
                                                <div class="col">
                                                    <?= hide_email($email) ?>
                                                </div>
                                            </div>

                                        </div>

                                    </div>
                                    <!-- /LEFT OF MAIN BAR -->

                                
                                </div>
                                <!-- ================ -->

                            </div>
                        </div>


                        <!-- ADDRESSES -->
                        <div class="col-6">
                            <div class="container p-5 rounded" style='background:white;'>
                                <div class="row mb-3">
                                    <div class="col">
                                        <div class='d-flex flex-row'>
                                            <div class='flex-fill'>
                                                <h4>Addresses</h4>
                                            </div>
                                            <div class='flex-fill text-right'>
                                                <a class='nav-link modal-link' href='#' data-id='<?= $id ?>' data-url='../partials/templates/upload_modal.php' role='button'>
                                                    <i class="far fa-edit"></i>
                                                    Edit
                                                </a>
                                                
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="row border-top">
                                    <div class="col">
                                        <div class="container px-0">

                                            <?php 
                                                $sql = "SELECT * FROM tbl_addresses WHERE `user_id` = ?";
                                                $statement = $conn->prepare($sql);
                                                $statement->execute([$id]);	

                                                $count = $statement->rowCount();

                                                if($count) {
                                                    while($row = $statement->fetch()){
                                                        $addressType = $row['addressType'];
                                                        $addressType = ucwords(strtolower($addressType));
                                                
                                                        $landmark = $row['landmark'];
                                                        $landmark = ucwords(strtolower($landmark));

                                                        $street = $row['street_bldg_unit'];
                                                        $street = ucwords(strtolower($street));

                                                        $regionId = $row['region_id'];
                                                        $provId = $row['province_id'];
                                                        $cityId = $row['city_id'];
                                                        $brgyId = $row['brgy_id'];
                                            ?>

                                             <div class="row mt-5">
                                                <div class="col-3">
                                                    <?=$addressType?>
                                                </div>
                                                <div class="col">
                                                        <?=$street?>,&nbsp;
                                                        <?= getBrgyName($conn, $brgyId) ?>,&nbsp;
                                                        <?= getCityName($conn, $cityId) ?>,&nbsp;
                                                        <?= getProvinceName($conn, $provId) ?>,
                                                        <?= getRegionName($conn, $regionId) ?>&nbsp; -- &nbsp;
                                                        
                                                        <span class='text-gray'>
                                                            <?=$landmark?>
                                                        </span>
                                                    
                                                </div>
                                            </div>  


                                            <?php } } ?>

                                            

                                        </div>
                                    </div>
                                </div>
                                <!-- ================ -->

                            </div>
                        </div>


                    </div>

                    
                    
                   

                </div>
                <!-- /#MAIN-WRAPPER -->



                <div class="container p-5 rounded mb-5" style='background:white;' id="main-wrapper">
                    <div class="row mb-3">
                        <div class="col">
                            <div class='d-flex flex-row'>
                                <div class='flex-fill'>
                                    <h4>Basic Information</h4>
                                </div>
                                <div class='flex-fill text-right'>
                                    <a class='nav-link modal-link' href='#' data-id='<?= $id ?>' data-url='../partials/templates/edit_user_modal.php' role='button'>
                                        <i class="far fa-edit"></i>
                                        Edit
                                    </a>
                                    
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row border-top">
                        <!-- LEFT OF MAIN BAR -->
                        <div class="col-lg-6 col-md-4 col-sm-12">
                            <div class="container px-0">

                                <div class="row my-5">
                                    <div class="col-3">
                                        Name
                                    </div>
                                    <div class="col">
                                        <?= $fname . " " . $lname ?>
                                    </div>
                                </div>  

                                <div class="row mb-5">
                                    <div class="col-3">
                                        Username
                                    </div>
                                    <div class="col">
                                        <?= $username ?>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-3">
                                        Email
                                    </div>
                                    <div class="col">
                                        <?= hide_email($email) ?>
                                    </div>
                                </div>

                            </div>

                        </div>
                        <!-- /LEFT OF MAIN BAR -->

                       
                    </div>
                </div>



            </div>
            <!-- /MAIN BAR -->


        </div>
        <!-- /.ROW -->
    </div>
    <!-- /.CONTAINER -->


<?php require_once "../partials/footer.php";?>
<?php require_once "../partials/modal_container.php";?>
<?php require_once "../partials/modal_container_big.php"; ?>

  