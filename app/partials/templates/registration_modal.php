<form 
    action="../controllers/process_email2.php?redirectUrl=<?= isset($_GET['redirectUrl']) 
    ? $_GET['redirectUrl'] 
    : null ?>" 
    method="POST" 
    id="form_register">

    <div class="container">

        <div class="row mb-5 mt-4"> 

            <a class='text-danger modal-link col-6' data-url='../partials/templates/login_modal.php'>
                <h4>Login</h4>
            </a>
            <div class='col-6 text-right'>
                <h4>Register</h4>
            </div>
        </div>

        <br>
        <div class="form-group mt-5">
            <!-- change name to id -->
            <input type="email" class="form-control form-control-lg" id="register_email" name="register_email" autocomplete="email" placeholder="Email">
            <small id="registration_email_validation" class="text-danger"></small>
        </div>
    
        <div class="form-group">
            <!-- change name to id -->
            <input type="text" class="form-control form-control-lg" id="register_username" name="register_username" autocomplete="username" placeholder="Username (5+ characters)">
            <small id="registration_username_validation" class="text-danger"></small>
        </div>

        <div class="form-group">
            <div class="input-group">
            <input type="password" class="form-control form-control-lg" id="register_password" name="register_password" placeholder="Password (8+ characters)">
                <div class="input-group-append">
                    <a class="btn btn-lg bg-light" type="button" id='btn_view_password'>
                        <i class="fas fa-eye-slash bg-light"></i>
                    </a>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-6">
                    <small class="text-danger" id="registration_password_validation"></small>
                </div>
            </div>
            
        </div>

        <div class="form-group mb-5">
            <div class="input-group">
            <input type="password" class="form-control form-control-lg" id="cpass" name="cpass" placeholder="Confirm Password">
                <div class="input-group-append">
                    <a class="btn btn-lg bg-light" type="button" id='btn_view_password'>
                        <i class="fas fa-eye-slash bg-light"></i>
                    </a>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-6">
                    <small class="text-danger" id="registration_cpass_validation"></small>
                </div>
            </div>
            
        </div>
       

        
        <div>
            <small id="registration_error_message"></small>
        </div>

        <br>


        <div class="d-flex flex-column text-center my-5">
            <button type="button" class="btn btn-lg btn-warning flex-fill mr-2 mb-5 mb-5" id="btn_register">REGISTER</button>
            <div class='flex-fill'>By signing up, you agree to Shopperoo's <a href="#" class='text-primary'>Terms  & Conditions</a>. </div>
        </div>


         <div class="d-flex flex-row">  
            <button type="button" class="flex-fill btn btn-danger mr-2 my-5" id="btn_login_gmail">
                <i class="fab fa-google-plus-g"></i>
                Sign Up With Gmail
            </button>
            <button type="button" class="flex-fill btn btn-primary my-5" id="btn_login_facebook">
                <i class="fab fa-facebook"></i>
                Connect With Facebook
            </button>
        </div>
        
    </div>

    

   

</form>


    

