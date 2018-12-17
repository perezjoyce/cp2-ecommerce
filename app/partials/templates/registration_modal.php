

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
                <button id='close_modal' type="button" class="close mr-1" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="container px-5 pb-2 pt-5 mb-4">
                <div class="row mb-5 mt-4"> 
                    <a class='text-purple modal-link col-6' data-url='../partials/templates/login_modal.php'>
                        <h4>Log In</h4>
                    </a>
                    <div class='col-6 text-right'>
                        <h4>Register</h4>
                    </div>
                </div>
            
                <form 
                    action="../controllers/process_email.php"
                    method="POST" 
                    id="form_register">


                    <div class="form-group my-5">
                        <input type="email" class="form-control form-control-lg" id="register_email" name="register_email" autocomplete="email" placeholder="Email">
                        <small id="registration_email_validation"></small>
                    </div>

                    <div class="form-group mb-4">
                        <input type="text" class="form-control form-control-lg" id="register_username" name="register_username" autocomplete="username" placeholder="Username">
                        <small id="registration_username_validation"><span class='text-secondary'>Minimum of 5 characters</span></small>
                    </div>
            
                    <div class="form-group mb-5">
                        <div class="input-group">
                            <input type="password" class="form-control form-control-lg" id="register_password" name="register_password" placeholder="Password">
                            <div class="input-group-append">
                                <a class="btn" id='btn_view_registration_password' onclick="showRegistrationPassword()">
                                    <i class="fas fa-eye-slash eye text-secondary"></i>
                                </a>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col">
                                <small id="registration_password_validation"><span class='text-secondary'>Minimum of 8 characters</span></small>
                            </div>
                        </div>
                        
                    </div>

                    <!-- <div class="form-group mb-5 d-none" id='cpass_hidden'>
                        <div class="input-group">
                        <input type="password" class="form-control form-control-lg" id="cpass" name="cpass" placeholder="Confirm Password">
                            <div class="input-group-append">
                                <a class="btn" id='btn_view_cpass'>
                                    <i class="fas fa-eye-slash bg-light"></i>
                                </a>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col">
                                <small id="registration_cpass_validation"></small>
                            </div>
                        </div>
                    </div> -->

                    <div class='row justify-content-center'>
                        <small id="registration_error_message"></small>
                    </div>
                    

                    <div class="d-flex flex-column text-center">
                        <button type="button" class="btn btn-lg flex-fill mr-2 mt-5 mb-3 btn-gradient" id="btn_register">REGISTER</button>
                        <small class='mb-3 text-left'>Or, register with</small>
                        <button type="button" class="btn btn-lg border mr-2 mb-5" id="btn_register_facebook">
                            <i class="fab fa-facebook-f text-primary mr-2"></i>
                            FACEBOOK
                        </button>
                        <small class='flex-fill mb-5'>By signing up, you agree to Shopperoo's <a href="#" class='text-primary'>Terms  & Conditions</a>. </small>
                    </div>

                </form>
            </div>
        </div>
    </div>
</div>












        

