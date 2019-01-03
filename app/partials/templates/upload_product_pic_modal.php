<?php session_start(); ?>
<div class="container-fluid">
    <div class="row">

        <div class="col">

            <div class="row float-right">
                <button id='close_modal' type="button" class="close mr-3 mt-2" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true" class='font-weight-light text-secondary' style='font-size:20px;'>&times;</span>
                </button>
            </div>

            <div class="container px-5 pb-2 pt-5 mb-4">
                <div class="row mb-5 mt-4"> 
                    <div class='col'>
                       <h3>Upload Your Product's Primary Picture</h3>
                    </div>
                </div>

                <div class="row">
                    <div class="col">
                        <form action="../controllers/process_upload_product_primary_pic.php" method="POST" enctype="multipart/form-data">
                            <div class="d-flex align-items-center py-0">
                                <div class="form-group flex-fill">
                                    <input type="file" name="upload" class='btn py-2'>
                                    <!-- STORE ID -->
                                    <input type="hidden" name="id" value="<?= $_GET['id'] ?>"> 
                                    <!-- PRODUCT ID TO BE FETCHED FROM store-add-product -->
                                    <input type="hidden" name="productId" value="<?= $_GET['producId'] ?>"> 
                                    
                                </div>
                               
                                <input type="submit" value="UPLOAD" class="btn btn-lg btn-purple-reverse flex-fill border" id="btn_upload">
                            
                             
                            </div>
                            <p id="upload_error" class="my-5"></p>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

