<?php require_once "../../config.php";?>
<?php require_once "../controllers/connect.php";?>
<?php require_once "../controllers/functions.php";?>

<?php 
    // if(!isset($_SESSION['id'])) {
    //     // ob_clean();
    //     // header("location: index.php?msg=NotLoggedIn"); // doesn't work because header already exists
    //     // ECHO THIS TO REDIRECT YOU TO HEADER
    //     echo "<script>window.location.href='".BASE_URL."/app/views/'</script>";
    // }

    $id = $_GET['id'];
    if(empty($id)) {
        header("location: index.php");
    } else {

        // get the store information

        $storeInfo = $storeId = getStore ($conn,$id);
        $id = $_SESSION['id'];
        $currentUser = getUser($conn, $id);
        $isSeller = $currentUser['isSeller'] == "yes" ? 1 : 0;    
    }  

    if ($isSeller && $currentUser['id'] == $storeInfo['user_id']) {
        require_once "../partials/store_header.php";
    } else {
        require_once "../partials/header.php";
    }

    $storeId = $storeInfo['id'];
    $storeName = $storeInfo['name'];
    $storeLogo = $storeInfo['logo'];
    $storeDescription = $storeInfo['description'];
    $storeAddress = $storeInfo['store_address'];
    $storeHours = $storeInfo['hours'];
    $storeFollowers = countFollowers ($conn, $storeId);
    $storeRating = getAverageStoreRating ($conn, $storeId);
    $storeMembershipDate = getMembershipDate($conn, $storeId);
    $storeShippingFee = displayStoreShippingFee($conn,$storeId);
    $storeFreeShippingMinimum = displayStoreFreeShipping($conn,$storeId);
    $fname = getFirstName ($conn,$id);
    $lname = getLastName ($conn,$id);
?>
    <!-- PAGE CONTENT -->
    <br>
    <div class="container p-0 my-lg-5 mt-md-5">

        


        <div class="row mx-0">

          
            <!-- MAIN BAR-->
            <div class="col">

                <!-- SEARCH BAR -->
                <div class='container p-5 rounded' style='background:white;'>
                    <div class="row mx-0">
                        <div class="col-6">
                            <h4>Order History</h4>
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

                
                
                <!-- ORDER HISTORY -->
                <div class="container px-5 pb-5 rounded" style='background:white;'>
                               
                                
                    <!-- PENDING TRANSACTIONS -->
                    <?php
                    $sql = "SELECT o.*, s.name as `status` FROM tbl_orders o JOIN tbl_status s ON o.status_id=s.id WHERE status_id = 3 OR status_id = 4  AND `user_id` = ? ORDER BY o.purchase_date DESC";
                                $statement = $conn->prepare($sql);
                                $statement->execute([$id]);
                                $count = $statement->rowCount();
            
                            if($count) {
                    ?>

                    <div class="row">
                        <div class="col-12 px-2 mb-0">
                            
                            <table class="table borderless text-center bg-gray mb-0">
                                <tr class='py-0'>
                                
                                    <td width='20%'>Date</td>
                                    <td width='30%'>Transaction Code</td>
                                    <td width='20%'>Payment Mode</td>
                                    <td width='15%'>Status</td>
                                    <td width='15%'>View</td>
                                    
                                </tr> 
                            </table>
                               
                        </div>
                    </div>
                    <div class="row">
                        <div class="col px-2">
                            <div class="container px-0" style='background:white;height:600px;overflow-y:auto;'>
                                <table class="table table-hover borderless text-center">
                                    
                                    <?php 
                                            while($row = $statement->fetch()){ 
                                            $transactionId = $row['id'];
                                            $transactionCode = $row['transaction_code'];
                                            $purchaseDate = $row['purchase_date'];
                                            $status = $row['status'];
                                            $orderHistoryCartSession = $row['cart_session'];
                                            $paymentModeId = $row['payment_mode_id'];
                                    ?>
                                    
                                    <tr>

                                        <div>

                                            <!-- PURCHASE DATE -->
                                            <td class='mx-0' width='20%'>
                                                <a data-url="../partials/templates/view_order_summary_modal.php" data-id='<?=$orderHistoryCartSession?>' class='border-0 btn_view_order_history' style='cursor:pointer;size:15px;'>
                                                    <div class='py-3 text-secondary'><?=date("M d, Y", strtotime($purchaseDate))?></div>
                                                </a>
                                            </td>
                                            
                                            
                                            <!-- TRANSACTION CODE -->
                                            <td class='mx-0' width='30%'> 
                                                <a data-url="../partials/templates/view_order_summary_modal.php" data-id='<?=$orderHistoryCartSession?>' class='border-0 btn_view_order_history' style='cursor:pointer;size:15px;'>
                                                    <div class='py-3'><?=$transactionCode ?></div>
                                                </a>
                                            </td>

                                            <!-- PAYMENT METHOD -->
                                            <td class='mx-0' width='20%'> 
                                                <a data-url="../partials/templates/view_order_summary_modal.php" data-id='<?=$orderHistoryCartSession?>' class='border-0 btn_view_order_history' style='cursor:pointer;size:15px;'>
                                                    <div class='py-3'>
                                                        <?= getModeOfPaymentShort($conn, $paymentModeId) ?>
                                                    </div>
                                                </a>
                                            </td>

                                            <!-- STATUS-->
                                            <td class='mx-0' width='15%'> 
                                                <a data-url="../partials/templates/view_order_summary_modal.php" data-id='<?=$orderHistoryCartSession?>' class='border-0 btn_view_order_history' style='cursor:pointer;size:15px;'>
                                                    <div class='py-3 text-gray'>
                                                        <?= ucfirst($status) ?>
                                                    </div>
                                                </a>
                                            </td>

                                            <!-- VIEW -->
                                            <td class='mx-0' width='15%'>
                                                <a data-url="../partials/templates/view_order_summary_modal.php" data-id='<?=$orderHistoryCartSession?>' class='border-0 btn_view_order_history' style='cursor:pointer;size:15px;'>
                                                    <i class="far fa-file-pdf text-gray py-3" style='width:100%;'></i>
                                                </a>
                                            </td>
                                            
                                        </div>
                                        
                                    </tr>
                                    <?php } ?>
        
                                </table>

                                
                            
                            </div>
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

    <!-- IF USER IS LOGGED IN AND USER IS NOT THE SELLER -->
    <?php if(isset($_SESSION['id']) && !$isSeller){ include '../partials/message_box.php'; } ?>

<?php require_once "../partials/footer.php";?>
<?php require_once "../partials/modal_container.php";?>
<?php require_once "../partials/modal_container_big.php"; ?>

  