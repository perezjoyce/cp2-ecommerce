<?php
    require_once '../../../config.php';
    $storeId = $_GET['id'];
    $storeInfo = getStore ($conn,$id);
    $storeName = $storeInfo['name'];
    $storeLogo = $storeInfo['logo'];

    $storeLogo = BASE_URL ."/". $storeLogo . "_80x80.jpg";
?>


<div class="container p-0 my-lg-5 mt-md-5">

        


        <div class="row mx-0">

          
            <!-- MAIN BAR-->
            <div class="col">

                <!-- SEARCH BAR -->
                <div class='container p-5 rounded' style='background:white;'>
                    <div class="row mx-0">
                        <div class="col-lg-6 col-md-4 col-sm-12">
                            <div class="d-flex align-items-center">
                                <img src='<?= $storeLogo ?>' style='width:60px;height:60px;' class='rounded-circle'>
                                <h4><?= $storeName ?>&nbsp; Account</h4>
                            </div>
                            
                        </div>
                        <!-- <div class="col">
                            <div class="input-group input-group-lg">
                                <div class="input-group-prepend">
                                    <span class="input-group-text border-right-0 border-left-0 border-top-0" id="store_page_search_button" style='background:white;'>
                                        <i class="fas fa-search" style='background:white;'></i>
                                    </span>
                                </div>
                                <input type="text" class="form-control border-right-0 border-left-0 border-top-0" id="store_page_search">
                            </div>
                        </div> -->
						
					</div>
                </div>

                
                
                <!-- COMPLETED ORDERS -->
                <div class="container px-5 pb-5 rounded" style='background:white;'>
                               
                                
                    <?php
                    $sql = " SELECT * FROM tbl_seller_account WHERE store_id = ?";
                                $statement = $conn->prepare($sql);
                                $statement->execute([$storeId]);
                                $count = $statement->rowCount();
            
                            if($count) {
                    ?>

                    <div class="row mb-5">
                        <div class="col-12 px-2 mb-0 table-responsive-sm">
                            
                            <table class="table table-hover borderless text-center bg-gray mb-0">
                                <thead>
                                    <tr class='py-0'>
                                    
                                        <td width='25%'>Date</td>
                                        <td width='25%'>Credit</td>
                                        <td width='25%'>
                                          Debit
                                            <a data-toggle="tooltip" title="Credit Charge is 3%" data-original-title="#">
                                               <i class="far fa-question-circle text-gray"></i>
                                            </a>
                                        </td>
                                        <td width='25%'>Description</td>
                                        <!-- <td width='15%'>Amount</td> -->
                                        <!-- <td width='10%'>View</td> -->
        
                                        
                                    </tr> 
                                </thead>
                                <tbody style='background:white;height:600px;overflow-y:auto;font-size:12px;'>

  
                                    <?php 
                                        while($row = $statement->fetch()){ 
                                            $purchaseDate = $row['timestamp'];
                                            $credit = $row['credit'];
                                            $debit = $row['debit'];
                                            $description = $row['description'];
                                    ?>
                                    
                                        <tr>
                                            <!-- PURCHASE DATE -->
                                            <td class='mx-0' width='25%'>
                                                <div class='py-4 text-secondary'>
                                                    <?=date("M d, Y", strtotime($purchaseDate))?>
                                                </div>
                                            </td>

                                            <!-- CLIENT -->
                                            <td class='mx-0' width='25%'> 
                                                <div class='d-flex flex-row justify-content-center text-secondary py-4'>
                                                    <div>&#36;&nbsp;</div>
                                                    <div><?=$credit?></div>
                                                </div>
                                            </td>
                                                

                                            <!-- PRODUCT ID & NAME -->
                                            <td class='mx-0' width='25%'> 
                                                <div class='d-flex flex-row justify-content-center text-secondary py-4'>
                                                    <div>&#36;&nbsp;</div>
                                                    <div><?=$debit?></div>
                                                </div>
                                            </td>

                                            <!-- VARIATION NAME -->
                                            <td class='mx-0' width='25%'> 
                                                <div class='py-4 text-secondary'>
                                                    <div><?=$description?></div>
                                                </div>
                                            </td>

                                        
                                           
                                        </tr>
                                    
                                    <?php } ?>
                                </tbody>
                            </table>

                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12 px-2 mb-0 table-responsive-sm">
                            
                            <table class="table table-hover borderless text-center bg-gray mb-0">
                                <thead>
                                    <tr class='py-0'>
                                    
                                        <td width='33.33%'>Total Debit</td>
                                        <td width='33.33%'>Total Credit</td>
                                        <td width='33.33%'>Balance</td>
                                        <!-- <td width='15%'>Amount</td> -->
                                        <!-- <td width='10%'>View</td> -->

                                    </tr> 
                                </thead>
                                <tbody style='background:white;height:600px;overflow-y:auto;font-size:12px;'>

                                    
                                        <tr>
                                            <!-- DEBIT -->
                                            <td class='mx-0 font-weight-bold' width='33.33%'>
                                                <div class='d-flex flex-row justify-content-center text-secondary py-4'>
                                                    <div>&#36;&nbsp;</div>
                                                    <div><?=showStoreCredit($conn,$storeId)?></div>
                                                <div>
                                            </td>

                                            <!-- CREDIT -->
                                            <td class='mx-0 font-weight-bold' width='33.33%'> 
                                                <div class='d-flex flex-row justify-content-center text-secondary py-4'>
                                                    <div>&#36;&nbsp;</div>
                                                    <div> <?=showStoreDebit($conn,$storeId)?></div>
                                                </div>
                                            </td>
                                                

                                            <!-- PRODUCT ID & NAME -->
                                            <td class='mx-0 font-weight-bold' width='33.33%'> 
                                                <div class='d-flex flex-row justify-content-center text-secondary py-4'>
                                                    <div>&#36;&nbsp;</div>
                                                    <div> <?=showStoreBalance($conn,$storeId)?></div>
                                                </div>
                                            </td>

                                           
                                        </tr>
                                    
                
                                </tbody>
                            </table>

                        </div>
                    </div>

                    <?php } ?>

                               
                </div>

            </div>
            <!-- /MAIN BAR -->


        </div>
        <!-- /.ROW -->


    </div>
    <!-- /.CONTAINER -->