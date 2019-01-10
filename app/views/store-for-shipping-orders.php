<?php require_once "../../config.php";?>
<?php 
    
    $id = $_GET['id'];
    if(empty($id)){ 
        header("location: index.php");
    } else {

        $storeInfo = $storeId = getStore ($conn,$id);
        $id = $_SESSION['id'];
        $currentUser = getUser($conn, $id);
        $isSeller = $currentUser['isSeller'] == "yes" ? 1 : 0;   
        
        $userIsStoreOwner = false;
        //IF USER IS NOT STORE OWNER, REDIRECT TO ORIGIN
        if($id === $storeInfo['user_id']) {
            $userIsStoreOwner = true;
        } else {
            echo '<script>history.go(-1);</script>';
        }
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

<?php require_once "../partials/store_header.php";?>
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
                                    <span class="input-group-text border-right-0 border-left-0 border-top-0" style='background:white;'>
                                        <i class="fas fa-search" style='background:white;'></i>
                                    </span>
                                </div>
                                <input type="text" 
                                    class="form-control border-right-0 border-left-0 border-top-0" 
                                    data-storeid='<?=$storeId?>'
                                    id="btn_search_shipping" 
                                    placeholder='Search using transaction codes'>
                            </div>
                        </div>
						
					</div>
                </div>

                
                
                <!-- NEW ORDERS -->
                <div class="container px-5 pb-5 rounded" style='background:white;'>
                               
                                
                    <?php
                    $sql = " SELECT o.payment_mode_id, o.address_id as 'shipping_address_id', o.billing_address_id, o.purchase_date, o.transaction_code, o.id as 'transaction_id', o.status_id, o.cart_session, o.user_id , i.store_id, i.price
                    FROM  tbl_orders o JOIN tbl_carts c JOIN tbl_items i JOIN tbl_variations v ON v.product_id = i.id AND c.variation_id=v.id AND o.cart_session=c.cart_session WHERE store_id = ? AND c.status_id = 2 ORDER BY purchase_date DESC ";
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
                                    <td width='25%'>Address</td>
                                    <!-- <td width='15%'>Contact Number</td> -->
                                    <!-- <td width='15%'>Tracking Number</td> -->
                                    <td width='15%'>
                                        Amount Due
                                        <a data-toggle="tooltip" title="For COD Only" data-original-title="#">
                                            &nbsp;<i class="far fa-question-circle text-gray"></i>
                                        </a>
                                    </td>
                                    <td width='15%'>View</td>
                                    <!-- IF COD, STATE HOW MUCH IS DUE -->
                                    <td width='15%'>Action</td>

                                    
                                </tr> 
                            </table>
                               
                        </div>
                    </div>
                    <div class="row">
                        <div class="col px-2">
                            <div class="container px-0" style='background:white;height:600px;overflow-y:auto;font-size:12px;'>
                                <table class="table table-hover borderless text-center" id='data-container'>
                                    
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

                                        <!-- PURCHASE DATE -->
                                        <td class='mx-0' width='15%'>
                                            <div class='py-4 text-secondary'>
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
                                        </td>

                                        <!-- RECEPIENT -->
                                        <td class='mx-0' width='15%'> 
                                            <div class='py-4 text-secondary'>
                                                <?= getNameFromShippingAddressId($conn,$shippingAddressId) ?>
                                            </div>
                                        </td>
                                        
                                        
                                        <!-- RECEPIENTS ADDRESS -->
                                        <td class='mx-0 text-center' width='25%'> 
                                            <div class="py-4 px-2 text-secondary">
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
                                                <?= getRegionName($conn, $regionId) ?>
                                                <br>
                                                <span class='text-gray'>
                                                    <?=$landmark?>
                                                </span>
                                            </div>
                                        </td>
                                        


                                        <!-- CONTACT NUMBER -->
                                        <!-- <td class='mx-0 py-3' width='15%'>
                                            <a data-url="../partials/templates/view_order_summary_modal.php" data-id='$orderHistoryCartSession class='border-0 btn_view_order_history' style='cursor:pointer;size:15px;'>
                                                -
                                            </a>
                                        </td> -->
                                        

                                            <!-- TRACKING NUMBER -->
                                            <!-- <td class='mx-0' width='15%'> 
                                            <div class='py-4 text-secondary'>
                                                <input type="text" style='width:100%;'>
                                            </div>
                                        </td> -->

                                        
                                            <!-- SHIPPING FEE -->
                                            <td class='mx-0' width='15%'> 
                                            <div class='py-4 text-secondary'>

                                                <?php  
                                                    $sql3 = "SELECT SUM(quantity*price) as 'subtotal_for_items', c.cart_session, c.variation_id, c.quantity, v.variation_name, v.product_id, i.price, i.store_id FROM tbl_carts c JOIN tbl_variations v JOIN tbl_items i ON c.variation_id=v.id AND v.product_id=i.id WHERE  cart_session = ? AND store_id = ?";
                                                    $statement3 = $conn->prepare($sql3);
                                                    $statement3->execute([$newOrderCartSession,$storeId]);	
                                                    $count3 = $statement3->rowCount();

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
                                            
                                                ?>
                                                    <input type="hidden" value='<?=$subTotalForItems?>'>
                                                    <input type="hidden" value='<?=$storeFreeShippingMinimum?>'>

                                            </div>
                                        </td>

                                            <!-- VIEW -->
                                        <td class='mx-0' width='15%'>
                                            <a data-href="../partials/templates/shipping_order_summary_modal.php?id=<?=$storeId?>&cart=<?=$newOrderCartSession?>" class='border-0 btn_view_new_order' style='cursor:pointer;size:15px;'>
                                                <i class="far fa-file-pdf text-gray py-4" style='width:100%;'></i>
                                            </a>
                                        </td>

                                        <!-- ACTION -->
                                        <td class='mx-0' width='15%'>                                                     
                                            <div class='py-4 font-weight-light'>       
                                                <!-- ONCE CLICKED, WILL BE TRANSFERRED TO ORDER HISTORY -->
                                                <a class="btn border btn_complete_order" data-cartsession='<?=$newOrderCartSession?>' data-storeid='<?=$storeId?>' data-storename='<?=$storeName?>'><small>MARK AS COMPLETE</small></a>                                                                
                                            </div> 
                                        </td>

        
                                            
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

<?php require_once "../partials/footer.php";?>
<?php require_once "../partials/modal_container.php";?>
<?php require_once "../partials/modal_container_big.php"; ?>

  