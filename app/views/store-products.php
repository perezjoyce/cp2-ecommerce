<?php require_once "../../config.php";?>
<?php require_once "../controllers/connect.php";?>
<?php require_once "../controllers/functions.php";?>

<?php 
    // if(!isset($_SESSION['id'])) {
    //     // ob_clean();
    //     // header("location: index.php?msg=NotLoggedIn"); // doesn't work because header already exists
    //     // ECHO THIS TO REDIRECT YOU TO HEADER
    //     echo "<script>window.location.href='".BASE_URL."/app/views/'</script>";
    // }

    $id = $_GET['id'];
    if(empty($id)) {
        header("location: index.php");
    } else {

        // get the store information

        $storeInfo = $storeId = getStore ($conn,$id);
        $id = $_SESSION['id'];
        $currentUser = getUser($conn, $id);
        $isSeller = $currentUser['isSeller'] == "yes" ? 1 : 0;    
    }  

    if ($isSeller && $currentUser['id'] == $storeInfo['user_id']) {
        require_once "../partials/store_header.php";
    } else {
        require_once "../partials/header.php";
    }

    $storeId = $storeInfo['id'];
    $storeName = $storeInfo['name'];
    $storeLogo = $storeInfo['logo'];
    $storeDescription = $storeInfo['description'];
    $storeAddress = $storeInfo['store_address'];
    $storeHours = $storeInfo['hours'];
    $storeFollowers = countFollowers ($conn, $storeId);
    $storeRating = getAverageStoreRating ($conn, $storeId);
    $storeMembershipDate = getMembershipDate($conn, $storeId);
    $storeShippingFee = displayStoreShippingFee($conn,$storeId);
    $storeFreeShippingMinimum = displayStoreFreeShipping($conn,$storeId);
    $fname = getFirstName ($conn,$id);
    $lname = getLastName ($conn,$id);
?>
    <!-- PAGE CONTENT -->
    <br>
    <div class="container p-0 my-lg-5 mt-md-5">

        


        <div class="row mx-0">

          
            <!-- MAIN BAR-->
            <div class="col">

                <!-- SEARCH BAR -->
                <div class='container p-5 rounded' style='background:white;'>
                    <div class="row mx-0">
                        <div class="col-6">
                            <h4>Product Inventory</h4>
                        </div>
                        <div class="col">
                            <div class="input-group input-group-lg">
                                <div class="input-group-prepend">
                                    <span class="input-group-text border-right-0 border-left-0 border-top-0" id="store_page_search_button" style='background:white;'>
                                        <i class="fas fa-search" style='background:white;'></i>
                                    </span>
                                </div>
                                <input type="text" class="form-control border-right-0 border-left-0 border-top-0" id="store_page_search">
                            </div>
                        </div>
						
					</div>
                </div>

                
                
                <!-- NEW ORDERS -->
                <div class="container px-5 pb-5 rounded" style='background:white;'>
                               
                    <div class="row">
                        <div class="col-12 px-2 mb-0">
                            
                            <table class="table borderless text-center bg-gray mb-0">
                                <tr class='py-0'>

                                    <td width='10%'>Id</td>
                                    <td width='20%'>Product</td>
                                    <td width='20%'>Price</td>
                                    <td width='15%'>Variation & Stock</td>
                                    <td width='20%'>Total Stocks</td>
                                    <td width='15%'>Action</td>

                                    
                                </tr> 
                            </table>
                               
                        </div>
                    </div>
                   
                    <div class="row">
                        <div class="col px-2">
                            <div class="container px-0" style='background:white;height:600px;overflow-y:auto;'>
                                <table class="table table-hover borderless text-center">
                                    
                                <?php 
                                    $sql = "SELECT * FROM tbl_items WHERE store_id = ?";
                                    $statement = $conn->prepare($sql);
                                    $statement->execute([$storeId]);
                                    $count = $statement->rowCount();

                                    if($count) {
                                        while($row = $statement->fetch()){
                                        $productId = $row['id'];
                                        $productName = $row['name'];
                                        $productPrice = $row['price'];
                                        $productSubCategoryId = $row['category_id'];
                                        $productBrandId = $row['brand_id'];
                                        $productPrimaryImage = $row['img_path'];

                                        // $productBrandName = getBrandName($conn,$productBrandId);
                                        // $productCategoryRow = getCategoryRow($conn,$productSubCategoryId);
                                        // $productSubCategoryName = $productCategoryRow['name'];
                                        // $productMainCategoryId =  $productCategoryRow['parent_category_id'];
                                        // $productMainCategoryName = getCategoryName($conn,$productMainCategoryId);
                                    

                    
                                ?>
                                    <tr>
                                        <div>

                                            <!-- PRODUCT ID -->
                                            <td class='mx-0' width='10%'> 
                                                <a data-url="../partials/templates/view_order_summary_modal.php" data-id='#' class='border-0 btn_view_order_history' style='cursor:pointer;size:15px;'>
                                                    <div class='py-3 text-secondary'>
                                                       <?= $productId ?>
                                                    </div>
                                                </a>
                                            </td>
                                            <!-- PRODUCT NAME -->
                                            <td class='mx-0' width='20%'> 
                                                <a data-url="../partials/templates/view_order_summary_modal.php" data-id='#' class='border-0 btn_view_order_history' style='cursor:pointer;size:15px;'>
                                                    <div class='py-3 text-secondary'>
                                                       <?= $productName ?>
                                                    </div>
                                                </a>
                                            </td>
                                            
                                            
                                            <!-- PRICE -->
                                            <td class='mx-0' width='20%'> 
                                                <a data-url="../partials/templates/view_order_summary_modal.php" data-id='#' class='border-0 btn_view_order_history' style='cursor:pointer;size:15px;'>
                                                    <div class='py-3 text-secondary'>
                                                        <span>&#8369;&nbsp;</span>
                                                        <span><?= $productPrice ?></span>
                                                    </div>
                                                </a>
                                            </td>

                                             <!-- VARIATION -->
                                             <td class='mx-0' width='15%'>
                                                <a data-url="../partials/templates/view_order_summary_modal.php" data-id='#' class='border-0 btn_view_order_history' style='cursor:pointer;size:15px;'>
                                                    <div class='py-3 text-gray'>
                                                        <div class="dropdown show">
                                                            <a class="btn border dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                                <small>QUICK VIEW</small>    
                                                            </a>

                                                            <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                                                                <a class="dropdown-item" href="#">
                                                                    <table class='borderless text-center'>
                                                                        <tr>
                                                                            <th class='width:50%;'><small class='font-weight-bold'>VARIATION</small></th>
                                                                            <th class='width:50%;'><small class='font-weight-bold'>STOCK</small></th>
                                                                        </tr>
                                                                <!-- ONCE CLICKED, BUTTON WILL BE CHANGED -->
                                                               <?php 
                                                                    $sql2 = "SELECT * FROM tbl_variations WHERE product_id =?";
                                                                    $statement2 = $conn->prepare($sql2);
                                                                    $statement2->execute([$productId]);

                                                                    $count2 = $statement2->rowCount();

                                                                    if($count2) {
                                                                        while($row2 = $statement2->fetch()){
                                                                        $variationName = $row2['variation_name'];
                                                                        $variationStock = $row2['variation_stock'];
                                                               ?>
                                                                
                                                                        <tr>
                                                                            <td><small><?= $variationName ?></small></td>
                                                                            <td><small><?= $variationStock ?></small></td>
                                                                        </tr>
                                                                    
                                                               
                                                                <?php } } ?>
                                                                    </table>
                                                                </a>
                                                                
                                                                
                                                            </div>
                                                        </div>
                                                    </div>
                                                </a>
                                            </td>

                                            <!-- TOTAL STOCKS -->
                                            <td class='mx-0' width='20%'> 
                                                <div class="d-flex align-items-center py-3">
                                                    <a data-url="../partials/templates/view_order_summary_modal.php" data-id='#' class='btn_view_order_history flex-fill text-center text-secondary' style='cursor:pointer;size:15px;'>
                                                    <?= getTotalProductStocks ($conn,$productId) ?>
                                                    </a>
                                                </div>
                                               
                                            </td>


                                            <!-- ACTION -->
                                            <td class='mx-0' width='15%'> 
                                                <a data-url="../partials/templates/view_order_summary_modal.php" data-id='#' class='border-0 btn_view_order_history' style='cursor:pointer;size:15px;'>
                                                    <div class='py-3 text-gray'>
                                                        <div class="dropdown show">
                                                            <a class="btn border dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                                <small>CHOOSE 1</small>    
                                                            </a>

                                                            <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                                                                <!-- ONCE CLICKED, BUTTON WILL BE CHANGED -->
                                                               
                                                                <a class="dropdown-item" href="#"><small>VIEW</small></a>
                                                                
                                                                <!-- ONCE CLICKED, WILL BE TRANSFERRED TO SHIPPING -->
                                                                <a class="dropdown-item" href="#"><small>UPDATE</small></a>
                                                                <!-- ONCE CLICKED, WILL BE TRANSFERRED TO ORDER HISTORY -->
                                                                <a class="dropdown-item" href="#"><small>DELETE</small></a>
                                                                
                                                        </div>
                                                        <!-- put dropdown with two buttons: SEND MESSAGE, CONFIRM, CANCELL, COMPLETE -->
                                                    </div>
                                                </a>
                                            </td>

                                            
                                            
                                        </div>
                                        
                                    </tr>
                                <?php } } ?>
        
                                </table>

                                
                            
                            </div>
                        </div>
                    </div>
               

                               
                </div>
                            


                
            </div>
            <!-- /MAIN BAR -->


        </div>
        <!-- /.ROW -->


    </div>
    <!-- /.CONTAINER -->

    <!-- IF USER IS LOGGED IN AND USER IS NOT THE SELLER -->
    <?php if(isset($_SESSION['id']) && !$isSeller){ include '../partials/message_box.php'; } ?>

<?php require_once "../partials/footer.php";?>
<?php require_once "../partials/modal_container.php";?>
<?php require_once "../partials/modal_container_big.php"; ?>

  