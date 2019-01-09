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
    }  

    $productId = $_GET['productid'];

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
<?php require_once "../partials/store_header.php";?>
    <!-- PAGE CONTENT -->
    <br>
    <div class="container p-0 my-lg-5 mt-md-5" id='store_page_container'>


        <div class="row mx-0">

          
            <!-- MAIN BAR-->
            <div class="col">


               
                <div class='container p-0'>
                    <div class="row">

                        <!-- FIRST ROW -->
                        <div class="col-lg-6 col-md-12 col-sm-12">

                            
                            <!-- PRODUCT BASIC INFO -->
                            <div class="container p-5 rounded mb-5" style='background:white;'>
                                <div class="row mb-3">
                                    <div class="col">
                                        <h4>Product Overview</h4>
                                    </div>
                                </div>

                                        <?php 
                                            if(isset($_GET['productid'])) {
                                                $sql = "SELECT i.id AS 'product_id', i.name, i.price, b.brand_name, b.id as 'brand_id', c.name 
                                                AS 'category_name', c.parent_category_id, c.id AS 'subcategory_id' 
                                                FROM tbl_items i JOIN tbl_brands b JOIN tbl_categories c 
                                                ON i.category_id=c.id AND i.brand_id=b.id WHERE i.id =  ?";
                                                $statement = $conn->prepare($sql);
                                                $statement->execute([$productId]);
                                                $row = $statement->fetch();
                                                $name = $row['name'];
                                                $price = $row['price'];
                                                $subCategoryId = $row['subcategory_id'];
                                                $subCategoryName = $row['category_name'];
                                                $parentCategoryId = $row['parent_category_id'];
                                                $brandName = $row['brand_name'];
                                                $brandId = $row['brand_id'];

                                                //FETCH PARENT CATEGORY NAME
                                                $sql2 = "SELECT name FROM tbl_categories WHERE id =?";
                                                $statement2 = $conn->prepare($sql2);
                                                $statement2->execute([$parentCategoryId]);
                                                $row2 = $statement2->fetch();
                                                $parentCategoryName = $row2['name'];
                                            }
                                        
                                        ?>
                                
                                <div class="row border-top">
                                    <div class="col">
                                        <div class="container px-0">

                                            <div class="row mt-5">
                                                <div class="col">
                                                    <form action="../controllers/process_add_new_product.php" method="POST" id="form_add_new_product">
                                                    <input type="hidden" id="new_product_id" value="<?= isset($_GET['productid']) ? $productId : null ; ?>">

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

                                                        <!-- ERROR -->
                                                        <div class="container">
                                                            <div class="row">
                                                                <div class="col">
                                                                    <div id="basic_info_error" class='text-red'></div>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="container px-0 mt-4">
                                                            <!-- CHECKOUT BUTTON -->
                                                            <div class="row">
                                                                <div class="col-lg-8 col-md-6"></div>
                                                                <div class="col-lg-4 col-md-6 col-sm-12"> 
                                                                    <?php if(!isset($productId)) { ?>
                                                                    <a class='btn btn-block py-3 btn-purple-reverse save_new_product' role='button' data-id='<?=$storeId?>'>
                                                                        <small>CREATE PRODUCT</small>    
                                                                    </a>
                                                                    <?php } else { ?>
                                                                    <a class='btn btn-block py-3 btn-purple-reverse save_new_product' role='button' data-id='<?=$storeId?>' data-productid='<?= isset($productId) ? $productId : null ; ?>'>
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
                                            Product Details
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
                                                                if(isset($productId)) {
                                                                $sqld = "SELECT * FROM tbl_item_descriptions WHERE product_id = ?";
                                                                $statementd = $conn->prepare($sqld);
                                                                $statementd->execute([$productId]);
                                                                $countd = $statementd->rowCount();

                                                                if($countd) {
                                                                    while($rowd = $statementd->fetch()){
                                                                    $descriptionId = $rowd['id'];
                                                                    $description = $rowd['description'];
                                                            ?>

                                                                <div class="input-group mb-4">
                                                                    <div class="input-group-prepend" style="background:white;">
                                                                        <button class="input-group-text border-0 text-gray font-weight-light btn_delete_new_detail"
                                                                            type='button'
                                                                            data-descriptionid='<?=$descriptionId?>'
                                                                            style="background:white;cursor:pointer">&times;
                                                                        </button> 
                                                                    </div>
                                                                    <textarea class="form-control product_description" 
                                                                        data-descriptionid='<?=$descriptionId?>' 
                                                                        data-id='<?=$productId?>' aria-label="With textarea"><?=
                                                                        $description
                                                                    ?></textarea>
                                                                </div>
  
                                                    
                                                            <?php } } else { ?>
                                                   
                                                            <div class="input-group mb-4">
                                                                <div class="input-group-prepend">
                                                                    <span class="input-group-text border-0 text-secondary" style="background:white;">&#9679;</span>
                                                                </div>
                                                                <textarea class="form-control product_description" data-id='<?= isset($productId) ? $productId : null ; ?>' aria-label="With textarea"></textarea>
                                                            </div>

                                                            
                                                            <?php } } else { ?>
                                                                <div class="input-group mb-4">
                                                                <div class="input-group-prepend">
                                                                    <span class="input-group-text border-0 text-secondary" style="background:white;">&#9679;</span>
                                                                </div>
                                                                <textarea class="form-control product_description" data-id='<?= isset($productId) ? $productId : null ; ?>' aria-label="With textarea"></textarea>
                                                            </div>

                                                           
                                                            <?php } ?>
                                                        </div>


                                                        <!-- ERROR -->
                                                        <div class="container">
                                                            <div class="row">
                                                                <div class="col">
                                                                    <div id="description_error" class='text-red'></div>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        
                                                                    
                                                        <?php 
                                                            if(isset($productId)) {
                                                                if($countd) { 
                                                        ?>
                                                        <!-- BUTTON -->
                                                        <div class="container px-0 mt-5">
                                                            <div class="row">
                                                                <div class="col-lg-4"></div>
                                                                <div class="col-lg-4 col-md-6">

                                                                    <a class='btn btn-block py-3 border pb-sm-5' id="btn_add_product_detail" role='button' data-id='<?= isset($productId) ? $productId : null ; ?>'>
                                                                        <small>ADD</small>    
                                                                    </a>

                                                                </div>
                                                                
                                                                <div class="col-lg-4 col-md-6 col-sm-12"> 
                                                                    <a class='btn btn-block py-3 btn-purple-reverse' id="btn_save_product_detail" role='button'>
                                                                        <small>APPLY CHANGES</small>    
                                                                    </a>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <?php } else  {?>

                                                        <div class="container px-0 mt-5">
                                                            <div class="row">
                                                                <div class="col-lg-4"></div>
                                                                <div class="col-lg-4 col-md-6">
                                                                    <a class='btn btn-block py-3 border mb-sm-5' id="btn_add_product_detail" role='button' data-id='<?= isset($productId) ? $productId : null ; ?>'>
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
                                                        <?php } } ?>       

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
                        <div class="col-lg-6 col-md-12 col-sm-12">

                            <!-- VARIATIONS -->
                            <div class="container p-5 rounded mb-5" style='background:white;'>
                                <div class="row mb-3">
                                    <div class="col d-flex flex-column">
                                        <h4>
                                            Variations & Stocks
                                        </h4>
                                        <div class="row px-3 my-3">
                                            <small class='text-secondary'>
                                               If product has <span class='font-weight-bold' style='text-decoration:underline;'>NO</span> variation, write "None" & incidate its total stocks instead.
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
                                                                    if(isset($productId)) {
                                                                    $sql = "SELECT * FROM tbl_variations WHERE product_id = ? ORDER BY id";
                                                                    $statement = $conn->prepare($sql);
                                                                    $statement->execute([$productId]);
                                                                    $count = $statement->rowCount();

                                                                    if($count) {
                                                                        while($row = $statement->fetch()){
                                                                        $variationId = $row['id'];
                                                                        $variationName = $row['variation_name'];
                                                                        $variationStock = $row['variation_stock'];
                                                                    
                                                                ?>

                                                            <div class="input-group mb-4">
                                                                <div class="input-group-prepend">
                                                                    <button class="input-group-text border-0 text-gray font-weight-light btn_delete_new_variation"
                                                                        type='button'
                                                                        data-variationid='<?=$variationId?>'
                                                                        style="background:white;cursor:pointer">&times;
                                                                    </button>                                                                
                                                                </div>
                                                                <input type="text" class="form-control new_variation_name" 
                                                                    value="<?=$variationName?>"
                                                                    data-variationid='<?=$variationId?>'
                                                                    data-id='<?= isset($productId) ? $productId : null ; ?>' 
                                                                    placeholder='Name'>
                                                                <input type="number" class="form-control new_variation_stock" 
                                                                    value='<?=$variationStock?>'
                                                                    data-variationid='<?=$variationId?>'
                                                                    data-id='<?= isset($productId) ? $productId : null ; ?>' 
                                                                    placeholder='Available Stock'>
                                                            </div>

                                                            
                                                                <?php } } else {?>
                                                            
                                                            <div class="input-group mb-4">
                                                                <div class="input-group-prepend">
                                                                    <span class="input-group-text border-0 text-secondary" style="background:white;">&#9679;</span>
                                                                </div>
                                                                <input type="text" class="form-control new_variation_name" data-id='<?= isset($productId) ? $productId : null ; ?>' placeholder='Name'>
                                                                <input type="number" class="form-control new_variation_stock" data-id='<?= isset($productId) ? $productId : null ; ?>' placeholder='Available Stock' min="1" step="1" onkeypress="return event.charCode >= 48 && event.charCode <= 57" title="Numbers only">
                                                            </div>

                                                            <?php } } else { ?>

                                                            <div class="input-group mb-4">
                                                                <div class="input-group-prepend">
                                                                    <span class="input-group-text border-0 text-secondary" style="background:white;">&#9679;</span>
                                                                </div>
                                                                <input type="text" class="form-control new_variation_name" data-id='<?= isset($productId) ? $productId : null ; ?>' placeholder='Name'>
                                                                <input type="number" class="form-control new_variation_stock" data-id='<?= isset($productId) ? $productId : null ; ?>' placeholder='Available Stock' min="1" step="1" onkeypress="return event.charCode >= 48 && event.charCode <= 57" title="Numbers only">
                                                            </div>

                                                            <?php } ?>
                                                        </div>
                                                        
                                                        <!-- ERROR -->
                                                        <div class="container">
                                                            <div class="row">
                                                                <div class="col">
                                                                    <div id="variation_error" class='text-red'></div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                      
                                                           
                                                        <!-- BUTTONS -->
                                                        <?php 
                                                            if(isset($productId)) {
                                                                if($count) { 
                                                        ?>
                                                            <div class="container px-0 mt-5">
                                                                <div class="row">
                                                                    <div class="col-lg-4"></div>
                                                                    <div class="col-lg-4 col-md-6">
                                                                        <a class='btn btn-block py-3 border mb-sm-5' id="btn_add_product_variation" role='button' data-id='<?= isset($productId) ? $productId : null ; ?>'>
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
                                                                        <a class='btn btn-block py-3 border mb-sm-5' id="btn_add_product_variation" role='button' data-id='<?= isset($productId) ? $productId : null ; ?>'>
                                                                            <small>ADD</small>    
                                                                        </a>
                                                                    </div>
                                                                    
                                                                    <div class="col-lg-4 col-md-6 col-sm-12">
                                                                        <a class='btn btn-block py-3 btn-purple-reverse btn_save_product_variation' data-id='<?= isset($productId) ? $productId : null ; ?>' role='button'>
                                                                            <small>SAVE VARIATIONS</small>    
                                                                        </a>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            
                                                        
                                                        <?php } } ?>
                                                        

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
                                            FAQs
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
                                                                    if(isset($productId)) {
                                                                        $yes = 'yes';
                                                                        $sqlf = "SELECT * FROM tbl_questions_answers WHERE product_id = ? AND faq = ?";
                                                                        // echo $sql;die();
                                                                        $statementf = $conn->prepare($sqlf);
                                                                        $statementf->execute([$productId, $yes]);
                                                                        $countf = $statementf->rowCount();

                                                                        if($countf) {
                                                                            while($rowf = $statementf->fetch()){
                                                                            $faqId = $rowf['id'];
                                                                            $question = $rowf['question'];
                                                                            $answer = $rowf['answer'];
                                                                ?>

                                                            <div class="input-group mb-4">
                                                                <div class="input-group-append">
                                                                    <button class="input-group-text border-0 text-gray font-weight-light btn_delete_new_faq"
                                                                        type='button'
                                                                        data-faqid='<?=$faqId?>'
                                                                        style="background:white;cursor:pointer">&times;
                                                                    </button>
                                                                </div>
                                                                <input type="text" class="form-control new_question" 
                                                                    value="<?=$question?>"
                                                                    data-faqid='<?=$faqId?>'
                                                                    data-id='<?= isset($productId) ? $productId : null ; ?>' 
                                                                    placeholder='Question'>
                                                                <input type="text" class="form-control new_answer" 
                                                                    value='<?=$answer?>'
                                                                    data-faqid='<?=$faqId?>'
                                                                    data-id='<?= isset($productId) ? $productId : null ; ?>' 
                                                                    placeholder='Answer'>
                                                            </div>

                                                            
                                                                <?php } } else { ?>
                                                            
                                                            <div class="input-group mb-4">
                                                                <div class="input-group-prepend">
                                                                    <span class="input-group-text border-0 text-secondary" style="background:white;">&#9679;</span>
                                                                </div>
                                                                <input type="text" class="form-control new_question" data-id='<?= isset($productId) ? $productId : null ; ?>' placeholder='Question' maxlength='50'>
                                                                <input type="text" class="form-control new_answer" data-id='<?= isset($productId) ? $productId : null ; ?>' placeholder='Answer' maxlength='50'>
                                                            </div>
                                

                                                            <?php } } else { ?>

                                                             <div class="input-group mb-4">
                                                                <div class="input-group-prepend">
                                                                    <span class="input-group-text border-0 text-secondary" style="background:white;">&#9679;</span>
                                                                </div>
                                                                <input type="text" class="form-control new_question" data-id='<?= isset($productId) ? $productId : null ; ?>' placeholder='Question' maxlength='50'>
                                                                <input type="text" class="form-control new_answer" data-id='<?= isset($productId) ? $productId : null ; ?>' placeholder='Answer' maxlength='50'>
                                                            </div>

                                                            <?php } ?>
                                                        </div>

                                                        <!-- ERROR -->
                                                        <div class="container">
                                                            <div class="row">
                                                                <div class="col">
                                                                    <div id="faq_error" class='text-red'></div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                      
                                                           
                                                        <!-- BUTTONS -->
                                                        <?php 
                                                            if(isset($productId)) {
                                                                if($countf) { 
                                                        ?>
                                                            <div class="container px-0 mt-5">
                                                                <div class="row">
                                                                    <div class="col-lg-4"></div>
                                                                    <div class="col-lg-4 col-md-6">
                                                                        <a class='btn btn-block py-3 border mb-sm-5 btn_add_product_faq' role='button' data-id='<?= isset($productId) ? $productId : null ; ?>'>
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
                                                                        <a class='btn btn-block py-3 border mb-sm-5 btn_add_product_faq' role='button' data-id='<?= isset($productId) ? $productId : null ; ?>'>
                                                                            <small>ADD</small>    
                                                                        </a>
                                                                    </div>
                                                                    
                                                                    <div class="col-lg-4 col-md-6 col-sm-12">
                                                                        <a class='btn btn-block py-3 btn-purple-reverse btn_save_product_faq' data-id='<?= isset($productId) ? $productId : null ; ?>' role='button'>
                                                                            <small>SAVE FAQ</small>    
                                                                        </a>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            
                                                        
                                                        <?php } } ?>
                                                        

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

                   


                
                <!-- PRODUCT IMAGES -->
                <div class='container p-5 rounded mb-5' style='background:white;'>
                    <div class="row mx-0 d-flex align-items-center">

                        <div class='flex-fill mb-sm-5'>
                            <h4>Product Images</h4>

                            <div class="text-gray">
                                <small>File size: Max of 1MB</small>
                            </div>
                            <div class="text-gray">
                                <small>File extension: jpg, jpeg, png</small>
                            </div>
                        </div>

                        <div class='flex-fill'>  
                            <div class='d-flex flex-row'>
                                <?php 
                                 if(isset($productId)) {
                                    $default = "https://via.placeholder.com/250x250";
                                    $sql = "SELECT * FROM tbl_product_images WHERE product_id =? AND is_primary = 1";
                                    $statement = $conn->prepare($sql);
                                    $statement->execute([$productId]);                                
                                    $count = $statement->rowCount();

                                    if(!$count){
                                    
                                 ?>
                                <a class='btn py-3 btn-purple-reverse modal-link btn-sm-block' data-id='<?= $storeId ?>' data-url='../partials/templates/upload_product_pic_modal.php?id=<?=$storeId?>&productid=<?=$productId?>' role='button'>
                                    <i class="fas fa-camera pr-2"></i>
                                    <small class='pr-2'>ADD PRIMARY IMAGE</small>
                                    <i class="far fa-question-circle text-gray" data-toggle="tooltip" title="This will be your product's profile picture." data-original-title="#"></i>
                                </a>

                                <?php } else { ?>

                                 <a class='btn py-3 btn-purple-reverse modal-link btn-sm-block' data-id='<?= $storeId ?>' data-url='../partials/templates/upload_product_pic_modal.php?id=<?=$storeId?>&productid=<?=$productId?>' role='button'>
                                    <i class="fas fa-camera pr-2"></i>
                                    <small class='pr-2'>EDIT PRIMARY IMAGE</small>
                                    <i class="far fa-question-circle text-gray" data-toggle="tooltip" title="This will be your product's profile picture." data-original-title="#"></i>
                                </a>

                                <?php } ?>
                            </div>
                        </div>


                        <div class='flex-fill'>  
                            <div class='d-flex flex-row'>
                                <!-- STILL NEEDS TO PASS VALUE THROUGH GET TO upload_product_pic_modal.php -->
                                    <!-- TO EDIT, ON CLICK PASS VALUE TO HIDDEN URL THEN WHEN USER CLICKS EDIT, YOU KNOW ALREADY WHAT IMG ID TO EDIT -->
                                <a class='btn py-3 btn-gray text-secondary modal-link btn-sm-block' data-id='<?= $storeId ?>' data-url='../partials/templates/upload_other_product_pics_modal.php?id=<?=$storeId?>&productid=<?=$productId?>' role='button'>
                                    <i class="fas fa-camera pr-2"></i>
                                    <small class='pr-2'>ADD ANOTHER IMAGE</small>
                                </a>
                                <?php } ?>
                            </div>
                        </div>
					</div>
                </div>


                <!-- PRODUCT IMAGES -->
                <div class="container p-0 <?= isset($productId) ? 'mb-5' : null ;?>">
                    
                    <div class="row no-gutters justify-content-left">
                      
                                <?php 
                                    if(isset($productId)) {
                                        $default = "https://via.placeholder.com/250x250";
                                        $sql = "SELECT * FROM tbl_product_images WHERE product_id =? AND is_primary = 1";
                                        $statement = $conn->prepare($sql);
                                        $statement->execute([$productId]);                                
                                        $count = $statement->rowCount();

                                        if($count){
                                            $row = $statement->fetch();
                                            $id = $row['id'];
                                            $img_path = $row['url'];
                                            $img_path = BASE_URL."/".$img_path.".jpg";
                                ?>
                                    

                        <div class="col-lg-3 col-md-4 col-sm-12 p-lg-2"> 
                            <div class='card border-0' style='background:none;height:300px;'>   
                                <button class="btn_delete_other_pic text-gray font-weight-light text-left border-0 pl-2"
                                    type='button'
                                    data-id='<?= $id ?>'
                                    style="background:transparent;cursor:pointer;z-index:5;">&times;
                                </button>      
                                <img class='card-img-top' src='<?= $img_path ?>' style='height:300px;background-color:transparent;'>
                            </div>
                        </div>
                            <?php } else { ?>
                    
                        <div class="col-lg-3 col-md-4 col-sm-6 p-lg-2">
                            <div class='card border-0' style='background:none;width:250px;height:250px;'>  
                                <img class='card-img-top' src='<?= $default ?>' style='width:250px;height:250px;background-color:transparent;'>
                            </div>
                        </div>
                            
                            <?php } } 
                                $default = "https://via.placeholder.com/250x250";
                                if(isset($productId)) {
                                    $sql = "SELECT * FROM tbl_product_images WHERE product_id =? AND is_primary = 0";
                                    $statement = $conn->prepare($sql);
                                    $statement->execute([$productId]);                                
                                    $count = $statement->rowCount();

                                if($count){
                                    while($row = $statement->fetch()){
                                        $id = $row['id'];
                                        $img_path = $row['url'];
                                        if($img_path) {
                                            $img_path = BASE_URL."/".$img_path.".jpg";
                            ?>

                        <div class="col-lg-3 col-md-4 col-sm-6 p-2">
                            <div class='card border-0' style='background:none;height:300px;'>
                                <button class="btn_delete_other_pic text-gray font-weight-light text-left border-0 pl-2"
                                    type='button'
                                    data-id='<?= $id ?>'
                                    style="background:transparent;cursor:pointer;z-index:5;">&times;
                                </button> 
                                <img class='card-img-top' src='<?=$img_path?>' style='height:300px;background-color:transparent;'>
                            </div>
                        </div>        
                                    
                            <?php } } } else { ?>
                        <div class="col-lg-3 col-md-4 col-sm-6 p-2">
                            <div class='card border-0' style='background:none;width:250px;height:250px;'>
                                <img class='card-img-top' src='<?=$default?>' style='width:250px;height:250px;background-color:transparent;'>
                            </div>
                        </div>
                            
                           <?php } } ?>
                    </div>
                </div>

                <!-- HEADING -->
                <div class='container p-5 rounded' style='background:white;'>
                    <div class="row mx-0 d-flex align-items-center flex-lg-row flex-md-row flex-sm-column">

                        <div class='flex-fill mb-sm-5'>
                            <h4>6. Post Your Product</h4>
                        </div>

                        <div class='flex'>   
                            <?php  if(isset($productId)) { ?>
                            <!-- open product.php?id=$newProductId in different page and end/unset session here -->
                            <a class="btn btn-lg btn-block btn-purple btn_store_products_view" data-href='<?= BASE_URL ."/app/partials/templates/product_modal.php?id=". $productId?>'>
                                <small>VIEW PRODUCT</small>
                            </a>
                            <?php } ?>
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

  