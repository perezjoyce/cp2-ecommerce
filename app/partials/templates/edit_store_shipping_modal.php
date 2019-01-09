<?php 
  require_once '../../../config.php';

  $storeId = $_GET['id'];

  $sql = "SELECT * FROM tbl_stores WHERE id = ? ";
  $statement = $conn->prepare($sql);
  $statement->execute([$storeId]);
  $row = $statement->fetch();
    $standard = $row['standard_shipping'];
    $free = $row['free_shipping_minimum'];  

?>

<div class="container-fluid">
  <div class="row">
      <div class="col" style='height:60vh;overflow-y:auto;'>

        <div class="row float-right">
            <button id='close_modal' type="button" class="close mr-3 mt-2" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true" class='font-weight-light text-secondary' style='font-size:20px;'>&times;</span>
            </button>
        </div>

        <div class="container px-5 pb-2 pt-5">
            <div class="row mt-4"> 
                <div class='col'>
                    <h3>Edit Your Shipping Details</h3>
                </div>
            </div>

            
            <div class="row mt-5">
                <div class="col mt-4">
                  <form action="../controllers/process_edit_store_details.php" method="POST" id="form_store_shipping">
                    <input type="hidden" name="id" id="id" value="<?= $storeId ?>">

                    

                      <!-- LAST NAME -->
                      <div class="form-group row mb-5">
                          <label for='store_standard_fee' class='col-lg-4 col-md-5 col-sm-12'>Standard Shipping</label>
                          <div class="input-group col-lg-8 col-md-7 col-sm-12">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" id="basic-addon1" style='background-color:white;'>&#x20B1;</span>
                                </div>
                                <input type="number" step=".01" class='form-control' id='store_standard_fee'
                                  value="" placeholder="<?= convertToDecimal($standard); ?>">
                          </div>
                          <div class='validation'></div>
                      </div>


                      <!-- EMAIL -->
                      <div class="form-group row mb-5">
                          <label for='store_free_shipping' class='col-lg-4 col-md-5 col-sm-122'>Free Shipping Minimum</label>
                          <div class="input-group col-lg-8 col-md-7 col-sm-12">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" id="basic-addon1" style='background-color:white;'>&#x20B1;</span>
                                </div>
                                <input type="number" step=".01" class='form-control' id='store_free_shipping'
                                  value="" placeholder="<?= convertToDecimal($free); ?>">
                          </div>
                          <div class='validation'></div>
                      </div>


                      <p id="edit_user_error"></p>

                      

                      <div class="container px-0 mb-5">
                          <!-- CHECKOUT BUTTON -->
                          <div class="row">
                              <div class="col-lg-8 col-md-6"></div>
                              <div class="col-lg-4 col-md-6 col-sm-12"> 
                                  <a class='btn btn-lg btn-block py-3 btn-purple mt-5 btn_edit_store_details' data-dismiss="modal" data-storeid=<?= $storeId ?> role='button'>
                                      Save Changes
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
                        


    