<?php 
  require_once '../../../config.php';

  $id = $_SESSION['id'];
?>

<div class="container-fluid">
  <div class="row">
      <div class="col" style='height:80vh;overflow-y:auto;'>

        <div class="row float-right">
            <button id='close_modal' type="button" class="close mr-3 mt-2" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true" class='font-weight-light text-secondary' style='font-size:20px;'>&times;</span>
            </button>
        </div>

        <div class="container px-5 pb-2 pt-5">
            <div class="row mt-4"> 
                <div class='col'>
                    <h3>Shop Registration</h3>
                </div>
            </div>

            
            <div class="row mt-5">
                <div class="col mt-4">
                  <form action="../controllers/process_edit_email.php" method="POST" id="form_edit_user">
                    <input type="hidden" name="id" id="id" value="<?= $id ?>">

                      <!-- SHOP NAME -->
                      <div class="form-group row mb-5">
                          <label for='sname' class='col-lg-3 col-md-3 col-sm-12'>Shop Name:</label>
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
                          <div class="input-group col-lg-9 col-md-9 col-sm-12">
                              <input type="text" class='form-control' id='saddress' placeholder='Please write your complete address.'>
                          </div>
                      </div>

                      <!-- BRAND -->
                      <div class="form-group row mb-5">
                          <label for='unsername' class='col-lg-3 col-md-3 col-sm-12'>Brands</label>
                          <div class="input-group col-lg-9 col-md-9 col-sm-12">
                              <?php 
                                $sql = "SELECT * FROM tbl_brands";
                                $statement = $conn->prepare($sql);
                                $statement->execute();
                                while($row = $statement->fetch()){
                                    $brandId = $row['id'];
                                    $brandName = $row['brand_name'];
                              ?>
                            <div class="form-check-inline">
                                <input class="form-check-input brand_checkbox" type="checkbox" value="<?=$brandId?>">
                                <label class="form-check-label" for="brand_checkbox">
                                    <?=$brandName?>
                                </label>
                            </div>
                                <?php } ?>
                          </div>
                      </div>

                      <!-- CATEGORIES -->
                      <div class="form-group row mb-5">
                          <label for='unsername' class='col-lg-3 col-md-3 col-sm-12'>Categories</label>
                          <div class="input-group col-lg-9 col-md-9 col-sm-12">
                              <?php 
                                $sql = "SELECT * FROM tbl_categories WHERE parent_category_id IS NULL";
                                $statement = $conn->prepare($sql);
                                $statement->execute();
                                while($row = $statement->fetch()){
                                    $categoryId = $row['id'];
                                    $categoryName = $row['name'];
                              ?>
                            <div class="form-check-inline">
                                <input class="form-check-input category_checkbox" type="checkbox" value="<?=$categoryId?>">
                                <label class="form-check-label" for="category_checkbox">
                                    <?=$categoryName?>
                                </label>
                            </div>
                                <?php } ?>
                          </div>
                      </div>

                      <!-- TYPES -->
                      <div class="form-group row mb-5">
                          <label for='unsername' class='col-lg-3 col-md-3 col-sm-12'>Categories</label>
                          <div class="input-group col-lg-9 col-md-9 col-sm-12">
                              <?php 
                                $sql = "SELECT * FROM tbl_categories WHERE parent_category_id IS NOT NULL";
                                $statement = $conn->prepare($sql);
                                $statement->execute();
                                while($row = $statement->fetch()){
                                    $typeId = $row['id'];
                                    $typeName = $row['name'];
                              ?>
                            <div class="form-check-inline">
                                <input class="form-check-input type_checkbox" type="checkbox" value="<?=$typeId?>">
                                <label class="form-check-label" for="type_checkbox">
                                    <?=$typeName?>
                                </label>
                            </div>
                                <?php } ?>
                          </div>
                      </div>

                      
                      
                          

                      <p id="register_shop_error"></p>

                      

                      <div class="container px-0 mb-5">
                          <!-- CHECKOUT BUTTON -->
                          <div class="row">
                              <div class="col-lg-8 col-md-6"></div>
                              <div class="col-lg-4 col-md-6 col-sm-12"> 
                                  <a class='btn btn-lg btn-block py-3 btn-purple mt-5' id="btn_submit_store_application" role='button'>
                                      SUBMIT APPLICATION
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
                        


    