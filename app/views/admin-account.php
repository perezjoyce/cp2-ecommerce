<?php require_once "../../config.php";?>
<?php 
    
    if(empty($_GET['id'])){ 
        echo "<script>window.location.href='".BASE_URL."/app/views/'</script>";
    } else {

        if(isset($_SESSION['id'])){
            $adminid = $_SESSION['id'];
            $currentUser = getUser($conn, $adminid);
            if($currentUser['userType'] == "admin") {
                require_once "../partials/admin_header.php";
            } else {
                echo "<script>window.location.href='".BASE_URL."/app/views/'</script>";
            }
        } else {
            echo "<script>window.location.href='".BASE_URL."/app/views/'</script>";
        }
    }

?>

<br>
    <div class="container p-0 my-lg-5 mt-md-5">

        <div class="row mx-0">

          
            <!-- MAIN BAR-->
            <div class="col">

                <!-- SEARCH BAR -->
                <div class='container p-5 rounded' style='background:white;'>
                    <div class="row mx-0">
                        <div class="col-lg-6 col-md-4 col-sm-12">
                            <h4>Mamaroo Account</h4>
                        </div>
                        <div class="col">
                            <div class="input-group input-group-lg">
                                <div class="input-group-prepend">
                                    <span class="input-group-text border-right-0 border-left-0 border-top-0" id="store_page_search_button" style='background:white;'>
                                        <i class="fas fa-search" style='background:white;'></i>
                                    </span>
                                </div>
                                <input type="text" class="form-control border-right-0 border-left-0 border-top-0" id="store_page_search">
                            </div>
                        </div>
						
					</div>
                </div>

                
                
                <!-- COMPLETED ORDERS -->
                <div class="container px-5 pb-5 rounded" style='background:white;'>
                               
                                
                    <?php
                    $sql = " SELECT store_id, SUM(credit) as 'storeCredit', SUM(debit) as 'storeDebit' FROM tbl_seller_account GROUP BY store_id ";
                                $statement = $conn->prepare($sql);
                                $statement->execute();
                                $count = $statement->rowCount();
            
                            if($count) {
                    ?>

                    <div class="row mb-5">
                        <div class="col-12 px-2 mb-0 table-responsive-sm">
                            
                            <table class="table table-hover borderless text-center bg-gray mb-0">
                                <thead>
                                    <tr class='py-0'>
                                    
                                        <td width='25%'>Store</td>
                                        <td width='25%'>Credit</td>
                                        <td width='25%'>
                                            <span>Credit Charge</span>
                                            <a data-toggle="tooltip" title="Credit Charge is 3%" data-original-title="#">
                                                <i class="far fa-question-circle text-gray"></i>
                                            </a>
                                        </td>
                                        <td width='25%'>View</td>
                                        
                                    </tr> 
                                </thead>
                                <tbody style='background:white;height:600px;overflow-y:auto;font-size:12px;'>

  
                                    <?php 
                                        while($row = $statement->fetch()){ 
                                            $storeId = $row['store_id'];
                                            $credit = $row['storeCredit'];
                                            $creidt = number_format((float)$credit, 2, '.', ',');
                                            $debit = $row['storeDebit'];
                                            $debit = number_format((float)$debit, 2, '.', ',');
                                    ?>
                                    
                                        <tr>
                                            <!-- STORE NAME -->
                                            <td class='mx-0' width='25%'>
                                                <div class='py-4 text-secondary'>
                                                    <?=getStoreNameFromStoreId($conn, $storeId)?>
                                                </div>
                                            </td>

                                            <!-- BALANCE -->
                                            <td class='mx-0' width='25%'> 
                                                <div class='d-flex flex-row justify-content-center text-secondary py-4'>
                                                    <div>&#36;&nbsp;</div>
                                                    <div><?=$credit?></div>
                                                </div>
                                            </td>
                                                

                                            <!-- CREDIT CHARGE -->
                                            <td class='mx-0' width='25%'> 
                                                <div class='d-flex flex-row justify-content-center text-secondary py-4'>
                                                    <div>&#36;&nbsp;</div>
                                                    <div><?=$debit?></div>
                                                </div>
                                            </td>

                                            <!-- VIEW -->
                                            <td class='mx-0' width='25%'>
                                                <a data-href="../partials/templates/account_summary_modal.php?id=<?=$storeId?>" class='border-0 btn_view_account' style='cursor:pointer;size:15px;'>
                                                    <i class="far fa-file-pdf text-gray py-4" style='width:100%;'></i>
                                                </a>
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

                                    <?php 
                                    $sql = "SELECT  SUM(credit) 
                                            as 'balance', SUM(debit) 
                                            as 'totalEarningsFromServiceCharge', SUM(credit) - SUM(debit) 
                                            as 'debit' 
                                            FROM tbl_seller_account ";
                                            $statement = $conn->prepare($sql);
                                            $statement->execute();
                                            $row = $statement->fetch();
                                            $balance = $row['balance'];
                                            $balance = number_format((float)$balance, 2, '.', ',');
                                            $totalEarnings = $row['totalEarningsFromServiceCharge'];
                                            $totalEarnings = number_format((float)$totalEarnings, 2, '.', ',');;
                                            $debit = $row['debit'];
                                            $debit = number_format((float)$debit, 2, '.', ',');
                                           

                                    ?>
                                    
                                        <td width='33.33%'>Total Balance</td>
                                        <td width='33.33%'>Total Debit</td>
                                        <td width='33.33%'>Total Earnings</td>

                                    </tr> 
                                </thead>
                                <tbody style='background:white;height:600px;overflow-y:auto;font-size:12px;'>

                                    
                                        <tr>
                                            <!-- DEBIT -->
                                            <td class='mx-0 font-weight-bold' width='33.33%'>
                                                <div class='d-flex flex-row justify-content-center text-secondary py-4'>
                                                    <div>&#36;&nbsp;</div>
                                                    <div><?=$balance?></div>
                                                <div>
                                            </td>

                                            <!-- CREDIT -->
                                            <td class='mx-0 font-weight-bold' width='33.33%'> 
                                                <div class='d-flex flex-row justify-content-center text-secondary py-4'>
                                                    <div>&#36;&nbsp;</div>
                                                    <div><?=$debit?></div>
                                                </div>
                                            </td>
                                                

                                            <!-- PRODUCT ID & NAME -->
                                            <td class='mx-0 font-weight-bold' width='33.33%'> 
                                                <div class='d-flex flex-row justify-content-center text-secondary py-4'>
                                                    <div>&#36;&nbsp;</div>
                                                    <div><?=$totalEarnings?></div>
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



<?php require_once "../partials/footer.php";?>
<?php require_once "../partials/modal_container.php";?>
<?php require_once "../partials/modal_container_big.php"; ?>