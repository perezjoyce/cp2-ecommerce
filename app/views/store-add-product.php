<?php require_once "../../config.php";?>
<?php require_once "../controllers/connect.php";?>
<?php require_once "../controllers/functions.php";?>
<?php require_once "../partials/store_header.php";?>

<?php 
    
    $id = $_GET['id'];
    if(empty($id)){ 
        header("location: index.php");
    } else {

        $storeInfo = $storeId = getStore ($conn,$id);
        $id = $_SESSION['id'];
        $currentUser = getUser($conn, $id);
        $isSeller = $currentUser['isSeller'] == "yes" ? 1 : 0;   
        
        $userIsStoreOwner = false;
        //IF USER IS NOT STORE OWNER, REDIRECT TO ORIGIN
        if($id === $storeInfo['user_id']) {
            $userIsStoreOwner = true;
        } else {
            echo '<script>history.go(-1);</script>';
        }
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
    <div class="container p-0 my-lg-5 mt-md-5" id='store_page_container'>


        <div class="row mx-0">

          
            <!-- MAIN BAR-->
            <div class="col">


                <!-- SECOND ROW -->
                <div class='container p-0'>
                    <div class="row">

                        
                        <div class="col-lg-6 col-md-6">

                          
                            <!-- PRODUCT BASIC INFO -->
                            <div class="container p-5 rounded mb-5" style='background:white;'>
                                <div class="row mb-3">
                                    <div class="col">
                                        <h4>1. Create Product Overview</h4>
                                    </div>
                                </div>
                                
                                <div class="row border-top">
                                    <div class="col">
                                        <div class="container px-0">

                                            <div class="row mt-5">
                                                <div class="col">
                                                    <form action="../controllers/process_add_product.php" method="POST" id="form_edit_user">
                                                        <input type="hidden" name="id" id="id" value="<?= $id ?>">

                                                        <!-- PRODUCT NAME -->
                                                        <div class="form-group row mb-5">
                                                            <label for='product_name' class='col-lg-3 col-md-3 col-sm-12'>Name</label>
                                                            <div class="input-group col-lg-9 col-md-9 col-sm-12">
                                                                <input type="text" class='form-control' id='product_name'
                                                                maxlength="40">
                                                            </div>
                                                        </div>

                                                        <!-- PARENT CATEGORY -->
                                                        <div class="form-group row mb-5">
                                                            <label for='product_category' class='col-lg-3 col-md-3 col-sm-12'>Category</label>
                                                            <div class="input-group col-lg-9 col-md-9 col-sm-12">
                                                                <select class="custom-select" id="product_category">
                                                                    <option selected>Choose...</option>
                                                                    <?php 
                                                                        $sql = "SELECT * FROM tbl_categories WHERE parent_category_id IS NULL";
                                                                            $statement = $conn->prepare($sql);
                                                                            $statement->execute();
                                                                        while($row = $statement->fetch()){
                                                                            $parentCategoryId = $row['id'];
                                                                            $parentCategoryName = $row['name'];
                                                                    ?>
                                                                    <option value="<?=$parentCategoryId?>"><?=$parentCategoryName?></option>
                                                                    <?php } ?>
                                                                </select>
                                                                <div class="input-group-append">
                                                                    <label class="input-group-text" for="product_category">Options</label>
                                                                </div>
                                                            </div>
                                                        </div>

                                                       
                                                        <!-- SUBCATEGORY -->
                                                        <div class="form-group row mb-5">
                                                            <label for='product_subcategory' class='col-lg-3 col-md-3 col-sm-12'>Type</label>
                                                            <div class="input-group col-lg-9 col-md-9 col-sm-12">
                                                                <select class="custom-select" id="product_subcategory">
                                                                    <option selected>...</option>
                                                                </select>
                                                                <div class="input-group-append">
                                                                    <label class="input-group-text" for="product_subcategory">Options</label>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <!-- BRAND -->
                                                        <div class="form-group row mb-5">
                                                            <label for='product_brand' class='col-lg-3 col-md-3 col-sm-12'>Brand</label>
                                                            <div class="input-group col-lg-9 col-md-9 col-sm-12">
                                                                <select class="custom-select" id="product_brand">
                                                                    <option selected>...</option>
                                                                </select>
                                                                <div class="input-group-append">
                                                                    <label class="input-group-text" for="product_brand">Options</label>
                                                                </div>
                                                            </div>
                                                        </div>


                                                        <!-- PRICE -->
                                                        <div class="form-group row mb-5">
                                                            <label for='unsername' class='col-lg-3 col-md-3 col-sm-12'>Price</label>
                                                            <div class="input-group col-lg-9 col-md-9 col-sm-12">
                                                                <div class="input-group-prepend">
                                                                    <span class="input-group-text">&#x20B1;</span>
                                                                </div>
                                                                <input type="number" step=".01" placeholder='0.00' class="form-control" aria-label="Amount" id='product_price'>
                                                            </div>
                                                            <div class='validation'></div>
                                                        </div>

                                                       

                                                        
                
                                                        <p id="edit_user_error"></p>

                                                        

                                                        <div class="container px-0">
                                                            <!-- CHECKOUT BUTTON -->
                                                            <div class="row">
                                                                <div class="col-lg-8 col-md-6"></div>
                                                                <div class="col-lg-4 col-md-6 col-sm-12"> 
                                                                    <a class='btn btn-block py-3 btn-purple-reverse save_new_product' role='button' data-id='<?=$storeId?>'>
                                                                        <small>SAVE CHANGES</small>    
                                                                    
                                                                    </a>

                                                                </div>
                                                            </div>
                                                        </div>
                                                            

                                                    </form>
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                            </div>

                             <!-- PRODUCT DETAILS -->
                            <div class="container p-5 rounded mb-5" style='background:white;'>
                                <div class="row mb-3">
                                    <div class="col">
                                        <h4>
                                            <span class='vanish-lg vanish-md'>2.</span>
                                            <span class='vanish-sm'>3.</span>
                                            Include Product Details
                                        </h4>
                                    </div>
                                </div>
                                
                                <div class="row border-top">
                                    <div class="col">
                                        <div class="container px-0">

                                            <div class="row mt-5">
                                                <div class="col">
                                                    <form action="../controllers/process_add_product.php" method="POST" id="form_edit_user">
                                                        <input type="hidden" name="id" id="id" value="<?= $id ?>">

                                                    
                                                        <div class="input-group mb-4">
                                                            <div class="input-group-prepend">
                                                                <span class="input-group-text">a.</span>
                                                            </div>
                                                            <textarea class="form-control" aria-label="With textarea"></textarea>
                                                        </div>

                                                        <div class="input-group mb-4">
                                                            <div class="input-group-prepend">
                                                                <span class="input-group-text">b.</span>
                                                            </div>
                                                            <textarea class="form-control" aria-label="With textarea"></textarea>
                                                        </div>

                                                        <div class="input-group mb-4">
                                                            <div class="input-group-prepend">
                                                                <span class="input-group-text">c.</span>
                                                            </div>
                                                            <textarea class="form-control" aria-label="With textarea"></textarea>
                                                        </div>

                                                        <div class="input-group mb-4">
                                                            <div class="input-group-prepend">
                                                                <span class="input-group-text">d.</span>
                                                            </div>
                                                            <textarea class="form-control" aria-label="With textarea"></textarea>
                                                        </div>

                                                        <div class="input-group mb-4">
                                                            <div class="input-group-prepend">
                                                                <span class="input-group-text">e.</span>
                                                            </div>
                                                            <textarea class="form-control" aria-label="With textarea"></textarea>
                                                        </div>

                                                        <div class="input-group mb-4">
                                                            <div class="input-group-prepend">
                                                                <span class="input-group-text">f.</span>
                                                            </div>
                                                            <textarea class="form-control" aria-label="With textarea"></textarea>
                                                        </div>

                                                        <div class="input-group mb-4">
                                                            <div class="input-group-prepend">
                                                                <span class="input-group-text">g.</span>
                                                            </div>
                                                            <textarea class="form-control" aria-label="With textarea"></textarea>
                                                        </div>

                                                        <div class="input-group mb-5">
                                                            <div class="input-group-prepend">
                                                                <span class="input-group-text">h.</span>
                                                            </div>
                                                            <textarea class="form-control" aria-label="With textarea"></textarea>
                                                        </div>

                                                        
                                                        <p id="edit_user_error"></p>

                                                        

                                                        <div class="container px-0">
                                                            <!-- CHECKOUT BUTTON -->
                                                            <div class="row">
                                                                <div class="col-lg-8 col-md-6">

                                                                    <!-- <a class='btn btn-block py-3 border save_address_edit' id="btn_edit_user" role='button'>
                                                                        <small>USE EXISTING</small>    
                                                                    </a> -->

                                                                </div>
                                                                <!-- <div class="col-lg-4 vanish-md vanish-sm">

                                                                </div> -->
                                                                <div class="col-lg-4 col-md-6 col-sm-12"> 
                                                                    <a class='btn btn-block py-3 btn-purple-reverse save_address_edit' id="btn_edit_user" role='button'>
                                                                        <small>SAVE CHANGES</small>    
                                                                    
                                                                    </a>
                                                                </div>
                                                            </div>
                                                        </div>
                                                            

                                                    </form>
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                            </div>
  

                        </div>


                        <div class="col-lg-6 col-md-6">

                            <!-- VARIATIONS -->
                            <div class="container p-5 rounded mb-5" style='background:white;'>
                                <div class="row mb-3">
                                    <div class="col">
                                        <h4>
                                            <span class='vanish-lg vanish-md'>3.</span>
                                            <span class='vanish-sm'>2.</span>
                                            Add Product Variations & Stocks
                                        </h4>
                                    </div>
                                </div>
                                
                                <div class="row border-top">
                                    <div class="col">
                                        <div class="container px-0">

                                            <div class="row mt-5">
                                                <div class="col">
                                                    <form action="../controllers/process_add_product.php" method="POST" id="form_edit_user">
                                                        <input type="hidden" name="id" id="id" value="<?= $id ?>">

                                                    
                                                        <div class="input-group mb-4">
                                                            <div class="input-group-prepend">
                                                                <span class="input-group-text" id="">a.</i></span>
                                                            </div>
                                                            <input type="text" class="form-control" placeholder='Name'>
                                                            <input type="number" class="form-control" placeholder='Available Stock'>
                                                        </div>

                                                        <div class="input-group mb-4">
                                                            <div class="input-group-prepend">
                                                                <span class="input-group-text" id="">b.</span>
                                                            </div>
                                                            <input type="text" class="form-control" placeholder='Name'>
                                                            <input type="number" class="form-control" placeholder='Available Stock'>
                                                        </div>

                                                        <div class="input-group mb-4">
                                                            <div class="input-group-prepend">
                                                                <span class="input-group-text" id="">c.</span>
                                                            </div>
                                                            <input type="text" class="form-control" placeholder='Name'>
                                                            <input type="number" class="form-control" placeholder='Available Stock'>
                                                        </div>

                                                        <div class="input-group mb-4">
                                                            <div class="input-group-prepend">
                                                                <span class="input-group-text" id="">d.</span>
                                                            </div>
                                                            <input type="text" class="form-control" placeholder='Name'>
                                                            <input type="number" class="form-control" placeholder='Available Stock'>
                                                        </div>

                                                        <div class="input-group mb-4">
                                                            <div class="input-group-prepend">
                                                                <span class="input-group-text" id="">e.</span>
                                                            </div>
                                                            <input type="text" class="form-control" placeholder='Name'>
                                                            <input type="number" class="form-control" placeholder='Available Stock'>
                                                        </div>

                                                        <div class="input-group mb-4">
                                                            <div class="input-group-prepend">
                                                                <span class="input-group-text" id="">f.</span>
                                                            </div>
                                                            <input type="text" class="form-control" placeholder='Name'>
                                                            <input type="number" class="form-control" placeholder='Available Stock'>
                                                        </div>


                                                        <div class="input-group mb-5">
                                                            <div class="input-group-prepend">
                                                                <span class="input-group-text" id="">g.</span>
                                                            </div>
                                                            <input type="text" class="form-control" placeholder='Name'>
                                                            <input type="number" class="form-control" placeholder='Available Stock'>
                                                        </div>

                                                        <p id="edit_user_error"></p>

                                                        

                                                        <div class="container px-0">
                                                            <!-- CHECKOUT BUTTON -->
                                                            <div class="row">
                                                                <div class="col-lg-8 col-md-6"></div>
                                                                <div class="col-lg-4 col-md-6 col-sm-12"> 
                                                                    <a class='btn btn-block py-3 btn-purple-reverse save_address_edit' id="btn_edit_user" role='button'>
                                                                        <small>SAVE CHANGES</small>    
                                                                    
                                                                    </a>

                                                                </div>
                                                            </div>
                                                        </div>
                                                            

                                                    </form>
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                            </div>

                              <!-- FAQS -->
                            <div class="container p-5 rounded mb-5" style='background:white;'>
                                <div class="row mb-3">
                                    <div class="col">
                                        <h4>4. Add FAQs</h4>
                                    </div>
                                </div>
                                
                                <div class="row border-top">
                                    <div class="col">
                                        <div class="container px-0">

                                            <div class="row mt-5">
                                                <div class="col">
                                                    <form action="../controllers/process_add_product.php" method="POST" id="form_edit_user">
                                                        <input type="hidden" name="id" id="id" value="<?= $id ?>">

                                                    
                                                        <div class="input-group mb-4">
                                                            <div class="input-group-prepend">
                                                                <span class="input-group-text" id="">a.</span>
                                                            </div>
                                                            <input type="text" class="form-control" placeholder='Question'>
                                                            <input type="text" class="form-control" placeholder='Answer'>
                                                        </div>

                                                        <div class="input-group mb-4">
                                                            <div class="input-group-prepend">
                                                                <span class="input-group-text" id="">b.</span>
                                                            </div>
                                                            <input type="text" class="form-control" placeholder='Question'>
                                                            <input type="text" class="form-control" placeholder='Answer'>
                                                        </div>

                                                        <div class="input-group mb-4">
                                                            <div class="input-group-prepend">
                                                                <span class="input-group-text" id="">c.</span>
                                                            </div>
                                                            <input type="text" class="form-control" placeholder='Question'>
                                                            <input type="text" class="form-control" placeholder='Answer'>
                                                        </div>

                                                        <div class="input-group mb-4">
                                                            <div class="input-group-prepend">
                                                                <span class="input-group-text" id="">d.</span>
                                                            </div>
                                                            <input type="text" class="form-control" placeholder='Question'>
                                                            <input type="text" class="form-control" placeholder='Answer'>
                                                        </div>

                                                        <div class="input-group mb-4">
                                                            <div class="input-group-prepend">
                                                                <span class="input-group-text" id="">e.</span>
                                                            </div>
                                                            <input type="text" class="form-control" placeholder='Question'>
                                                            <input type="text" class="form-control" placeholder='Answer'>
                                                        </div>

                                                        <div class="input-group mb-4">
                                                            <div class="input-group-prepend">
                                                                <span class="input-group-text" id="">f.</span>
                                                            </div>
                                                            <input type="text" class="form-control" placeholder='Question'>
                                                            <input type="text" class="form-control" placeholder='Answer'>
                                                        </div>

                                                        <div class="input-group mb-4">
                                                            <div class="input-group-prepend">
                                                                <span class="input-group-text" id="">g.</span>
                                                            </div>
                                                            <input type="text" class="form-control" placeholder='Question'>
                                                            <input type="text" class="form-control" placeholder='Answer'>
                                                        </div>

                                                        <div class="input-group mb-4">
                                                            <div class="input-group-prepend">
                                                                <span class="input-group-text" id="">h.</span>
                                                            </div>
                                                            <input type="text" class="form-control" placeholder='Question'>
                                                            <input type="text" class="form-control" placeholder='Answer'>
                                                        </div>

                                                        <div class="input-group mb-4">
                                                            <div class="input-group-prepend">
                                                                <span class="input-group-text" id="">i.</span>
                                                            </div>
                                                            <input type="text" class="form-control" placeholder='Question'>
                                                            <input type="text" class="form-control" placeholder='Answer'>
                                                        </div>

                                                        <div class="input-group mb-5">
                                                            <div class="input-group-prepend">
                                                                <span class="input-group-text" id="">j.</span>
                                                            </div>
                                                            <input type="text" class="form-control" placeholder='Question' >
                                                            <input type="text" class="form-control" placeholder='Answer'>
                                                        </div>


                                                        
                                                        <p id="edit_user_error"></p>

                                                        

                                                        <div class="container px-0">
                                                            <!-- CHECKOUT BUTTON -->
                                                            <div class="row">
                                                                <div class="col-lg-8 col-md-6">

                                                                    <!-- <a class='btn btn-block py-3 border save_address_edit' id="btn_edit_user" role='button'>
                                                                        <small>USE EXISTING</small>    
                                                                    </a> -->

                                                                </div>
                                                                <!-- <div class="col-lg-4 vanish-md vanish-sm">

                                                                </div> -->
                                                                <div class="col-lg-4 col-md-6 col-sm-12"> 
                                                                    <a class='btn btn-block py-3 btn-purple-reverse save_address_edit' id="btn_edit_user" role='button'>
                                                                        <small>SAVE CHANGES</small>    
                                                                    
                                                                    </a>
                                                                </div>
                                                            </div>
                                                        </div>
                                                            

                                                    </form>
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                            </div>
                            

                        </div>

                    </div>

                </div>
                <!-- /SECOND ROW -->


                
                <!-- HEADING -->
                <div class='container p-5 rounded mb-5' style='background:white;'>
                    <div class="row mx-0 d-flex align-items-center">

                        <div class='flex-fill'>
                            <h4>5. Upload Product Images</h4>

                            <div class="text-gray pl-4">
                                <small>File size: Max of 1MB</small>
                            </div>
                            <div class="text-gray pl-4">
                                <small>File extension: jpg, jpeg, png</small>
                            </div>
                        </div>

                        <div class='flex-fill'>  
                            <div class='d-flex flex-row'>
                                <a class='nav-link modal-link text-gray btn border' href='#' data-id='<?= $id ?>' data-url='../partials/templates/upload_product_pic_modal.php' role='button'>
                                    <i class="fas fa-camera pr-2"></i>
                                    <small class='pr-2'>ADD PRIMARY IMAGE</small>
                                    <i class="far fa-question-circle text-gray" data-toggle="tooltip" title="This will be your product's profile picture." data-original-title="#"></i>
                                </a>
                            </div>
                        </div>


                        <div class='flex-fill'>  
                            <div class='d-flex flex-row'>
                                <!-- STILL NEEDS TO PASS VALUE THROUGH GET TO upload_product_pic_modal.php -->
                                <a class='nav-link modal-link text-gray btn border' href='#' data-id='<?= $storeId ?>' data-url='../partials/templates/upload_modal.php' role='button'>
                                    <i class="fas fa-camera pr-2"></i>
                                    <small class='pr-2'>ADD OTHER IMAGES</small>
                                </a>
                            </div>
                        </div>
					</div>
                </div>


                <!-- PRODUCT IMAGES -->
                <div class="container p-0" id='store_page_product_container'>
                    
                    <div class="row no-gutters justify-content-left">
                      

                        <div class="col-lg-3 col-md-4 col-sm-6 p-2">
                            <a href="product.php?id=<?= $id ?>">
                            <div class='card border-0'>
                                <a href="product.php?id=<?= $row2['id'] ?>">
                                <img class='card-img-top' src="https://via.placeholder.com/250x250">
                                </a> 
                            </div>
                            </a>
                        </div>

                        <div class="col-lg-3 col-md-4 col-sm-6 p-2">
                            <a href="product.php?id=<?= $id ?>">
                            <div class='card border-0'>
                                <a href="product.php?id=<?= $row2['id'] ?>">
                                <img class='card-img-top' src="https://via.placeholder.com/250x250">
                            </div>
                            </a>
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

  