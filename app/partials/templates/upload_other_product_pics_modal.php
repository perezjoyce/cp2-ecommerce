<?php
require_once '../../../config.php';
require_once "../../sources/class.upload.php";

?>
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
                       <h3>Upload Other Picture</h3>
                    </div>
                </div>

                <div class="row">
                    <div class="col">
                        <form action='<?= isset($_GET['productid']) ? "../controllers/process_edit_other_product_pic.php" : "../controllers/process_upload_other_product_pic.php" ?>'  method="POST" enctype="multipart/form-data">
                            <div class="d-flex align-items-center py-0">
                                <div class="form-group flex-fill">
                                    <input type="file" name="upload" class='btn py-2'>
                                    <!-- STORE ID -->
                                    <input type="text" name="id" value="<?= $_GET['id'] ?>"> 
                                    <!-- PRODUCT ID TO BE FETCHED FROM store-add-product -->
                                    <input type="text" name="<?=isset($_GET['productid']) ? 'productid' : 'productId'?>" value="<?=isset($_GET['productid']) ? $_GET['productid'] : $_SESSION['newProductId'] ?>"> 
                    
                                    
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

