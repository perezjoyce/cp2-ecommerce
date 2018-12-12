<form 
    action="../controllers/process_login.php?redirectUrl=<?= isset($_GET['redirectUrl']) 
    ? $_GET['redirectUrl'] 
    : null ?>" 
    method="POST" 
    id="form_login">

    <div class="container">

        <div class="row mb-5 mt-4"> 
            <div class=' col-6'>
                <h4>Login</h4>
            </div>
            <a href="#" class='text-right text-danger col-6 b'>
                <h4>Register</h4>
            </a>
        </div>

        <br>
    
        <div class="form-group mt-5">
            <!-- change name to id -->
            <input type="text" class="form-control form-control-lg" id="login_username" name="login_username" autocomplete="username" placeholder="Username">
            <small class="validation text-danger"></small>
        </div>
    


        <div class="form-group mb-5">
            <div class="input-group">
            <input type="password" class="form-control form-control-lg" id="login_password" name="login_password" autocomplete="password" placeholder="Password">
                <div class="input-group-append">
                    <a class="btn btn-lg bg-light" type="button" id='btn_view_password'>
                        <i class="fas fa-eye-slash bg-light"></i>
                    </a>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-6">
                    <small class="text-danger" id="login_password_validation"></small>
                </div>
                <div class="col-lg-6 text-right">
                    <a class="btn" id="btn_forgot_password">
                        Forgot Password?
                    </a>
                </div>
            </div>
            
        </div>
       

        
        <div>
            <small id="login_error_message"></small>
        </div>

        <br>


        <div class="d-flex flex-row justify-content-end my-5">
            
            <button type="button" class="btn btn-lg btn-warning flex-fill mr-2 mb-5 mb-5" id="btn_login">LOGIN</button>
            
        </div>


         <div class="d-flex flex-row">  
            <button type="button" class="flex-fill btn btn-danger mr-2 my-5" id="btn_login_gmail">
                <i class="fab fa-google-plus-g"></i>
                Connect With Your Gmail
            </button>
            <button type="button" class="flex-fill btn btn-primary my-5" id="btn_login_facebook">
                <i class="fab fa-facebook"></i>
                Connect With Facebook
            </button>
        </div>
        
    </div>

    

   

</form>


    

