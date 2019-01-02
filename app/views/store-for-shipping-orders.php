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
                            <h4>For Shipping</h4>
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

                
                
                <!-- NEW ORDERS -->
                <div class="container px-5 pb-5 rounded" style='background:white;'>
                               
                                
                    <?php
                    $sql = " SELECT o.payment_mode_id, o.address_id as 'shipping_address_id', o.billing_address_id, o.purchase_date, o.transaction_code, o.id as 'transaction_id', o.status_id, o.cart_session, o.user_id , i.store_id, i.price
                    FROM  tbl_orders o JOIN tbl_carts c JOIN tbl_items i JOIN tbl_variations v ON v.product_id = i.id AND c.variation_id=v.id AND o.cart_session=c.cart_session WHERE store_id = ? AND status_id = 2 ORDER BY purchase_date DESC ";
                                $statement = $conn->prepare($sql);
                                $statement->execute([$storeId]);
                                $count = $statement->rowCount();
            
                            if($count) {
                    ?>

                    <div class="row">
                        <div class="col-12 px-2 mb-0">
                            
                            <table class="table borderless text-center bg-gray mb-0">
                                <tr class='py-0'>
                                
                                    <td width='15%'>Time Ago</td>
                                    <td width='15%'>Recepient</td>
                                    <td width='15%'>Address</td>
                                    <td width='15%'>Contact Number</td>
                                    <td width='15%'>Tracking Number</td>
                                    <td width='15%'>
                                        Amount Due
                                        <a data-toggle="tooltip" title="For COD Only" data-original-title="#">
                                            &nbsp;<i class="far fa-question-circle text-gray"></i>
                                        </a>
                                    </td>
                                    <!-- IF COD, STATE HOW MUCH IS DUE -->
                                    <td width='12.5%'>Action</td>

                                    
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
                                                $purchaseDate = $row['purchase_date'];
                                                $transactionId = $row['transaction_id'];
                                                $transactionCode = $row['transaction_code'];
                                                $clientId = $row['user_id'];
                                                // $whoWillPay = $row['billing_address_id'];
                                                $shippingAddressId = $row['shipping_address_id'];
                                                // $productId = $row['product_id'];
                                                // $productName = $row['product_name'];
                                                // $variationId = $row['variation_id'];
                                                // $variationName = $row['variation_name'];
                                                // $quantity = $row['quantity'];
                                                // $price = $row['price'];
                                                // $status = $row['status'];
                                                $newOrderCartSession = $row['cart_session'];
                                                $paymentModeId = $row['payment_mode_id'];

                                                // FETCH ADDRESS
                                                $sql2 = "SELECT * FROM tbl_addresses WHERE id = ?";
                                                $statement2 = $conn->prepare($sql2);
                                                $statement2->execute([$shippingAddressId]);	
                                                $count2 = $statement2->rowCount();

                                                // FETCH TOTAL PRICE
                                                $sql3 = "SELECT SUM(quantity*price) as 'subtotal_for_items', c.cart_session, c.variation_id, c.quantity, v.variation_name, v.product_id, i.price, i.store_id FROM tbl_carts c JOIN tbl_variations v JOIN tbl_items i ON c.variation_id=v.id AND v.product_id=i.id WHERE  cart_session = ? AND store_id = ?";
                                                $statement3 = $conn->prepare($sql3);
                                                $statement3->execute([$newOrderCartSession,$storeId]);	
                                                $count3 = $statement3->rowCount();
                                               
                                    ?>
                                       
                                    
                                    <tr>

                                        <div>

                                            <!-- PURCHASE DATE -->
                                            <td class='mx-0' width='15%'>
                                                <a data-url="../partials/templates/view_order_summary_modal.php" data-id='<?=$orderHistoryCartSession?>' class='border-0 btn_view_order_history' style='cursor:pointer;size:15px;'>
                                                    <div class='py-3 text-secondary'>
                                                        <?php 

                                                            $datetime1 = new DateTime($purchaseDate);
                                                            $datetime2 = new DateTime();
                                                            $interval = $datetime1->diff($datetime2);
                                                            $ago = "";


                                                            if($interval->format('%w') != 0) {
                                                                $ago = $interval->format('%w weeks ago');
                                                            } else {
                                                                if($interval->format('%d') != 0) {
                                                                    $ago = $interval->format('%d days ago ');
                                                                } else {
                                                                    if($interval->format('%h') != 0) {
                                                                        $ago = $interval->format('%h hrs ago');
                                                                    } elseif($interval->format('%i') != 0) {
                                                                        $ago = $interval->format('%i minutes ago');
                                                                    } else {
                                                                        $ago = "
                                                                        <small>
                                                                            <i class='fas fa-circle text-success'>&nbsp;</i>
                                                                        </small>
                                                                        Just Now
                                                                        ";
                                                                    }
                                                                }
                                                                
                                                            }

                                                            echo $ago;
                                                        ?>
                                                    </div>
                                                </a>
                                            </td>

                                            <!-- RECEPIENT -->
                                            <td class='mx-0' width='15%'> 
                                                <a data-url="../partials/templates/view_order_summary_modal.php" data-id='<?=$orderHistoryCartSession?>' class='border-0 btn_view_order_history' style='cursor:pointer;size:15px;'>
                                                    <div class='py-3 text-secondary'>
                                                      <?= getNameFromShippingAddressId($conn,$shippingAddressId) ?>
                                                    </div>
                                                </a>
                                            </td>
                                            
                                            
                                            <!-- RECEPIENTS ADDRESS -->
                                            <td class='mx-0' width='15%'> 
                                                <div class="py-3">
                                                    <a data-url="../partials/templates/view_order_summary_modal.php" data-id='<?=$orderHistoryCartSession?>' class='btn_view_order_history flex-fill text-right text-secondary' style='cursor:pointer;size:15px;'>
                                                       
                                                        <small>
                                                            <?php
                                                           
                                                                if($count2) {
                                                                    $row2 = $statement2->fetch();
                                                                    $landmark = $row2['landmark'];
                                                                    $landmark = ucwords(strtolower($landmark));
            
                                                                    $street = $row2['street_bldg_unit'];
                                                                    $street = ucwords(strtolower($street));
            
                                                                    $regionId = $row2['region_id'];
                                                                    $provId = $row2['province_id'];
                                                                    $cityId = $row2['city_id'];
                                                                    $brgyId = $row2['brgy_id'];
                                                                }
                                                            ?>
                                                            <?=$street?>,&nbsp;
                                                            <?= getBrgyName($conn, $brgyId) ?>,&nbsp;
                                                            <?= getCityName($conn, $cityId) ?>,&nbsp;
                                                            <?= getProvinceName($conn, $provId) ?>,
                                                            <?= getRegionName($conn, $regionId) ?>&nbsp; -- &nbsp;
                                                            
                                                            <span class='text-gray'>
                                                                <?=$landmark?>
                                                            </span>
                                                            
                                                        </small>
                                                    </a>

                                                </div>
                                               
                                            </td>
                                           


                                            <!-- CONTACT NUMBER -->
                                            <td class='mx-0 py-3' width='15%'>
                                                <a data-url="../partials/templates/view_order_summary_modal.php" data-id='<?=$orderHistoryCartSession?>' class='border-0 btn_view_order_history' style='cursor:pointer;size:15px;'>
                                                    -
                                                </a>
                                            </td>
                                           

                                             <!-- TRACKING NUMBER -->
                                             <td class='mx-0' width='15%'> 
                                                <div class='py-3 text-secondary'>
                                                    <input type="text" style='width:100%;'>
                                                </div>
                                            </td>

                                            
                                             <!-- SHIPPING FEE -->
                                             <td class='mx-0' width='15%'> 
                                                <a data-url="../partials/templates/view_order_summary_modal.php" data-id='<?=$orderHistoryCartSession?>' class='border-0 btn_view_order_history' style='cursor:pointer;size:15px;'>
                                                    <div class='py-3 text-secondary'>

                                                        <?php  if($paymentModeId == 1) {

                                                            if($count3){
                                                                
                                                                $row3 = $statement3->fetch();
                                                                $subTotalForItems = $row3['subtotal_for_items'];
                                                                
                                                                if($subTotalForItems >= $storeFreeShippingMinimum){
                                                                    $subTotalForShipping = 0;
                                                                } else {
                                                                    $subTotalForShipping = $storeShippingFee;
                                                                }

                                                                $grandTotalFee = $subTotalForItems + $subTotalForShipping;
                                                                $grandTotalFee = number_format((float)$grandTotalFee, 2, '.', ',');

                                                                echo "&#8369;&nbsp;".$grandTotalFee;
                                                            }

                                                        } else { echo "Paid"; } ?>

                                                    </div>
                                                </a>
                                            </td>

                                            <!-- ACTION -->
                                            <td class='mx-0' width='15%'> 
                                                <a data-url="../partials/templates/view_order_summary_modal.php" data-id='<?=$orderHistoryCartSession?>' class='border-0 btn_view_order_history' style='cursor:pointer;size:15px;'>
                                                    <div class='py-3 text-gray'>
                                                        <div class="dropdown show">
                                                            <a class="btn border dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                                <small>CHOOSE 1</small>    
                                                            </a>

                                                            <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                                                                <!-- ONCE CLICKED, TRACKING WILL BE UPDATED IN MODALS AND MESSAGE WILL BE GENERATED TO SELLER -->
                                                                <a class="dropdown-item" href="#"><small>SEND TRACKING #</small></a>
                                                                
                                                                <!-- ONCE CLICKED, WILL BE TRANSFERRED TO ORDER HISTORY -->
                                                                <a class="dropdown-item" href="#"><small>MARK AS COMPLETE</small></a>
                                                                
                                                                
                                                        </div>
                                                        <!-- put dropdown with two buttons: SEND MESSAGE, CONFIRM, CANCELL, COMPLETE -->
                                                    </div>
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

  