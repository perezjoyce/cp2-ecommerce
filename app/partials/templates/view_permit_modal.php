<?php 
    require_once '../../../config.php';

    $storeId = $_GET['id'];
    $storeInfo = getStore ($conn,$storeId);
    $sellerId = $storeInfo['user_id'];
    $storeName = $storeInfo['name'];

    $sql = "SELECT * FROM tbl_permits WHERE store_id=?";
    $statement = $conn->prepare($sql);
    $statement->execute([$storeId]);
    $row = $statement->fetch();
    $permit = $row['permit'];

    if($permit == null) {
        $permit = DEFAULT_STORE;
    } else {
        $permit = BASE_URL ."/". $permit . ".jpg";
    }
    
    
?>

<div class="container-fluid" id='view_shop_modal'>
    <div class="row">
        
        <div class="col" style='height:80vh;overflow-y:auto;' id='printThis'>

            <div class="row float-right">
                <button id='close_modal' type="button" class="close mr-3 mt-2" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true" class='font-weight-light text-secondary' style='font-size:20px;'>&times;</span>
                </button>
            </div>

            <div class="container p-0 my-lg-5 mt-md-5" id='store_page_container'>

                <div class="row mx-0">
                    <div class="col my-5">
                        <h3><?=$storeName?> &nbsp; Business Permit</h3>
                    </div>
                </div>

                <div class="row" >
                    <div class="col mb-5">
                        <img src="<?=$permit?>" alt="business permit of <?=$storeName?>" style="width:100%;">
                    </div>
                </div>
                
            </div>

            <div class="container mb-5 px-0">
                <div class="row">
                    <div class="col-lg-6 col-md-6 col-sm-12"></div>
                    <div class="col">
                        <?php 
                            $modalLinkClassPrefix = ''; 
                            if(isset($_SESSION['id'])) {
                                $modalLinkClassPrefix='-big';
                            }
                        ?>
                        <a class='btn btn-lg btn-block py-3 btn-purple btn_print'>
                            Save As PDF&nbsp; 
                            <i class="fas fa-file-download"></i>
                        </a>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

