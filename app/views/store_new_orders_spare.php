<?php require_once "../../config.php";?>
<?php require_once "../controllers/connect.php";?>
<?php require_once "../controllers/functions.php";?>
<?php require_once "../partials/store_header.php";?>

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
    $storeFreeShippingMinimum = displayStoreFreeShipping($conn,$storeId, false);
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
                            <h4>New Orders</h4>
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
                            WHERE c.status_id = ? and store_id = ? GROUP BY o.cart_session";
                                $statement = $conn->prepare($sql);
                                $statement->execute([1,$storeId]);
                                $count = $statement->rowCount();
            
                            if($count) {
                    ?>

                    <div class="row">
                        <div class="col-12 px-2 mb-0">
                            
                            <table class="table borderless text-center bg-gray mb-0">
                                <tr class='py-0'>
                                
                                    <td width='10%'>Time Ago</td>
                                    <td width='15%'>Client</td>
                                    <!-- <td width='15%'>Transaction Code</td> -->
                                    <td width='10%'>Product Id</td>
                                    <td width='10%'>Variation</td>
                                    <td width='10%'>Price</td>
                                    <td width='10%'>Quantity</td>
                                    <td width='15%'>Amount</td>
                                    <td width='10%'>View</td>
                                    <td width='10%'>Action</td>

                                    
                                </tr> 
                            </table>
                               
                        </div>
                    </div>
                    <div class="row">
                        <div class="col px-2">
                            <div class="container px-0" style='background:white;height:600px;overflow-y:auto;font-size:12px;'>
                                <table class="table table-hover borderless text-center">
                                    
                                        <?php 
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
                                        <td class='mx-0' width='10%'>
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
                                            

                                        <!-- PRODUCT ID & NAME -->
                                        <td class='mx-0' width='10%'> 
                                            <div class="d-flex flex-row justify-content-center py-4">
                                                <div>
                                                    <?= $productId ?>
                                                </div>
                                                <a data-toggle="tooltip" title="<?= $productName ?>" data-original-title="#">
                                                    &nbsp;<i class="far fa-question-circle text-gray"></i>
                                                </a>
                                            </div>
                                        </td>

                                        <!-- VARIATION NAME -->
                                        <td class='mx-0' width='10%'> 
                                            <div class='py-4 text-secondary'>
                                                <?= $variationName ?>
                                            </div>
                                        </td>

                                        <!-- PRICE -->
                                        <td class='mx-0' width='10%'> 
                                            <div class='py-4 text-secondary'>
                                                &#8369;&nbsp;
                                                <?= number_format((float)$price, 2, '.', ','); ?>
                                            </div>
                                        </td>

                                            <!-- QUANTITY -->
                                            <td class='mx-0' width='10%'> 
                                            <div class='py-4 text-secondary'>
                                                <?= $quantity ?>
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

                                                        echo "&#8369;&nbsp;".$grandTotalFee;
                                                    }
                                            
                                                    ?>
                                                    <input type="hidden" value='<?=$subTotalForItems?>'>
                                                    <input type="hidden" value='<?=$storeFreeShippingMinimum?>'>
                                            </div>
                                        </td>

                                        <!-- VIEW -->
                                        <td class='mx-0' width='10%'>
                                            <a data-href="../partials/templates/new_order_summary_modal.php?id=<?=$storeId?>&cart=<?=$newOrderCartSession?>" class='border-0 btn_view_new_order' style='cursor:pointer;size:15px;'>
                                                <i class="far fa-file-pdf text-gray py-4" style='width:100%;'></i>
                                            </a>
                                        </td>

                                        <!-- ACTION -->
                                        <td class='mx-0' width='10%'> 
                                            <div class='py-2 text-gray'>
                                                <div class="dropdown show">
                                                    <a class="btn border dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                        <small>CHOOSE 1</small>    
                                                    </a>

                                                    <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                                                        <!-- ONCE CLICKED, BUTTON WILL BE CHANGED -->
                                                        <?php if($paymentModeId == 2) { ?>
                                                        <a class="dropdown-item" href="#"><small>MESSAGE CLIENT</small></a>
                                                        <?php } else { echo ""; } ?>
                                                        <!-- ONCE CLICKED, WILL BE TRANSFERRED TO SHIPPING -->
                                                        <a class="dropdown-item" href="#"><small>CONFIRM ORDER</small></a>
                                                        <!-- ONCE CLICKED, WILL BE TRANSFERRED TO ORDER HISTORY -->
                                                        <a class="dropdown-item btn_cancel_order" href="#" data-cartsession='<?=$newOrderCartSession?>' data-storeid='<?=$storeId?>'><small>CANCEL ORDER</small></a>
                                                    </div>
                                                    <!-- put dropdown with two buttons: SEND MESSAGE, CONFIRM, CANCELL, COMPLETE -->
                                                </div>
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

    <!-- IF USER IS LOGGED IN AND USER IS NOT THE SELLER -->
    <?php if(isset($_SESSION['id']) && !$isSeller){ include '../partials/message_box.php'; } ?>

<?php require_once "../partials/footer.php";?>
<?php require_once "../partials/modal_container.php";?>
<?php require_once "../partials/modal_container_big.php"; ?>

  