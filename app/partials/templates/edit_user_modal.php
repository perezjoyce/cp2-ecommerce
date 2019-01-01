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

  $id = $_GET['id'];
    
  $sql = "SELECT * FROM tbl_users WHERE id = ? ";
  $statement = $conn->prepare($sql);
  $statement->execute([$id]);
  $row = $statement->fetch();
    $id = $row['id'];
    $fname = $row['first_name'];
    $lname = $row['last_name'];
    $email = $row['email'];
    $username = $row['username'];
    $password = sha1($row['password']);

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
                    <h3>Your Basic Information</h3>
                </div>
            </div>

            
            <div class="row mt-5">
                <div class="col mt-4">
                  <form action="../controllers/process_edit_email.php" method="POST" id="form_edit_user">
                    <input type="hidden" name="id" id="id" value="<?= $id ?>">

                      <!-- FIRST NAME -->
                      <div class="form-group row mb-5">
                          <label for='fname' class='col-lg-3 col-md-3 col-sm-12'>First Name</label>
                          <div class="input-group col-lg-9 col-md-9 col-sm-12">
                              <input type="text" class='form-control' id='fname'
                                  value="<?= $fname ?>">
                          </div>
                      </div>

                      <!-- LAST NAME -->
                      <div class="form-group row mb-5">
                          <label for='lname' class='col-lg-3 col-md-3 col-sm-12'>Last Name</label>
                          <div class="input-group col-lg-9 col-md-9 col-sm-12">
                              <input type="text" class='form-control' id='lname'
                                  value="<?= $lname ?>">
                          </div>
                      </div>

                      <!-- EMAIL -->
                      <div class="form-group row mb-5">
                          <label for='email' class='col-lg-3 col-md-3 col-sm-12'>Email*</label>
                          <div class="input-group col-lg-9 col-md-9 col-sm-12">
                              <input type="text" class='form-control' id='email'
                                  value="<?= hide_email($email) ?>">
                          </div>
                          <div class='validation'></div>
                      </div>


                      <!-- USERNAME -->
                      <div class="form-group row mb-5">
                          <label for='unsername' class='col-lg-3 col-md-3 col-sm-12'>Username*</label>
                          <div class="input-group col-lg-9 col-md-9 col-sm-12">
                              <input type="text" class='form-control' id='username'
                                  value="<?= $username ?>">
                          </div>
                          <div class='validation'></div>
                      </div>

                      
                      <!-- PASSWORD -->
                      <div class="form-group row mb-5">
                          <label for='name' class='col-lg-3 col-md-3 col-sm-12'>Password*</label>
                          <div class="input-group col-lg-9 col-md-9 col-sm-12">
                              <input type="password" class='form-control border-right-0 pr-0' id='password'>
                              <a class="btn hide btn_view_profile_password border border-left-0 mx-0 px-0 eye-profile">
                                  <i class="fas fa-eye-slash eye text-secondary bg-light hide pr-2"></i>
                              </a>
                          </div>
                          <!-- <div class="input-group-append col-1 px-0 mx-0">
                              <a class="btn hide btn_view_profile_password border border-left-0 mx-0 px-lg-3 px-md-3 px-sm-3 float-right eye-profile">
                                  <i class="fas fa-eye-slash eye text-secondary bg-light hide px-lg-0 px-md-2 px-sm-0 mx-0"></i>
                              </a>
                          </div> -->
                      </div>
                          

                      <p id="edit_user_error"></p>

                      

                      <div class="container px-0 mb-5">
                          <!-- CHECKOUT BUTTON -->
                          <div class="row">
                              <div class="col-lg-8 col-md-6"></div>
                              <div class="col-lg-4 col-md-6 col-sm-12"> 
                                  <a class='btn btn-lg btn-block py-3 btn-purple mt-5 save_address_edit' id="btn_edit_user" role='button'>
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
                        


    