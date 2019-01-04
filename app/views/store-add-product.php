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


               
                <div class='container p-0'>
                    <div class="row">

                        <!-- FIRST ROW -->
                        <div class="col-lg-6 col-md-6">

                            
                            <!-- PRODUCT BASIC INFO -->
                            <div class="container p-5 rounded mb-5" style='background:white;'>
                                <div class="row mb-3">
                                    <div class="col">
                                        <h4>1. Create Product Overview</h4>
                                    </div>
                                </div>

                                        <?php 
                                            if(isset($_SESSION['newProductId'])) {
                                                $newProductId = $_SESSION['newProductId'];
                                                $sql = "SELECT i.id AS 'product_id', i.name, i.price, b.brand_name, b.id as 'brand_id', c.name 
                                                AS 'category_name', c.parent_category_id, c.id AS 'subcategory_id' 
                                                FROM tbl_items i JOIN tbl_brands b JOIN tbl_categories c 
                                                ON i.category_id=c.id AND i.brand_id=b.id WHERE i.id =  ?";
                                                $statement = $conn->prepare($sql);
                                                $statement->execute([$newProductId]);
                                                $row = $statement->fetch();
                                                $name = $row['name'];
                                                $price = $row['price'];
                                                $subCategoryId = $row['subcategory_id'];
                                                $subCategoryName = $row['category_name'];
                                                $parentCategoryId = $row['parent_category_id'];
                                                $brandName = $row['brand_name'];
                                                $brandId = $row['brand_id'];

                                                //FETCH PARENT CATEGORY NAME
                                                $sql = "SELECT name FROM tbl_categories WHERE id =?";
                                                $statement = $conn->prepare($sql);
                                                $statement->execute([$parentCategoryId]);
                                                $row = $statement->fetch();
                                                $parentCategoryName = $row['name'];
                                            }
                                        
                                        ?>
                                
                                <div class="row border-top">
                                    <div class="col">
                                        <div class="container px-0">

                                            <div class="row mt-5">
                                                <div class="col">
                                                    <form action="../controllers/process_add_new_product.php" method="POST" id="form_add_new_product">
                                                        <input type="text" id="new_product_id" value="<?= isset($newProductId) ? $newProductId : null ; ?>">

                                                        <!-- PRODUCT NAME -->
                                                        <div class="form-group row mb-5">
                                                            <label for='product_name' class='col-lg-3 col-md-3 col-sm-12'>Name</label>
                                                            <div class="input-group col-lg-9 col-md-9 col-sm-12">
                                                                <input type="text" class='form-control' id='product_name'
                                                                maxlength="40" value='<?= isset($name) ? $name : null ; ?>'>
                                                            </div>
                                                        </div>

                                                        <!-- PARENT CATEGORY -->
                                                        <div class="form-group row mb-5">
                                                            <label for='product_category' class='col-lg-3 col-md-3 col-sm-12'>Category</label>
                                                            <div class="input-group col-lg-9 col-md-9 col-sm-12">
                                                                <select class="custom-select" id="product_category">
                                                                    <option value="<?=isset($parentCategoryName) ? $parentCategoryId : null ; ?>"  selected>
                                                                        <?= isset($parentCategoryName) ? $parentCategoryName : 'Choose...' ; ?>
                                                                    </option>
                                                                    <?php 
                                                                        $sql2 = "SELECT * FROM tbl_categories WHERE parent_category_id IS NULL";
                                                                            $statement2 = $conn->prepare($sql2);
                                                                            $statement2->execute();
                                                                        while($row2 = $statement2->fetch()){
                                                                            $parentCategoryId = $row2['id'];
                                                                            $parentCategoryName = $row2['name'];
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
                                                                    <option value="<?=isset($parentCategoryName) ? $subCategoryId : null ; ?>" selected>
                                                                        <?= isset($subCategoryName) ? $subCategoryName : '...' ; ?>
                                                                    </option>
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
                                                                    <option value="<?=isset($parentCategoryName) ? $brandId : null ; ?>" selected>
                                                                        <?= isset($brandName) ? $brandName : '...' ; ?>
                                                                    </option>
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
                                                                <input type="number" step=".01" placeholder='0.00' 
                                                                    class="form-control" aria-label="Amount" id='product_price'
                                                                    value='<?= isset($price) ? $price : null ; ?>'>
                                                            </div>
                                                            <div class='validation'></div>
                                                        </div>
                
                                                        <!-- <p id=""></p> -->

                                                        <div class="container px-0">
                                                            <!-- CHECKOUT BUTTON -->
                                                            <div class="row">
                                                                <div class="col-lg-8 col-md-6"></div>
                                                                <div class="col-lg-4 col-md-6 col-sm-12"> 
                                                                    <?php if(!isset($newProductId)) { ?>
                                                                    <a class='btn btn-block py-3 btn-purple-reverse save_new_product' role='button' data-id='<?=$storeId?>'>
                                                                        <small>CREATE PRODUCT</small>    
                                                                    </a>
                                                                    <?php } else { ?>
                                                                    <a class='btn btn-block py-3 btn-purple-reverse save_new_product' role='button' data-id='<?=$storeId?>' data-productid='<?= isset($newProductId) ? $newProductId : null ; ?>'>
                                                                        <small>APPLY CHANGES</small>    
                                                                    </a>
                                                                    <?php } ?>

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
                                                    <form action="../controllers/process_add_new_product_details.php" method="POST" id="form_product_details">
                                                    
                                                        <div class="container px-0 product_detail">

                                                            <?php 
                                                                $sql = "SELECT * FROM tbl_item_descriptions WHERE product_id = ?";
                                                                $statement = $conn->prepare($sql);
                                                                $statement->execute([$newProductId]);
                                                                $count = $statement->rowCount();

                                                                if($count) {
                                                                    while($row = $statement->fetch()){
                                                                    $descriptionId = $row['id'];
                                                                    $description = $row['description'];
                                                            ?>

                                                                <div class="input-group mb-4">
                                                                    <div class="input-group-prepend" style="background:white;">
                                                                        <span class="input-group-text border-0 text-secondary" style="background:white;">&#9679;</span>
                                                                    </div>
                                                                    <textarea class="form-control product_description" 
                                                                        data-descriptionid='<?=$descriptionId?>' 
                                                                        data-id='<?=$newProductId?>' aria-label="With textarea"><?=
                                                                        $description
                                                                    ?></textarea>
                                                                </div>
  
                                                    
                                                            <?php } } else { ?>
                                                   
                                                            <div class="input-group mb-4">
                                                                <div class="input-group-prepend">
                                                                    <span class="input-group-text border-0 text-secondary" style="background:white;">&#9679;</span>
                                                                </div>
                                                                <textarea class="form-control product_description" data-id='<?= isset($newProductId) ? $newProductId : null ; ?>' aria-label="With textarea"></textarea>
                                                            </div>

                                                            <div class="input-group mb-4">
                                                                <div class="input-group-prepend">
                                                                    <span class="input-group-text border-0 text-secondary" style="background:white;">&#9679;</span>
                                                                </div>
                                                                <textarea class="form-control product_description" data-id='<?= isset($newProductId) ? $newProductId : null ; ?>' aria-label="With textarea"></textarea>
                                                            </div>

                                                            <div class="input-group mb-4">
                                                                <div class="input-group-prepend">
                                                                    <span class="input-group-text border-0 text-secondary" style="background:white;">&#9679;</span>
                                                                </div>
                                                                <textarea class="form-control product_description" data-id='<?= isset($newProductId) ? $newProductId : null ; ?>' aria-label="With textarea"></textarea>
                                                            </div>
                                                            <?php } ?>
                                                        </div>

                                                       
                                                        
                                                                    
                                                        <?php if($count) { ?>
                                                        <!-- BUTTON -->
                                                        <div class="container px-0 mt-5">
                                                            <div class="row">
                                                                <div class="col-lg-4"></div>
                                                                <div class="col-lg-4 col-md-6">

                                                                    <a class='btn btn-block py-3 border' id="btn_add_product_detail" role='button' data-id='<?= isset($newProductId) ? $newProductId : null ; ?>'>
                                                                        <small>ADD</small>    
                                                                    </a>

                                                                </div>
                                                                
                                                                <div class="col-lg-4 col-md-6 col-sm-12"> 
                                                                    <a class='btn btn-block py-3 btn-purple-reverse' id="btn_save_product_detail" role='button'>
                                                                        <small>SAVE EDITS</small>    
                                                                    </a>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <?php } else  {?>

                                                        <div class="container px-0 mt-5">
                                                            <div class="row">
                                                                <div class="col-lg-4"></div>
                                                                <div class="col-lg-4 col-md-6">
                                                                    <a class='btn btn-block py-3 border' id="btn_add_product_detail" role='button' data-id='<?= isset($newProductId) ? $newProductId : null ; ?>'>
                                                                        <small>ADD</small>    
                                                                    </a>
                                                                </div>
                                                                
                                                                <div class="col-lg-4 col-md-6 col-sm-12"> 
                                                                    <a class='btn btn-block py-3 btn-purple-reverse' id="btn_save_product_detail" role='button'>
                                                                        <small>SAVE DETAILS</small>    
                                                                    </a>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <?php } ?>       

                                                    </form>
                                                </div>
                                            </div>
                                            

                                        </div>
                                    </div>
                                </div>
                            </div>


                        </div>
                        <!-- / END OF FIRST ROW -->
                    
                    
                        <!-- SECOND ROW -->
                        <div class="col-lg-6 col-md-6">

                            <!-- VARIATIONS -->
                            <div class="container p-5 rounded mb-5" style='background:white;'>
                                <div class="row mb-3">
                                    <div class="col d-flex flex-column">
                                        <h4>
                                            <span class='vanish-lg vanish-md'>3.</span>
                                            <span class='vanish-sm'>2.</span>
                                            &nbsp;Add Product Variations & Stocks
                                        </h4>
                                        <div class="row pl-5 my-3">
                                            <small class='text-secondary'>
                                               If product has <span class='font-weight-bold' style='text-decoration:underline;'>NO</span> variation, write "None" in name & incidate the total stocks of the product.
                                            </small>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="row border-top">
                                    <div class="col">
                                        <div class="container px-0">

                                            

                                            <div class="row mt-5">
                                                <div class="col">
                                                    <form action="../controllers/process_add_new_product_variation.php" method="POST" id="form_product_variation">
                                                        <div class="container px-0 product_variation">
                                                                <?php 
                                                                    $sql = "SELECT * FROM tbl_variations WHERE product_id = ?";
                                                                    $statement = $conn->prepare($sql);
                                                                    $statement->execute([$newProductId]);
                                                                    $count = $statement->rowCount();

                                                                    if($count) {
                                                                        while($row = $statement->fetch()){
                                                                        $variationId = $row['id'];
                                                                        $variationName = $row['variation_name'];
                                                                        $variationStock = $row['variation_stock'];
                                                                ?>

                                                            <div class="input-group mb-4">
                                                                <div class="input-group-prepend">
                                                                    <span class="input-group-text border-0 text-secondary" style="background:white;">&#9679;</span>
                                                                </div>
                                                                <input type="text" class="form-control new_variation_name" 
                                                                    value="<?=$variationName?>"
                                                                    data-variationid='<?=$variationId?>'
                                                                    data-id='<?= isset($newProductId) ? $newProductId : null ; ?>' 
                                                                    placeholder='Name'>
                                                                <input type="number" class="form-control new_variation_stock" 
                                                                    value='<?=$variationStock?>'
                                                                    data-variationid='<?=$variationId?>'
                                                                    data-id='<?= isset($newProductId) ? $newProductId : null ; ?>' 
                                                                    placeholder='Available Stock'>
                                                            </div>

                                                            
                                                                <?php } } else {?>
                                                            
                                                            <div class="input-group mb-4">
                                                                <div class="input-group-prepend">
                                                                    <span class="input-group-text border-0 text-secondary" style="background:white;">&#9679;</span>
                                                                </div>
                                                                <input type="text" class="form-control new_variation_name" data-id='<?= isset($newProductId) ? $newProductId : null ; ?>' placeholder='Name'>
                                                                <input type="number" class="form-control new_variation_stock" data-id='<?= isset($newProductId) ? $newProductId : null ; ?>' placeholder='Available Stock' min="1" step="1" onkeypress="return event.charCode >= 48 && event.charCode <= 57" title="Numbers only">
                                                            </div>

                                                            <div class="input-group mb-4">
                                                                <div class="input-group-prepend">
                                                                    <span class="input-group-text border-0 text-secondary" style="background:white;">&#9679;</span>
                                                                </div>
                                                                <input type="text" class="form-control new_variation_name" data-id='<?= isset($newProductId) ? $newProductId : null ; ?>' placeholder='Name'>
                                                                <input type="number" class="form-control new_variation_stock" data-id='<?= isset($newProductId) ? $newProductId : null ; ?>' placeholder='Available Stock' min="1" step="1" onkeypress="return event.charCode >= 48 && event.charCode <= 57" title="Numbers only">
                                                            </div>

                                                            <div class="input-group mb-4">
                                                                <div class="input-group-prepend">
                                                                    <span class="input-group-text border-0 text-secondary" style="background:white;">&#9679;</span>
                                                                </div>
                                                                <input type="text" class="form-control new_variation_name" data-id='<?= isset($newProductId) ? $newProductId : null ; ?>' placeholder='Name'>
                                                                <input type="number" class="form-control new_variation_stock" data-id='<?= isset($newProductId) ? $newProductId : null ; ?>' placeholder='Available Stock' min="1" step="1" onkeypress="return event.charCode >= 48 && event.charCode <= 57" title="Numbers only">
                                                            </div>
                                                            <?php } ?>
                                                        </div>
                                                      
                                                           
                                                        <!-- BUTTONS -->
                                                        <?php if($count) { ?>
                                                            <div class="container px-0 mt-5">
                                                                <div class="row">
                                                                    <div class="col-lg-4"></div>
                                                                    <div class="col-lg-4 col-md-6">
                                                                        <a class='btn btn-block py-3 border' id="btn_add_product_variation" role='button' data-id='<?= isset($newProductId) ? $newProductId : null ; ?>'>
                                                                            <small>ADD</small>    
                                                                        </a>
                                                                    </div>
                                                                    
                                                                    <div class="col-lg-4 col-md-6 col-sm-12"> 
                                                                        <a class='btn btn-block py-3 btn-purple-reverse btn_save_product_variation' role='button'>
                                                                            <small>APPLY CHANGES</small>    
                                                                        </a>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        <?php } else { ?>
                                                            
                                                            <div class="container px-0 mt-5">
                                                                <div class="row">
                                                                    <div class="col-lg-4"></div>
                                                                    <div class="col-lg-4 col-md-6">
                                                                        <a class='btn btn-block py-3 border' id="btn_add_product_variation" role='button' data-id='<?= isset($newProductId) ? $newProductId : null ; ?>'>
                                                                            <small>ADD</small>    
                                                                        </a>
                                                                    </div>
                                                                    
                                                                    <div class="col-lg-4 col-md-6 col-sm-12">
                                                                        <a class='btn btn-block py-3 btn-purple-reverse btn_save_product_variation' data-id='<?= isset($newProductId) ? $newProductId : null ; ?>' role='button'>
                                                                            <small>SAVE VARIATIONS</small>    
                                                                        </a>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            
                                                        
                                                        <?php } ?>
                                                        

                                                    </form>
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        
                            <!-- FAQs -->
                            <div class="container p-5 rounded mb-5" style='background:white;'>
                                <div class="row mb-3">
                                    <div class="col">
                                        <h4>
                                            4. FAQs
                                        </h4>
                                    </div>
                                </div>
                                
                                <div class="row border-top">
                                    <div class="col">
                                        <div class="container px-0">

                                            <div class="row mt-5">
                                                <div class="col">
                                                    <form action="../controllers/process_add_new_product_faq.php" method="POST" id="form_new_product_faq">
                                                        <div class="container px-0 product_faq">
                                                                <?php 
                                                                    $yes = 'yes';
                                                                    $sql = "SELECT * FROM tbl_questions_answers WHERE product_id = ? AND faq = ?";
                                                                    $statement = $conn->prepare($sql);
                                                                    $statement->execute([$newProductId, $yes]);
                                                                    $count = $statement->rowCount();

                                                                    if($count) {
                                                                        while($row = $statement->fetch()){
                                                                        $faqId = $row['id'];
                                                                        $question = $row['question'];
                                                                        $answer = $row['answer'];
                                                                ?>

                                                            <div class="input-group mb-4">
                                                                <div class="input-group-prepend">
                                                                    <span class="input-group-text border-0 text-secondary" style="background:white;">&#9679;</span>
                                                                </div>
                                                                <input type="text" class="form-control new_question" 
                                                                    value="<?=$question?>"
                                                                    data-faqid='<?=$faqId?>'
                                                                    data-id='<?= isset($newProductId) ? $newProductId : null ; ?>' 
                                                                    placeholder='Question'>
                                                                <input type="text" class="form-control new_answer" 
                                                                    value='<?=$answer?>'
                                                                    data-faqid='<?=$faqId?>'
                                                                    data-id='<?= isset($newProductId) ? $newProductId : null ; ?>' 
                                                                    placeholder='Answer'>
                                                            </div>

                                                            
                                                                <?php } } else {?>
                                                            
                                                            <div class="input-group mb-4">
                                                                <div class="input-group-prepend">
                                                                    <span class="input-group-text border-0 text-secondary" style="background:white;">&#9679;</span>
                                                                </div>
                                                                <input type="text" class="form-control new_question" data-id='<?= isset($newProductId) ? $newProductId : null ; ?>' placeholder='Question' maxlength='50'>
                                                                <input type="text" class="form-control new_answer" data-id='<?= isset($newProductId) ? $newProductId : null ; ?>' placeholder='Answer' maxlength='50'>
                                                            </div>

                                                            <div class="input-group mb-4">
                                                                <div class="input-group-prepend">
                                                                    <span class="input-group-text border-0 text-secondary" style="background:white;">&#9679;</span>
                                                                </div>
                                                                <input type="text" class="form-control new_question" data-id='<?= isset($newProductId) ? $newProductId : null ; ?>' placeholder='Question' maxlength='50'>
                                                                <input type="text" class="form-control new_answer" data-id='<?= isset($newProductId) ? $newProductId : null ; ?>' placeholder='Answer' maxlength='50'>
                                                            </div>

                                                            <div class="input-group mb-4">
                                                                <div class="input-group-prepend">
                                                                    <span class="input-group-text border-0 text-secondary" style="background:white;">&#9679;</span>
                                                                </div>
                                                                <input type="text" class="form-control new_question" data-id='<?= isset($newProductId) ? $newProductId : null ; ?>' placeholder='Question' maxlength='50'>
                                                                <input type="text" class="form-control new_answer" data-id='<?= isset($newProductId) ? $newProductId : null ; ?>' placeholder='Answer' maxlength='50'>
                                                            </div>
                                                            <?php } ?>
                                                        </div>
                                                      
                                                           
                                                        <!-- BUTTONS -->
                                                        <?php if($count) { ?>
                                                            <div class="container px-0 mt-5">
                                                                <div class="row">
                                                                    <div class="col-lg-4"></div>
                                                                    <div class="col-lg-4 col-md-6">
                                                                        <a class='btn btn-block py-3 border btn_add_product_faq' role='button' data-id='<?= isset($newProductId) ? $newProductId : null ; ?>'>
                                                                            <small>ADD</small>    
                                                                        </a>
                                                                    </div>
                                                                    
                                                                    <div class="col-lg-4 col-md-6 col-sm-12"> 
                                                                        <a class='btn btn-block py-3 btn-purple-reverse btn_save_product_faq' role='button'>
                                                                            <small>APPLY CHANGES</small>    
                                                                        </a>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        <?php } else { ?>
                                                            
                                                            <div class="container px-0 mt-5">
                                                                <div class="row">
                                                                    <div class="col-lg-4"></div>
                                                                    <div class="col-lg-4 col-md-6">
                                                                        <a class='btn btn-block py-3 border btn_add_product_faq' role='button' data-id='<?= isset($newProductId) ? $newProductId : null ; ?>'>
                                                                            <small>ADD</small>    
                                                                        </a>
                                                                    </div>
                                                                    
                                                                    <div class="col-lg-4 col-md-6 col-sm-12">
                                                                        <a class='btn btn-block py-3 btn-purple-reverse btn_save_product_faq' data-id='<?= isset($newProductId) ? $newProductId : null ; ?>' role='button'>
                                                                            <small>SAVE FAQ</small>    
                                                                        </a>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            
                                                        
                                                        <?php } ?>
                                                        

                                                    </form>
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                            </div>


                        </div>
                        <!-- / END SECOND ROW -->
                    
                    </div>
                </div>

                   


                
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
                <div class="container mb-5 p-0" id='store_page_product_container'>
                    
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

                 <!-- HEADING -->
                 <div class='container p-5 rounded mb-5' style='background:white;'>
                    <div class="row mx-0 d-flex align-items-center">

                        <div class='flex-fill'>
                            <h4>6. Post Your Product</h4>
                        </div>

                        <div class='flex-fill d-flex flex-row'>  
                                <div class="col-6 flex-fill"></div>
                                <div class="col-6 flex-fill">
                                <!-- open product.php?id=$newProductId in different page and end/unset session here -->
                                <a class='nav-link btn btn-lg btn-purple' href='#' target="_blank" data-id='<?= $id ?>' data-url='../partials/templates/upload_product_pic_modal.php' role='button'>
                                    
                                    <small>POST PRODUCT</small>
                                </a>
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

  