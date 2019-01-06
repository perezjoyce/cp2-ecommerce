<?php
require_once '../sources/pdo/src/PDO.class.php';
require_once "connect.php";
require_once "../../config.php";
require_once "functions.php";

    if(isset($_POST['storeId'])){
        $storeId = $_POST['storeId'];
        $searchkey = $_POST['searchkey'];

        $storeFollowers = countFollowers ($conn, $storeId);
        $storeRating = getAverageStoreRating ($conn, $storeId);
        $storeMembershipDate = getMembershipDate($conn, $storeId);
        $storeShippingFee = displayStoreShippingFee($conn,$storeId);
        $storeFreeShippingMinimum = displayStoreFreeShipping($conn,$storeId, false);


        $sql = "SELECT o.payment_mode_id,o.billing_address_id, o.address_id as 'shipping_address_id', o.purchase_date, o.transaction_code, o.id 
                AS 'transaction_id', o.status_id, o.cart_session, o.user_id, c.variation_id, c.quantity, v.variation_name 
                AS 'variation_name', i.id 
                AS 'product_id', i.name 
                AS 'product_name', i.store_id, i.price 
                FROM tbl_carts c 
                JOIN tbl_variations v 
                JOIN tbl_items i 
                JOIN tbl_orders o 
                ON v.product_id=i.id 
                AND c.variation_id=v.id 
                AND o.cart_session=c.cart_session 
                WHERE c.status_id = ? AND store_id = ? AND transaction_code LIKE ? OR transaction_code = ? GROUP BY o.cart_session";
                    $statement = $conn->prepare($sql);
                    $statement->execute([1,$storeId,"%$searchkey%", "%$searchkey%"]);
                    $count = $statement->rowCount();
            
                            

                if($count) {
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
                        <a data-href="../partials/templates/new_order_summary_modal.php?id=<?=$storeId?>&cart=<?=$newOrderCartSession?>" class='border-0 btn_view_new_order' style='cursor:pointer;size:15px;'>
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

                
<?php } } } else { {
    echo "fail"; } } ?>