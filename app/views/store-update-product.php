<?php require_once "../../config.php";?>

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
    }
?>

<?php require_once "../partials/store_header.php";?>
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
                                        <div class='d-flex flex-row'>
                                            <div class='flex-fill'>
                                                <h4>Product Overview</h4>
                                            </div>
                                            <?php if ($isSeller && $currentUser['id'] == $storeInfo['user_id']) { ?>    
                                            <div class='flex-fill text-right'>
                                                <a class='nav-link modal-link' href='#' data-id='<?= $id ?>' data-url='../partials/templates/edit_user_modal.php' role='button'>
                                                    <i class="far fa-edit"></i>
                                                    Edit
                                                </a>
                                            </div>
                                            <?php } else { echo ""; } ?>
                                        </div>
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
                                                            <label for='fname' class='col-lg-3 col-md-3 col-sm-12'>Name</label>
                                                            <div class="input-group col-lg-9 col-md-9 col-sm-12">
                                                                <input type="text" class='form-control' id='fname'
                                                                    value="">
                                                            </div>
                                                        </div>

                                                        <!-- PARENT CATEGORY -->
                                                        <div class="form-group row mb-5">
                                                            <label for='lname' class='col-lg-3 col-md-3 col-sm-12'>Category</label>
                                                            <div class="input-group col-lg-9 col-md-9 col-sm-12">
                                                                <select class="custom-select" id="inputGroupSelect02">
                                                                    <option selected>Choose...</option>
                                                                    <option value="1">One</option>
                                                                    <option value="2">Two</option>
                                                                    <option value="3">Three</option>
                                                                </select>
                                                                <div class="input-group-append">
                                                                    <label class="input-group-text" for="inputGroupSelect02">Options</label>
                                                                </div>
                                                            </div>
                                                        </div>

                                                       

                                                     

                                                        <!-- SUBCATEGORY -->
                                                        <div class="form-group row mb-5">
                                                            <label for='lname' class='col-lg-3 col-md-3 col-sm-12'>Type</label>
                                                            <div class="input-group col-lg-9 col-md-9 col-sm-12">
                                                                <select class="custom-select" id="inputGroupSelect02">
                                                                    <option selected>Choose...</option>
                                                                    <option value="1">One</option>
                                                                    <option value="2">Two</option>
                                                                    <option value="3">Three</option>
                                                                </select>
                                                                <div class="input-group-append">
                                                                    <label class="input-group-text" for="inputGroupSelect02">Options</label>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <!-- BRAND -->
                                                        <div class="form-group row mb-5">
                                                            <label for='email' class='col-lg-3 col-md-3 col-sm-12'>Brand</label>
                                                            <div class="input-group col-lg-9 col-md-9 col-sm-12">
                                                                <input type="text" class='form-control' id='email'
                                                                    value="">
                                                            </div>
                                                            <div class='validation'></div>
                                                        </div>


                                                        <!-- USERNAME -->
                                                        <div class="form-group row mb-5">
                                                            <label for='unsername' class='col-lg-3 col-md-3 col-sm-12'>Price</label>
                                                            <div class="input-group col-lg-9 col-md-9 col-sm-12">
                                                                <input type="text" class='form-control' id='username'
                                                                    value="">
                                                            </div>
                                                            <div class='validation'></div>
                                                        </div>

                                                        
                
                                                        <p id="edit_user_error"></p>

                                                                                                                

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
                                        <div class='d-flex flex-row'>
                                            <div class='flex-fill'>
                                                <h4>Product Details</h4>
                                            </div>
                                            <?php if ($isSeller && $currentUser['id'] == $storeInfo['user_id']) { ?>    
                                            <div class='flex-fill text-right'>
                                                <a class='nav-link modal-link' href='#' data-id='<?= $id ?>' data-url='../partials/templates/edit_user_modal.php' role='button'>
                                                    <i class="far fa-edit"></i>
                                                    Edit
                                                </a>
                                            </div>
                                            <?php } else { echo ""; } ?>
                                        </div>
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
                                                                <span class="input-group-text">1.</span>
                                                            </div>
                                                            <textarea class="form-control" aria-label="With textarea"></textarea>
                                                        </div>

                                                        <div class="input-group mb-4">
                                                            <div class="input-group-prepend">
                                                                <span class="input-group-text">2.</span>
                                                            </div>
                                                            <textarea class="form-control" aria-label="With textarea"></textarea>
                                                        </div>

                                                        <div class="input-group mb-4">
                                                            <div class="input-group-prepend">
                                                                <span class="input-group-text">3.</span>
                                                            </div>
                                                            <textarea class="form-control" aria-label="With textarea"></textarea>
                                                        </div>

                                                        <div class="input-group mb-4">
                                                            <div class="input-group-prepend">
                                                                <span class="input-group-text">4.</span>
                                                            </div>
                                                            <textarea class="form-control" aria-label="With textarea"></textarea>
                                                        </div>

                                                        <div class="input-group mb-4">
                                                            <div class="input-group-prepend">
                                                                <span class="input-group-text">5.</span>
                                                            </div>
                                                            <textarea class="form-control" aria-label="With textarea"></textarea>
                                                        </div>

                                                        <div class="input-group mb-4">
                                                            <div class="input-group-prepend">
                                                                <span class="input-group-text">6.</span>
                                                            </div>
                                                            <textarea class="form-control" aria-label="With textarea"></textarea>
                                                        </div>

                                                        <div class="input-group mb-4">
                                                            <div class="input-group-prepend">
                                                                <span class="input-group-text">7.</span>
                                                            </div>
                                                            <textarea class="form-control" aria-label="With textarea"></textarea>
                                                        </div>

                                                        <div class="input-group mb-5">
                                                            <div class="input-group-prepend">
                                                                <span class="input-group-text">8.</span>
                                                            </div>
                                                            <textarea class="form-control" aria-label="With textarea"></textarea>
                                                        </div>

                                                        
                                                        <p id="edit_user_error"></p>

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
                                        <div class='d-flex flex-row'>
                                            <div class='flex-fill'>
                                                <h4>Product Variations</h4>
                                            </div>
                                            <?php if ($isSeller && $currentUser['id'] == $storeInfo['user_id']) { ?>    
                                            <div class='flex-fill text-right'>
                                                <a class='nav-link modal-link' href='#' data-id='<?= $id ?>' data-url='../partials/templates/edit_user_modal.php' role='button'>
                                                    <i class="far fa-edit"></i>
                                                    Edit
                                                </a>
                                            </div>
                                            <?php } else { echo ""; } ?>
                                        </div>
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
                                                                <span class="input-group-text" id="">1.</span>
                                                            </div>
                                                            <input type="text" class="form-control" placeholder='Name'>
                                                            <input type="number" class="form-control" placeholder='Available Stock'>
                                                        </div>

                                                        <div class="input-group mb-4">
                                                            <div class="input-group-prepend">
                                                                <span class="input-group-text" id="">2.</span>
                                                            </div>
                                                            <input type="text" class="form-control" placeholder='Name'>
                                                            <input type="number" class="form-control" placeholder='Available Stock'>
                                                        </div>

                                                        <div class="input-group mb-4">
                                                            <div class="input-group-prepend">
                                                                <span class="input-group-text" id="">3.</span>
                                                            </div>
                                                            <input type="text" class="form-control" placeholder='Name'>
                                                            <input type="number" class="form-control" placeholder='Available Stock'>
                                                        </div>

                                                        <div class="input-group mb-4">
                                                            <div class="input-group-prepend">
                                                                <span class="input-group-text" id="">4.</span>
                                                            </div>
                                                            <input type="text" class="form-control" placeholder='Name'>
                                                            <input type="number" class="form-control" placeholder='Available Stock'>
                                                        </div>

                                                        <div class="input-group mb-4">
                                                            <div class="input-group-prepend">
                                                                <span class="input-group-text" id="">5.</span>
                                                            </div>
                                                            <input type="text" class="form-control" placeholder='Name'>
                                                            <input type="number" class="form-control" placeholder='Available Stock'>
                                                        </div>

                                                        <div class="input-group mb-4">
                                                            <div class="input-group-prepend">
                                                                <span class="input-group-text" id="">6.</span>
                                                            </div>
                                                            <input type="text" class="form-control" placeholder='Name'>
                                                            <input type="number" class="form-control" placeholder='Available Stock'>
                                                        </div>


                                                        <div class="input-group mb-5">
                                                            <div class="input-group-prepend">
                                                                <span class="input-group-text" id="">7.</span>
                                                            </div>
                                                            <input type="text" class="form-control" placeholder='Name'>
                                                            <input type="number" class="form-control" placeholder='Available Stock'>
                                                        </div>

                                                        <p id="edit_user_error"></p>
                                                            

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
                                        <div class='d-flex flex-row'>
                                            <div class='flex-fill'>
                                                <h4>FAQs</h4>
                                            </div>
                                            <?php if ($isSeller && $currentUser['id'] == $storeInfo['user_id']) { ?>    
                                            <div class='flex-fill text-right'>
                                                <a class='nav-link modal-link' href='#' data-id='<?= $id ?>' data-url='../partials/templates/edit_user_modal.php' role='button'>
                                                    <i class="far fa-edit"></i>
                                                    Edit
                                                </a>
                                            </div>
                                            <?php } else { echo ""; } ?>
                                        </div>
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
                                                                <span class="input-group-text" id="">1.</span>
                                                            </div>
                                                            <input type="text" class="form-control" placeholder='Question'>
                                                            <input type="text" class="form-control" placeholder='Answer'>
                                                        </div>

                                                        <div class="input-group mb-4">
                                                            <div class="input-group-prepend">
                                                                <span class="input-group-text" id="">2.</span>
                                                            </div>
                                                            <input type="text" class="form-control" placeholder='Question'>
                                                            <input type="text" class="form-control" placeholder='Answer'>
                                                        </div>

                                                        <div class="input-group mb-4">
                                                            <div class="input-group-prepend">
                                                                <span class="input-group-text" id="">3.</span>
                                                            </div>
                                                            <input type="text" class="form-control" placeholder='Question'>
                                                            <input type="text" class="form-control" placeholder='Answer'>
                                                        </div>

                                                        <div class="input-group mb-4">
                                                            <div class="input-group-prepend">
                                                                <span class="input-group-text" id="">4.</span>
                                                            </div>
                                                            <input type="text" class="form-control" placeholder='Question'>
                                                            <input type="text" class="form-control" placeholder='Answer'>
                                                        </div>

                                                        <div class="input-group mb-4">
                                                            <div class="input-group-prepend">
                                                                <span class="input-group-text" id="">5.</span>
                                                            </div>
                                                            <input type="text" class="form-control" placeholder='Question'>
                                                            <input type="text" class="form-control" placeholder='Answer'>
                                                        </div>

                                                        <div class="input-group mb-4">
                                                            <div class="input-group-prepend">
                                                                <span class="input-group-text" id="">6.</span>
                                                            </div>
                                                            <input type="text" class="form-control" placeholder='Question'>
                                                            <input type="text" class="form-control" placeholder='Answer'>
                                                        </div>

                                                        <div class="input-group mb-4">
                                                            <div class="input-group-prepend">
                                                                <span class="input-group-text" id="">7.</span>
                                                            </div>
                                                            <input type="text" class="form-control" placeholder='Question'>
                                                            <input type="text" class="form-control" placeholder='Answer'>
                                                        </div>

                                                        <div class="input-group mb-4">
                                                            <div class="input-group-prepend">
                                                                <span class="input-group-text" id="">8.</span>
                                                            </div>
                                                            <input type="text" class="form-control" placeholder='Question'>
                                                            <input type="text" class="form-control" placeholder='Answer'>
                                                        </div>

                                                        <div class="input-group mb-4">
                                                            <div class="input-group-prepend">
                                                                <span class="input-group-text" id="">9.</span>
                                                            </div>
                                                            <input type="text" class="form-control" placeholder='Question'>
                                                            <input type="text" class="form-control" placeholder='Answer'>
                                                        </div>

                                                        <div class="input-group mb-5">
                                                            <div class="input-group-prepend">
                                                                <span class="input-group-text" id="">10.</span>
                                                            </div>
                                                            <input type="text" class="form-control" placeholder='Question' >
                                                            <input type="text" class="form-control" placeholder='Answer'>
                                                        </div>


                                                        
                                                        <p id="edit_user_error"></p>

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
                    <div class="row mx-0">
                        <div class="col-6">
                            <h4>Product Images</h4>
                            <div class="text-gray">
                                <small>File size: Max of 1MB</small>
                            </div>
                            <div class="text-gray">
                                <small>File extension: jpg, jpeg, png</small>
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
                                <div class="card-body">
                                    <a class='nav-link modal-link px-0 text-gray' href='#' data-id='<?= $id ?>' data-url='../partials/templates/upload_modal.php' role='button'>
                                        <i class="fas fa-camera pr-2"></i>
                                        <small class='pr-2'>EDIT PRIMARY IMAGE</small>
                                        <i class="far fa-question-circle text-gray" data-toggle="tooltip" title="This will be your product's profile picture." data-original-title="#"></i>
                                    </a>
                                </div>
                                </a> 
                            </div>
                            </a>
                        </div>

                        <div class="col-lg-3 col-md-4 col-sm-6 p-2">
                            <a href="product.php?id=<?= $id ?>">
                            <div class='card border-0'>
                                <a href="product.php?id=<?= $row2['id'] ?>">
                                <img class='card-img-top' src="https://via.placeholder.com/250x250">
                                <div class="card-body">
                                    <a class='nav-link modal-link px-0 text-gray' href='#' data-id='<?= $id ?>' data-url='../partials/templates/upload_modal.php' role='button'>
                                        <i class="fas fa-camera pr-2"></i>
                                        <small class='pr-2'>EDIT IMAGE</small>
                                    </a>
                                </div>
                                </a> 
                            </div>
                            </a>
                        </div>

                        <div class="col-lg-3 col-md-4 col-sm-6 p-2">
                            <a href="product.php?id=<?= $id ?>">
                            <div class='card border-0'>
                                <a href="product.php?id=<?= $row2['id'] ?>">
                                <img class='card-img-top' src="https://via.placeholder.com/250x250">
                                <div class="card-body">
                                    <a class='nav-link modal-link px-0 text-gray' href='#' data-id='<?= $id ?>' data-url='../partials/templates/upload_modal.php' role='button'>
                                        <i class="fas fa-camera pr-2"></i>
                                        <small class='pr-2'>EDIT IMAGE</small>
                                    </a>
                                </div>
                                </a> 
                            </div>
                            </a>
                        </div>

                        <div class="col-lg-3 col-md-4 col-sm-6 p-2">
                            <a href="product.php?id=<?= $id ?>">
                            <div class='card border-0'>
                                <a href="product.php?id=<?= $row2['id'] ?>">
                                <img class='card-img-top' src="https://via.placeholder.com/250x250">
                                <div class="card-body">
                                    <a class='nav-link modal-link px-0 text-gray' href='#' data-id='<?= $id ?>' data-url='../partials/templates/upload_modal.php' role='button'>
                                        <i class="fas fa-camera pr-2"></i>
                                        <small class='pr-2'>EDIT IMAGE</small>
                                    </a>
                                </div>
                                </a> 
                            </div>
                            </a>
                        </div>

                        <div class="col-lg-3 col-md-4 col-sm-6 p-2">
                            <a href="product.php?id=<?= $id ?>">
                            <div class='card border-0'>
                                <a href="product.php?id=<?= $row2['id'] ?>">
                                <img class='card-img-top' src="https://via.placeholder.com/250x250">
                                <div class="card-body">
                                    <a class='nav-link modal-link px-0 text-gray' href='#' data-id='<?= $id ?>' data-url='../partials/templates/upload_modal.php' role='button'>
                                        <i class="fas fa-camera pr-2"></i>
                                        <small class='pr-2'>EDIT IMAGE</small>
                                    </a>
                                </div>
                                </a> 
                            </div>
                            </a>
                        </div>

                        <div class="col-lg-3 col-md-4 col-sm-6 p-2">
                            <a href="product.php?id=<?= $id ?>">
                            <div class='card border-0'>
                                <a href="product.php?id=<?= $row2['id'] ?>">
                                <img class='card-img-top' src="https://via.placeholder.com/250x250">
                                <div class="card-body">
                                    <a class='nav-link modal-link px-0 text-gray' href='#' data-id='<?= $id ?>' data-url='../partials/templates/upload_modal.php' role='button'>
                                        <i class="fas fa-camera pr-2"></i>
                                        <small class='pr-2'>EDIT IMAGE</small>
                                    </a>
                                </div>
                                </a> 
                            </div>
                            </a>
                        </div>

                        <div class="col-lg-3 col-md-4 col-sm-6 p-2">
                            <a href="product.php?id=<?= $id ?>">
                            <div class='card border-0'>
                                <a href="product.php?id=<?= $row2['id'] ?>">
                                <img class='card-img-top' src="https://via.placeholder.com/250x250">
                                <div class="card-body">
                                    <a class='nav-link modal-link px-0 text-gray' href='#' data-id='<?= $id ?>' data-url='../partials/templates/upload_modal.php' role='button'>
                                        <i class="fas fa-camera pr-2"></i>
                                        <small class='pr-2'>EDIT IMAGE</small>
                                    </a>
                                </div>
                                </a> 
                            </div>
                            </a>
                        </div>

                        <div class="col-lg-3 col-md-4 col-sm-6 p-2">
                            <a href="product.php?id=<?= $id ?>">
                            <div class='card border-0'>
                                <a href="product.php?id=<?= $row2['id'] ?>">
                                <img class='card-img-top' src="https://via.placeholder.com/250x250">
                                <div class="card-body">
                                    <a class='nav-link modal-link px-0 text-gray' href='#' data-id='<?= $id ?>' data-url='../partials/templates/upload_modal.php' role='button'>
                                        <i class="fas fa-camera pr-2"></i>
                                        <small class='pr-2'>EDIT IMAGE</small>
                                    </a>
                                </div>
                                </a> 
                            </div>
                            </a>
                        </div>

                        <div class="col-lg-3 col-md-4 col-sm-6 p-2">
                            <a href="product.php?id=<?= $id ?>">
                            <div class='card border-0'>
                                <a href="product.php?id=<?= $row2['id'] ?>">
                                <img class='card-img-top' src="https://via.placeholder.com/250x250">
                                <div class="card-body">
                                    <a class='nav-link modal-link px-0 text-gray' href='#' data-id='<?= $id ?>' data-url='../partials/templates/upload_modal.php' role='button'>
                                        <i class="fas fa-camera pr-2"></i>
                                        <small class='pr-2'>EDIT IMAGE</small>
                                    </a>
                                </div>
                                </a> 
                            </div>
                            </a>
                        </div>

                        <div class="col-lg-3 col-md-4 col-sm-6 p-2">
                            <a href="product.php?id=<?= $id ?>">
                            <div class='card border-0'>
                                <a href="product.php?id=<?= $row2['id'] ?>">
                                <img class='card-img-top' src="https://via.placeholder.com/250x250">
                                <div class="card-body">
                                    <a class='nav-link modal-link px-0 text-gray' href='#' data-id='<?= $id ?>' data-url='../partials/templates/upload_modal.php' role='button'>
                                        <i class="fas fa-camera pr-2"></i>
                                        <small class='pr-2'>EDIT IMAGE</small>
                                    </a>
                                </div>
                                </a> 
                            </div>
                            </a>
                        </div>

                        <div class="col-lg-3 col-md-4 col-sm-6 p-2">
                            <a href="product.php?id=<?= $id ?>">
                            <div class='card border-0'>
                                <a href="product.php?id=<?= $row2['id'] ?>">
                                <img class='card-img-top' src="https://via.placeholder.com/250x250">
                                <div class="card-body">
                                    <a class='nav-link modal-link px-0 text-gray' href='#' data-id='<?= $id ?>' data-url='../partials/templates/upload_modal.php' role='button'>
                                        <i class="fas fa-camera pr-2"></i>
                                        <small class='pr-2'>EDIT IMAGE</small>
                                    </a>
                                </div>
                                </a> 
                            </div>
                            </a>
                        </div>

                        <div class="col-lg-3 col-md-4 col-sm-6 p-2">
                            <a href="product.php?id=<?= $id ?>">
                            <div class='card border-0'>
                                <a href="product.php?id=<?= $row2['id'] ?>">
                                <img class='card-img-top' src="https://via.placeholder.com/250x250">
                                <div class="card-body">
                                    <a class='nav-link modal-link px-0 text-gray' href='#' data-id='<?= $id ?>' data-url='../partials/templates/upload_modal.php' role='button'>
                                        <i class="fas fa-camera pr-2"></i>
                                        <small class='pr-2'>EDIT IMAGE</small>
                                    </a>
                                </div>
                                </a> 
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

<?php require_once "../partials/footer.php";?>
<?php require_once "../partials/modal_container.php";?>
<?php require_once "../partials/modal_container_big.php"; ?>

  