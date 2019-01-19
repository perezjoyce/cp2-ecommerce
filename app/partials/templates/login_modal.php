<div class="container-fluid">
    <div class="row">

        <div class="col-lg-6 ml-0 py-0 px-0 my-0 ml-0 d-none d-lg-block d-xl-block">
            <div id='login_image'></div>
            <!-- <div id='login_ad'>
                <h1>fdsfsd</h1>
            </div> -->
        </div>

        <div class="col">

            <div class="row float-right">
                <button id='close_modal' type="button" class="close mr-3 mt-2" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true" class='font-weight-light text-purple' style='font-size:20px;'>&times;</span>
                </button>
            </div>

            <div class="container px-5 pb-2 pt-5 mb-4">
                <div class="row mb-5 mt-4"> 
                    <div class=' col-6'>
                        <h4>Log In</h4>
                    </div>
                    <a class='text-right text-secondary modal-link col-6' data-url='../partials/templates/registration_modal.php'>
                        <h4>Register</h4>
                    </a>
                </div>
            
                <form 
                    action="../controllers/process_login.php?redirectUrl=<?= isset($_GET['redirectUrl']) 
                    ? $_GET['redirectUrl'] 
                    : null ?>" 
                    method="POST" 
                    id="form_login">

                    <br>

                    <div class="form-group mt-5">
                        <input type="text" class="form-control form-control-lg" id="login_username_email" name="login_username_email" autocomplete="username" placeholder="Email or Username">
                        <small class="validation"></small>
                    </div>
            
                    <div class="form-group my-5">
                        <div class="input-group">
                            <input type="password" class="form-control form-control-lg" id="login_password" name="login_password" autocomplete="password" placeholder="Password">
                            <div class="input-group-append">
                                <a class="btn hide" id='btn_view_login_password' onclick="showLoginPassword()">
                                    <i class="fas fa-eye-slash eye text-secondary bg-light hide"></i>
                                </a>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-6">
                                <small id="login_password_validation"></small>
                            </div>
                            <div class="col-lg-6 text-right">
                                <a class="btn text-gray" id="btn_forgot_password">
                                    Forgot Password?
                                </a>
                            </div>
                        </div>
                    </div>

                    
                    <div class='row justify-content-center my-5'>
                        <small id="login_error_message"></small>
                    </div>


                    <div class="d-flex flex-column justify-content-end">
                        <button type="button" class="btn btn-lg flex-fill mr-2 mt-5 mb-3 btn-gradient" id="btn_login">LOG IN</button>   
                        <small class='mb-3'>Or, log in with</small>
                        <button type="button" class="btn btn-lg border mr-2 mb-5" id="btn_login_facebook">
                            <i class="fab fa-facebook-f text-primary mr-2"></i>
                            FACEBOOK
                        </button>
                    </div>
                    
                </form>
            </div>

        </div>

    </div>

    
</div>
  

       



    

