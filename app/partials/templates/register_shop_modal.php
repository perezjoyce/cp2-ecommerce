<?php 
  require_once '../../../config.php';

  $id = $_SESSION['id'];
?>

<div class="container-fluid">
    <div class="row">

        <div class="col-lg-4 vanish-md vanish-sm px-0">
            <div id='login_image'></div>
        </div>


        <div class="col" style='height:80vh;overflow-y:auto;'>

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

                        <!-- DESCRIPTION -->
                        <div class="form-group row mb-5">
                            <label class='col-lg-3 col-md-3 col-sm-12 d-flex flex-row'>
                                About
                                <a data-toggle="tooltip" title="Describe what makes your shop unique or share your vision statement here." data-original-title="#">
                                    &nbsp;<i class="far fa-question-circle text-gray"></i>
                                </a>
                            </label>
                            <div class="input-group col-lg-9 col-md-9 col-sm-12"><textarea 
                            class='form-control' id='description' style='background:white!important;'></textarea>
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
                                <input type="text" class='form-control' id='saddress' placeholder='Sample City, PH'>
                            </div>
                        </div>

                        <!-- HOURS -->
                        <div class="form-group row mb-5">
                            <label for='hours' class='col-lg-3 col-md-3 col-sm-12'>Hours</label>
                            <div class="input-group col-lg-9 col-md-9 col-sm-12">
                                <input type='text' class='form-control' id='hours' placeholder='Mon - Fri, 0:00 AM - 0:00 PM'>
                            </div>
                        </div>

                        <!-- STANDARD SHIPPING -->
                        <div class="form-group row mb-5">
                            <label for='standard' class='col-lg-3 col-md-3 col-sm-12'>Standard</label>
                            <div class="input-group col-lg-9 col-md-9 col-sm-12">
                                <input type='number' class='form-control' id='standard' placeholder='Standard shipping fee.'>
                            </div>
                        </div>


                        <!-- STANDARD SHIPPING -->
                        <div class="form-group row mb-5">
                            <label for='free' class='col-lg-3 col-md-3 col-sm-12'>Free</label>
                            <div class="input-group col-lg-9 col-md-9 col-sm-12">
                                <input type='number' class='form-control' id='free' placeholder='Minimum spend required to avail free shipping.'>
                            </div>
                        </div>

                        <!-- STANDARD SHIPPING -->
                        <div class="form-group row mb-5">
                            <label class='col-lg-3 col-md-3 col-sm-12 d-flex flex-row'>
                                Permit
                                <a data-toggle="tooltip" title="Please attach a scanned copy of your government permit to get the certified seller badge after review." data-original-title="#">
                                    &nbsp;<i class="far fa-question-circle text-gray"></i>
                                </a>
                            </label>
                            <div class="input-group col-lg-9 col-md-9 col-sm-12">
                                <input type='file' class='form-control' id='permit'>
                            </div>
                        </div>

                       



                        <p id="register_shop_error"></p>

                        

                        <div class="container px-0 mb-5">
                            <!-- CHECKOUT BUTTON -->
                            <div class="row">
                                <div class="col">
                                    <div class="form-check-inline">
                                        <input class="form-check-input type_checkbox" type="checkbox" id="">
                                        <label class="form-check-label" for="">
                                           I hereby certify that the above information given is true and correct.
                                        </label>
                                    </div>
                                    
                                </div>
                                <div class="col-lg-4 col-md-6 col-sm-12"> 
                                    <a class='btn btn-lg btn-block py-3 btn-purple mt-5' id="btn_submit_store_application" role='button'>
                                        Go To My Shop
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
                        


    