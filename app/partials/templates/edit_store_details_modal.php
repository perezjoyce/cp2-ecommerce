<?php 
  // TO DISPLAY CURRENT USER DATA
  session_start(); 
  require_once "../../controllers/functions.php";
  include_once '../../sources/pdo/src/PDO.class.php';

  //set values
  $host = "localhost";
  $db_username = "root";
  $db_password = "";
  $db_name = "db_demoStoreNew";

  $conn = new PDO("mysql:host=$host;dbname=$db_name",$db_username,$db_password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

  $storeId = $_GET['id'];

  $sql = "SELECT * FROM tbl_stores WHERE id = ? ";
  $statement = $conn->prepare($sql);
  $statement->execute([$storeId]);
  $row = $statement->fetch();
    $address = $row['store_address'];
    $hours = $row['hours'];

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
                    <h3>Edit Your Business Details</h3>
                </div>
            </div>

            
            <div class="row mt-5">
                <div class="col mt-4">
                  <form action="../controllers/process_edit_store_details.php" method="POST" id="form_store_details">
                    <input type="hidden" name="id" id="id" value="<?= $storeId ?>">

                    

                      <!-- LAST NAME -->
                      <div class="form-group row mb-5">
                          <label for='store_address' class='col-lg-3 col-md-3 col-sm-12'>Address</label>
                          <div class="input-group col-lg-9 col-md-9 col-sm-12">
                              <input type="text" class='form-control' id='store_address'
                                  value="<?= ucwords(strtolower($address)) ?>">
                          </div>
                      </div>

                      <!-- EMAIL -->
                      <div class="form-group row mb-5">
                          <label for='hours' class='col-lg-3 col-md-3 col-sm-12'>Hours</label>
                          <div class="input-group col-lg-9 col-md-9 col-sm-12">
                              <input type="text" class='form-control' id='store_hours'
                                  value="<?= $hours ?>" placeholder="Mon - Fri, 8:00 AM - 5:00 PM">
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
                        


    