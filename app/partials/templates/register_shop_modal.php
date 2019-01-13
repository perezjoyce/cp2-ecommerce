<?php 
  require_once '../../../config.php';

  $id = $_SESSION['id'];
?>

<div class="container-fluid">
    <div class="row">

        <div class="col-lg-3 vanish-md vanish-sm px-0">
            <div id='login_image'></div>
        </div>


        <div class="col">

            <div class="row float-right">
                <button id='close_modal' type="button" class="close mr-3 mt-2" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true" class='font-weight-light text-secondary' style='font-size:20px;'>&times;</span>
                </button>
            </div>

            <div class="container px-lg-5 px-md-5 px-sm-4 pb-2 pt-5 mb-4">
                <div class="row mt-4"> 
                    <div class='col'>
                        <h3>Shop Registration</h3>
                    </div>
                </div>

                
                <div class="row mt-5">
                    <div class="col mt-4">
                    <form action="../controllers/process_register_shop.php" method="POST" id="form_register_shop">
                        <input type="hidden" name="id" id="id" value="<?= $id ?>">

                        <!-- SHOP NAME -->
                        <div class="form-group row mb-5">
                            <label for='sname' class='col-lg-3 col-md-3 col-sm-12'>Shop Name</label>
                            <div class="input-group col-lg-9 col-md-9 col-sm-12">
                                <input type="text" class='form-control' id='sname'>
                            </div>
                        </div>

                        <!-- OWNER -->
                        <div class="form-group row mb-5">
                            <label for='owner' class='col-lg-3 col-md-3 col-sm-12'>Owner</label>
                            <div class="input-group col-lg-9 col-md-9 col-sm-12">
                                <input type="text" class='form-control' id='owner' placeholder='Please write your complete name.'>
                            </div>
                        </div>

                        <!-- ADDRESS -->
                        <div class="form-group row mb-5">
                            <label for='saddress' class='col-lg-3 col-md-3 col-sm-12'>Address</label>
                            <div class="input-group col">
                                <input type="text" class='form-control' id='saddress' placeholder='Please write your complete address.'>
                            </div>
                        </div>

                        <!-- BRAND -->
                        <div class="form-group row mb-5">
                            <label for='brands' class="col-lg-3 col-md-3 col-sm-12">Brands</label>
                            <div class="input-group col" id='brands'>
                                <div class="container-fluid px-lg-0 px-md-0 pl-sm-3 pr-sm-0">
                                    <div class="row no-gutters">   
                                        <?php 
                                            $sql = "SELECT * FROM tbl_brands";
                                            $statement = $conn->prepare($sql);
                                            $statement->execute();
                                            while($row = $statement->fetch()){
                                                $brandId = $row['id'];
                                                $brandName = $row['brand_name'];
                                        ?>
                                        
                                            <div class="col-lg-3 col-md-4 col-sm-12">
                                                <div class="form-check-inline">
                                                    <input class="form-check-input brand_checkbox" type="checkbox" value="<?=$brandId?>" id="<?=$brandName?>">
                                                    <label class="form-check-label" for="<?=$brandName?>">
                                                        <?=$brandName?>
                                                    </label>
                                                </div>
                                            </div>
                                            
                                        <?php } ?>
                                        <div class="container-fluid px-0">
                                            <div class="row no-gutters">
                                                <div class="col-lg-3 col-md-4 col-sm-12 pt-4">
                                                    <button class="btn border" id='btn_add_brand' type='button'>
                                                        Add Brand
                                                    </button>
                                                </div>
                                                <div class="input-group col pt-4 additional_brand">
                                                    <input type="text" class="form-control" placeholder="Additional brand.">
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- CATEGORIES -->
                        <div class="form-group row mb-5">
                            <label for='categories' class='col-lg-3 col-md-3 col-sm-12'>Categories</label>
                            <div class="input-group col" id='categories'>
                                <div class="container-fluid px-lg-0 px-md-0 pl-sm-3 pr-sm-0">
                                    <div class="row no-gutters"> 
                                        <?php 
                                            $sql = "SELECT * FROM tbl_categories WHERE parent_category_id IS NULL";
                                            $statement = $conn->prepare($sql);
                                            $statement->execute();
                                            while($row = $statement->fetch()){
                                                $categoryId = $row['id'];
                                                $categoryName = $row['name'];
                                        ?>
                                        <div class="col-lg-2 col-md-4 col-sm-12">
                                            <div class="form-check-inline">
                                                <input class="form-check-input category_checkbox" type="checkbox" value="<?=$categoryId?>" id="<?=$categoryName?>">
                                                <label class="form-check-label" for="<?=$categoryName?>">
                                                    <?=$categoryName?>
                                                </label>
                                            </div>
                                        </div>
                                        <?php } ?>
                                    </div>
                                </div> 
                            </div>
                        </div>

                        <!-- TYPES -->
                        <div class="form-group row mb-5">
                            <label for='types' class="col-lg-3 col-md-3 col-sm-12">Brands</label>
                            <div class="input-group col" id='types'>
                                <div class="container-fluid px-lg-0 px-md-0 pl-sm-3 pr-sm-0">
                                    <div class="row no-gutters">   
                                        <?php 
                                            $sql = "SELECT * FROM tbl_categories WHERE parent_category_id IS NOT NULL";
                                            $statement = $conn->prepare($sql);
                                            $statement->execute();
                                            while($row = $statement->fetch()){
                                                $typeId = $row['id'];
                                                $typeName = $row['name'];
                                        ?>
                                        
                                            <div class="col-lg-4 col-md-6 col-sm-12">
                                                <div class="form-check-inline">
                                                    <input class="form-check-input type_checkbox" type="checkbox" value="<?=$typeId?>" id="<?=$typeName?>">
                                                    <label class="form-check-label" for="<?=$typeName?>">
                                                        <?=$typeName?>
                                                    </label>
                                                </div>
                                            </div>
                                            
                                        <?php } ?>
                                    </div>
                                </div>
                            </div>
                        </div>



                        <p id="register_shop_error"></p>

                        

                        <div class="container px-0 mb-5">
                            <!-- CHECKOUT BUTTON -->
                            <div class="row">
                                <div class="col-lg-8 col-md-6"></div>
                                <div class="col-lg-4 col-md-6 col-sm-12"> 
                                    <a class='btn btn-lg btn-block py-3 btn-purple mt-5' id="btn_submit_store_application" role='button'>
                                        Submit <span class='vanish-sm'>Application</span>
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
                        


    