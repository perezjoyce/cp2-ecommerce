<?php
require_once '../../config.php';

    if(isset($_POST['storeId'])){
        $storeId = $_POST['storeId'];
        $searchkey = $_POST['searchkey'];

        $storeFollowers = countFollowers ($conn, $storeId);
        $storeRating = getAverageStoreRating ($conn, $storeId);
        $storeMembershipDate = getMembershipDate($conn, $storeId);
        $storeShippingFee = displayStoreShippingFee($conn,$storeId);
        $storeFreeShippingMinimum = displayStoreFreeShipping($conn,$storeId, false);


        $sql = "SELECT o.payment_mode_id,o.billing_address_id, o.purchase_date, o.transaction_code, o.id 
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
                        $whoWillPay = $row['billing_address_id'];
                        $productId = $row['product_id'];
                        $productName = $row['product_name'];
                        $variationId = $row['variation_id'];
                        $variationName = $row['variation_name'];
                        $quantity = $row['quantity'];
                        $price = $row['price'];
                        // $status = $row['status'];
                        $newOrderCartSession = $row['cart_session'];
                        $paymentModeId = $row['payment_mode_id'];
            
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

                    <!-- CLIENT -->
                    <td class='mx-0' width='15%'> 
                        <div class='py-4 text-secondary'>
                            <?php 
                            
                            $fname = getFirstName($conn, $clientId);
                            $lname = getLastName($conn, $clientId);
                            if($fname && $lname) {
                                $name = $fname . " " . $lname;
                            } else { 

                                if(!$whoWillPay){
                                    $name = "";
                                } else {
                                    $name = getWhoWillPay($conn,$whoWillPay);
                                }
                            }
                            
                            echo ucwords(strtolower($name));
                                
                            ?>
                        </div>
                    </td>
                        

                    <!-- TRANSACTION CODE -->
                    <td class='mx-0' width='20%'> 
                        <div class='py-4 text-secondary'>
                            <?= $transactionCode ?>
                        </div>
                    </td>

                    <!-- MODE OF PAYMENT -->
                    <td class='mx-0' width='15%'> 
                        <div class='py-4 text-secondary'>
                            <?= getModeOfPayment($conn, $paymentModeId) ?>
                        </div>
                    </td>

                    

                    <!-- AMOUNT DUE -->
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

                                    echo "&#36;&nbsp;".$grandTotalFee;
                                }
                        
                                ?>
                                <input type="hidden" value='<?=$subTotalForItems?>'>
                                <input type="hidden" value='<?=$storeFreeShippingMinimum?>'>
                        </div>
                    </td>

                    <!-- VIEW -->
                    <td class='mx-0' width='5%'>
                        <a data-href="../partials/templates/new_order_summary_modal.php?id=<?=$storeId?>&cart=<?=$newOrderCartSession?>" class='border-0 btn_view_new_order' style='cursor:pointer;size:15px;'>
                            <i class="far fa-file-pdf text-gray py-4" style='width:100%;'></i>
                        </a>
                    </td>

                    <!-- ACTION -->
                    <td class='mx-0' width='15%'> 
                        <div class='py-2 text-gray'>
                            <div class="dropdown show">
                                <a class="btn border dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <small>CHOOSE 1</small>    
                                </a>

                                <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                                    <!-- ONCE CLICKED, WILL BE TRANSFERRED TO SHIPPING -->
                                    <a class="dropdown-item btn_confirm_order" href="#" data-cartsession='<?=$newOrderCartSession?>' data-storeid='<?=$storeId?>' data-storename='<?=$storeName?>'><small>CONFIRM ORDER</small></a>
                                    <!-- ONCE CLICKED, WILL BE TRANSFERRED TO ORDER HISTORY -->
                                    <a class="dropdown-item btn_cancel_order" href="#" data-cartsession='<?=$newOrderCartSession?>' data-storeid='<?=$storeId?>'><small>CANCEL ORDER</small></a>
                                </div>
                                <!-- put dropdown with two buttons: SEND MESSAGE, CONFIRM, CANCELL, COMPLETE -->
                            </div>
                        </div>
                    </td>

                </tr>

                
<?php } } } else { {
    echo "fail"; } } ?>