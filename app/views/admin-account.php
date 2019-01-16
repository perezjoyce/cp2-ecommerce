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
                                <input type="text" class="form-control border-right-0 border-left-0 border-top-0" id="admin_search" data-location='account'>
                            </div>
                        </div>
						
					</div>
                </div>

                
                
                <!-- COMPLETED ORDERS -->
                <div class="container px-5 pb-5 rounded" style='background:white;'>
                               
                                
                    <?php
                    $sql = " SELECT ROUND(COUNT(store_id) /2, 0) 
                            AS 'transactionCount', store_id, SUM(credit) 
                            AS 'storeCredit', SUM(debit) 
                            AS 'storeDebit' 
                            FROM tbl_seller_accounts 
                            GROUP BY store_id";
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
                                    
                                        <td width='20%'>Store</td>
                                        <td width='20%'>
                                            <div class="d-flex align-items-center text-center justify-content-center">
                                                <div>
                                                   Transaction Count
                                                </div>
                                                <div class='d-flex flex-column'>
                                                    <i class="fas fa-angle-up text-gray pl-2 btn_sort" data-location='account' data-column="transactionCount" data-order='ASC' style='cursor: pointer;'></i>
                                                    <i class="fas fa-angle-down text-gray pl-2 btn_sort" data-location='account' data-column="transactionCount" data-order='DESC' style='cursor: pointer;'></i>
                                                </div>
                                            </div>
                                        </td>
                                        <td width='20%'>
                                            <div class="d-flex align-items-center text-center justify-content-center">
                                                <div>
                                                   Credit
                                                </div>
                                                <div class='d-flex flex-column'>
                                                    <i class="fas fa-angle-up text-gray pl-2 btn_sort" data-location='account' data-column="storeCredit" data-order='ASC' style='cursor: pointer;'></i>
                                                    <i class="fas fa-angle-down text-gray pl-2 btn_sort" data-location='account' data-column="storeCredit" data-order='DESC' style='cursor: pointer;'></i>
                                                </div>
                                            </div>
                                        </td>
                                        <td width='20%'>
                                            <div class="d-flex align-items-center text-center justify-content-center">
                                                <a data-toggle="tooltip" title="Credit Charge is 3%" data-original-title="#">
                                                    <i class="far fa-question-circle text-gray pr-2"></i>
                                                </a>
                                                Credit Charge
                                                <div class='d-flex flex-column'>
                                                    <i class="fas fa-angle-up text-gray pr-2 btn_sort" data-location='account' data-column="storeDebit" data-order='ASC' style='cursor: pointer;'></i>
                                                    <i class="fas fa-angle-down text-gray pr-2 btn_sort" data-location='account' data-column="storeDebit" data-order='DESC' style='cursor: pointer;'></i>
                                                </div>
                                            </div>
                                        </td>
                                        <td width='20%'>View</td>
                                        
                                    </tr> 
                                </thead>
                                <tbody style='background:white;height:600px;overflow-y:auto;font-size:14px;' class='admin-data-container'>

  
                                    <?php 
                                        while($row = $statement->fetch()){ 
                                            $storeId = $row['store_id'];
                                            $transactionCount = $row['transactionCount'];
                                            // $transactionCount = round($transactionCount, 0);
                                            $credit = $row['storeCredit'];
                                            $credit = number_format((float)$credit, 2, '.', ',');
                                            $debit = $row['storeDebit'];
                                            $debit = number_format((float)$debit, 2, '.', ',');
                                            $storeName = getStoreNameFromStoreId($conn, $storeId);
                                    ?>
                                    
                                        <tr>
                                            <!-- STORE NAME -->
                                            <td class='mx-0' width='20%'>
                                                <div class='py-4 text-secondary'>
                                                    <?=$storeName?>
                                                </div>
                                            </td>

                                            <!-- TRANSACTION COUNT -->
                                            <td class='mx-0' width='20%'>
                                                <div class='py-4 text-secondary'>
                                                    <?= $transactionCount?>
                                                </div>
                                            </td>

                                            <!-- BALANCE -->
                                            <td class='mx-0' width='20%'> 
                                                <div class='d-flex flex-row justify-content-center text-secondary py-4'>
                                                    <div>&#36;&nbsp;</div>
                                                    <div><?=$credit?></div>
                                                </div>
                                            </td>
                                                

                                            <!-- CREDIT CHARGE -->
                                            <td class='mx-0' width='20%'> 
                                                <div class='d-flex flex-row justify-content-center text-secondary py-4'>
                                                    <div>&#36;&nbsp;</div>
                                                    <div><?=$debit?></div>
                                                </div>
                                            </td>

                                            <!-- VIEW -->
                                            <td class='mx-0' width='20%'>
                                                <a data-href="../partials/templates/account_summary_modal.php?id=<?=$storeId?>" class='border-0 btn_view_account' style='cursor:pointer;size:15px;'>
                                                    <i class="far fa-file-pdf text-gray py-4" style='width:100%;'></i>
                                                </a>
                                            </td>

                                           
                                        </tr>
                                    
                                    <?php } ?>
                                </tbody>
                            </table>
                            <div class="container">
                                <div class="row">
                                    <div class="col-12 text-center admin-data-container-error">
                                       <!-- ERROR HERE -->
                                    </div>
                                </div>
                            </div>

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
                                            FROM tbl_seller_accounts ";
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
                                <tbody style='background:white;height:600px;overflow-y:auto;font-size:14px;'>

                                    
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