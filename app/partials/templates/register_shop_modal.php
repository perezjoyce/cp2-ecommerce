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
                    <form action="../controllers/process_register_shop.php" method="POST" id="form_register_shop" enctype="multipart/form-data">

                        <!-- SHOP NAME -->
                        <div class="form-group row mb-5">
                            <label for='sname' class='col-lg-3 col-md-3 col-sm-12'>Shop Name*</label>
                            <div class="input-group col-lg-9 col-md-9 col-sm-12">
                                <input type="text" class='form-control' id='sname' placeholder='You cannot change this afterwards.'>
                            </div>
                        </div>

                        <!-- DESCRIPTION -->
                        <div class="form-group row mb-5">
                            <label for='about' class='col-lg-3 col-md-3 col-sm-12'>About*</label>
                            <div class="input-group col-lg-9 col-md-9 col-sm-12"><textarea 
                            class='form-control' id='about' style='background:white!important;' placeholder='Describe what makes your shop unique or share your vision statement here.'></textarea>
                            </div>
                        </div>


                        <!-- OWNER -->
                        <div class="form-group row mb-5">
                            <label class='col-lg-3 col-md-3 col-sm-12'>Owner*</label>
                            <div class="input-group col-lg-9 col-md-9 col-sm-12 d-flex flex-row">
                                <?php 
                                    if(getFirstName($conn,$id)){
                                    $fname = getFirstName($conn,$id);
                                    $fname = ucwords(strtolower($fname));
                                ?>
                                    <input type="text" class='form-control' id='fname' value='<?=$fname?>'>
                                <?php } else { ?>
                                    <input type="text" class='form-control' id='fname' placeholder='First Name'>
                                <?php } ?>

                                <?php 
                                    if(getLastName($conn,$id)){
                                    $lname = getLastName($conn,$id);
                                    $lname = ucwords(strtolower($lname));
                                ?>
                                    <input type="text" class='form-control' id='lname' value='<?=$lname?>'>
                                <?php } else { ?>
                                    <input type="text" class='form-control' id='lname' placeholder='Last Name'>
                                <?php } ?>
                                
                                
                            </div>
                        </div>

                        <!-- ADDRESS -->
                        <div class="form-group row mb-5">
                            <label for='saddress' class='col-lg-3 col-md-3 col-sm-12'>Address*</label>
                            <div class="input-group col">
                                <input type="text" class='form-control' id='saddress' placeholder='Sample City, PH'>
                            </div>
                        </div>

                        <!-- HOURS -->
                        <div class="form-group row mb-5">
                            <label for='shours' class='col-lg-3 col-md-3 col-sm-12'>Hours</label>
                            <div class="input-group col-lg-9 col-md-9 col-sm-12">
                                <input type='text' class='form-control' id='shours' placeholder='Mon - Fri, 0:00 AM - 0:00 PM'>
                            </div>
                        </div>

                        <!-- STANDARD SHIPPING -->
                        <div class="form-group row mb-5">
                            <label for='standard' class='col-lg-3 col-md-3 col-sm-12'>Standard*</label>
                            <div class="input-group col-lg-9 col-md-9 col-sm-12">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" style='background-color:white;'>&#x20B1;</span>
                                </div>
                                <input type='number' step=".01" class='form-control' id='standard' placeholder='Standard shipping fee'>
                            </div>
                        </div>


                        <!-- FREE SHIPPING -->
                        <div class="form-group row mb-5">
                            <label for='free' class='col-lg-3 col-md-3 col-sm-12'>Free</label>
                            <div class="input-group col-lg-9 col-md-9 col-sm-12">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" style='background-color:white;'>&#x20B1;</span>
                                </div>
                                <input type='number' step=".01" class='form-control' id='free' placeholder='Minimum spend required to avail free shipping'>
                            </div>
                        </div>

                        <!-- LOGO
                        <div class="form-group row mb-5">
                            <label class='col-lg-3 col-md-3 col-sm-12'>Logo*</label>
                            <div class="input-group col-lg-9 col-md-9 col-sm-12">
                                <input type='file' value='UPLOAD' class='form-control' id='logo' name='logo' accept="image/jpeg, image/jpg, image/png" />
                            </div>
                        </div>

                        PERMIT
                        <div class="form-group row mb-5">
                            <label class='col-lg-3 col-md-3 col-sm-12 d-flex flex-row'>
                                Permit
                                <a data-toggle="tooltip" title="Please attach a scanned copy of your government permit to get the certified seller badge after review." data-original-title="#">
                                    &nbsp;<i class="far fa-question-circle text-gray"></i>
                                </a>
                            </label>
                            <div class="input-group col-lg-9 col-md-9 col-sm-12">
                                <input type='file' value='UPLOAD' class='form-control' id='permit' name='permit' accept="image/jpeg, image/jpg, image/png" />
                            </div>
                        </div> -->


                        <p id="register_shop_error"></p>

                        

                        <div class="container pr-0 mb-5">
                            <!-- CHECKOUT BUTTON -->
                            <div class="row">
                                <div class="col">
                                    <div class="d-flex flex-row mt-5 pr-lg-5 pr-md-5 pr-sm-0">
                                        <input class="form-check-input type_checkbox" type="checkbox" name="confirmation" id='confirmation'>
                                        <p id='confirmation_text'>I confirm that the above information I gave is true & correct.</p>
                                    </div>
                                    
                                </div>
                                <div class="col-lg-5 col-md-6 col-sm-12 pl-0"> 
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
                        


    