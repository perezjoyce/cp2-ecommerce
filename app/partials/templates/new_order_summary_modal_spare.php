<?php 
  require_once '../../../config.php';
  require_once '../../sources/pdo/src/PDO.class.php';
  require_once '../../controllers/functions.php';
  require_once '../../controllers/connect.php';


    $userId = $_SESSION['id']; //userid
    $storeId = $_GET['id'];
    $cartSession = $_GET['cart'];
   
    $storeName = getStoreName ($conn,$userId);
    $storeLogo = getStoreLogo ($conn,$userId);
    $storeAddress = getStoreAddress ($conn,$userId);
    $storeHours = getStoreHours ($conn,$userId);
    $storeFollowers = countFollowers ($conn, $storeId);
    $storeRating = getAverageStoreRating ($conn, $storeId);
    $storeMembershipDate = getMembershipDate($conn, $storeId);
    $storeShippingFee = displayStoreShippingFee($conn,$storeId);
    $storeFreeShippingMinimum = displayStoreFreeShipping($conn,$storeId);
    
?>

<input type="hidden" value='<?=$cartSession?>'>
<input type="hidden" value='<?=$storeId?>'>

<div class="container-fluid" id='confirmation_modal'>
    <div class="row">
        
        <div class="col" style='height:80vh;overflow-y:auto;' id='printThis'>

            <div class="row float-right">
                <button id='close_modal' type="button" class="close mr-3 mt-2" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true" class='font-weight-light text-secondary' style='font-size:20px;'>&times;</span>
                </button>
            </div>

            <div class="container px-5 pb-2 pt-5 mb-4">
                <input type="hidden" value='1' id='variation_id_hidden_modal'>
                <div class="row mb-5 mt-4"> 
                    <div class='col'>
                       <h3>Order Summary</h3>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">            
                        <form>



                                <?php 
                                    $sql = "SELECT * FROM tbl_orders WHERE cart_session = ?";
                                    $statement = $conn->prepare($sql);
                                    $statement->execute([$cartSession]);	
                                    $row = $statement->fetch();
                                    $orderId = $row['id'];
                                    $purchaseDate = $row['purchase_date'];
                                    $purchaseDate = date("M d, Y", strtotime($purchaseDate));

                                    $transactionCode = $row['transaction_code'];
                                    $paymentModeId = $row['payment_mode_id'];
                                    $shippingAddressId = $row['address_id'];
                                    $statusId = $row['status_id'];
                                    $billingAddressId = $row['billing_address_id'];

                                    //SHIPPING INFO
                                    $sql = "SELECT * FROM tbl_addresses WHERE id = ?";
                                    $statement = $conn->prepare($sql);
                                    $statement->execute([$shippingAddressId]);	
                                    $row = $statement->fetch();
                                    $sName = $row['name'];
                                    $sName = ucwords(strtolower( $sName));

                                    $sLandmark = $row['landmark'];
                                    $sLandmark = ucwords(strtolower($sLandmark));

                                    $sStreet = $row['street_bldg_unit'];
                                    $sStreet = ucwords(strtolower($sStreet));

                                    $sRegionId = $row['region_id'];
                                    $sProvId = $row['province_id'];
                                    $sCityId = $row['city_id'];
                                    $sBrgyId = $row['brgy_id'];

                                    //BILLING INFO
                                    $sql = "SELECT * FROM tbl_addresses WHERE id = ?";
                                    $statement = $conn->prepare($sql);
                                    $statement->execute([$billingAddressId]);	
                                    $row = $statement->fetch();
                                    $bName = $row['name'];
                                    $bName = ucwords(strtolower( $sName));

                                    $bLandmark = $row['landmark'];
                                    $bLandmark = ucwords(strtolower($sLandmark));

                                    $bStreet = $row['street_bldg_unit'];
                                    $bStreet = ucwords(strtolower($sStreet));

                                    $bRegionId = $row['region_id'];
                                    $bProvId = $row['province_id'];
                                    $bCityId = $row['city_id'];
                                    $bBrgyId = $row['brgy_id'];
                                ?>

                            <div class="container my-5 px-0">
                                <!-- TRANSACTION CODE -->
                                <div class="row mt-5">
                                    <div class="col-4">
                                            
                                        <div>
                                            <div class='text-secondary'>Transaction Code</div>
                                        </div>
                                        
                                        
                                    </div>
                                    <div class="col">
                                        <h4 class='text-purple font-weight-bold'><?=$transactionCode?></h3>
                                    </div>
                                </div>


                                <!-- TRANSACTION DATE -->
                                <div class="row mt-5">
                                    <div class="col-4">  
                                        <div>
                                            <div class='text-secondary'>Transaction Date</div>
                                        </div>
                                    </div>
                                    <div class="col text-secondary">
                                        <div>
                                            <?=$purchaseDate?>
                                        </div>
                                    </div>
                                </div>

                                <!-- TRANSACTION DATE -->
                                <div class="row mt-5">
                                    <div class="col-4">  
                                        <div>
                                            <div class='text-secondary'>Status</div>
                                        </div>
                                    </div>
                                    <div class="col text-secondary">
                                        <div>
                                            <?= displayOrderStatus($conn, $statusId)?>
                                        </div>
                                    </div>
                                </div>

                                <!-- PAYMENT MODE -->
                                <div class="row mt-5">
                                    <div class="col-4">  
                                        <div>
                                            <div class='text-secondary'>Mode Of Payment</div>
                                        </div>
                                    </div>
                                    <div class="col text-secondary">
                                        <div>
                                            <?=getModeOfPayment($conn, $paymentModeId)?>
                                        </div>
                                    </div>
                                </div>
                                
                                
                                <!-- DELIVERY ADDRESS -->
                                <div class="row mt-5">
                                    <div class="col-12">
                                            
                                        <div>
                                            <div class='text-secondary'>Shipping & Billing Info</div>
                                        </div>
                                        <table class="table table-hover borderless mt-5 text-center">
                                            <thead>
                                                <tr id="table-header" class='text-secondary bg-gray text-center border-bottom'>
                                                    <th style="width:20%;">NATURE</th>
                                                    <th style="width:30%;"> NAME </th>
                                                    <th style="width:50%;"> ADDRESS </th>
                                                </tr>
                                            </thead>
                                                                        
                                            <tbody>
                                                <tr class='text-secondary'>
                                                    <td> 
                                                        Shipping
                                                    </td>
                                                    <td>
                                                        <?=$sName?>
                                                    </td>
                                                    <td>
                                                        
                                                        <?=$sStreet?>,&nbsp;
                                                        <?= getBrgyName($conn, $sBrgyId) ?>,&nbsp;
                                                        <?= getCityName($conn, $sCityId) ?>,&nbsp;
                                                        <?= getProvinceName($conn, $sProvId) ?>,
                                                        <br>
                                                        <?= getRegionName($conn, $sRegionId) ?>&nbsp; -- &nbsp;
                                                        
                                                        <span class='text-gray'>
                                                            <?=$sLandmark?>
                                                        </span>
                                                    
                                                    </td>
                                                </tr>

                                                <tr class='tr-gray text-secondary'>
                                                    <td>
                                                        Billing
                                                    </td>
                                                    <td>
                                                        <?=$bName?>
                                                    </td>
                                                    <td>
                                                        <?= $bStreet?>,&nbsp;
                                                        <?= getBrgyName($conn, $bBrgyId) ?>,&nbsp;
                                                        <?= getCityName($conn, $bCityId) ?>,&nbsp;
                                                        <?= getProvinceName($conn, $bProvId) ?>,
                                                        <br>
                                                        <?= getRegionName($conn, $bRegionId) ?>&nbsp; -- &nbsp;
                                                        
                                                        <span class='text-gray'>
                                                            <?=$bLandmark?>
                                                        </span>
                                                    </td>
                                                </tr>
                                            </tbody>

                                        </table>
                                    </div>
                                </div>
                            </div>

                            <!-- SHIPPING FEE & TOTAL SUMMARY -->
                            <div class="container mb-5 mt-5 px-0">
                                <div class="row">
                                    <div class="col">
                                        <div class='mb-5'>
                                            <div class='text-secondary'>Cart Items</div>
                                        </div>
     
                                        <!-- ITEMS SUMMARY -->
                                        <table class="table table-hover borderless">
                                            
                                            <tr id="table-header" class='text-secondary bg-gray border-bottom'>
                                                <th class='text-center'> ITEM </th>
                                                <th class='text-center'> VARIATION </th>
                                                <th class='text-center'> PRICE </th>
                                                <th class='text-center'> QUANTITY </th>
                                                <th class='text-center'> UNIT PRICE &nbsp;x &nbsp; QUANTITY </th>

                                            </tr>
                                            
                    
                                            <?php 

                                                $sql = "SELECT v.product_id as 'productId', v.variation_stock, v.variation_name, c.variation_id, c.quantity, c.cart_session,p.store_id, p.img_path, p.name, p.price
                                                FROM tbl_carts c 
                                                JOIN tbl_items p 
                                                JOIN tbl_variations v
                                                ON v.product_id = p.id AND c.variation_id=v.id WHERE cart_session= ? AND store_id = ?";
                                                //$result = mysqli_query($conn, $sql);
                                                $statement = $conn->prepare($sql);
                                                $statement->execute([$cartSession,$storeId]);
                                                $count = $statement->rowCount();
                                                $subtotalPrice = 0;

                                                while($row = $statement->fetch()){ 
                                                    // $userId = $row['user_id'];
                                                    $variationStock = $row['variation_stock'];
                                                    $variationId = $row['variation_id'];
                                                    $variationName = $row['variation_name'];
                                                    $productId = $row['productId'];
                                                    $name = $row['name'];
                                                    $price = $row['price'];
                                                    $quantity = $row['quantity'];
                                                    $image = $row['img_path'];  
                                                    $image =  BASE_URL."/". $image . ".jpg";
                                                    $subtotalPrice = $price * $quantity;
                                                    $totalStocksAvailable = getTotalProductStocks ($conn,$productId);
                                            ?>

                                            
                                            <tr class='text-secondary'>
                                                <!-- IMAGE -->
                                                <td> 
                                                    <div class='text-center d-flex justify-content-center' style='justify-content:flex-start;'>
                                                        <div class='pr-2'>
                                                            <a href="product.php?id=<?=$productId?>">
                                                                <img src='<?=$image?>' style='width:50px;height:50px;'>
                                                            </a> 
                                                        </div> 
                                                        <div><?=$name?></div>
                                                    </div>
                                                </td>
                                                
                                                <!-- VARIATION -->
                                                <td> 
                                                    <div class='text-center justify-content-center'>
                                                        <?=$variationName?>
                                                    </div>
                                                </td>
                                                
                                                
                                                <!-- PRICE -->
                                                <td> 
                                                    <div class='text-center justify-content-center'>
                                                        <span>&#8369; </span>
                                                        <span><?=$price?></span>
                                                    </div>
                                                </td>
                                            
                                                <!-- QUANTITY -->
                                                <td> 
                                                    <div class='text-center justify-content-center'>
                                                        <?=$quantity?>
                                                    </div>
                                                </td>

                                                
                                                
                                                
                                                <!-- UNIT PRICE X QUANTITY -->
                                                <td class='text-center justify-content-center'>
                                                    <span>&#8369; </span>
                                                    <span class="subtotal_price<?=$variationId?>"> <?= number_format((float)$subtotalPrice, 2, '.', ',') ?> </span> 
                                                </td>
                        
                                            </tr>
                    
                                            <?php } ?>
                                        

                                            <!-- GRAND TOTAL /SUBTOTAL CART ITEMS -->
                                            <tr class='tr-gray text-secondary font-weight-bold text-center'>
                                                <td colspan='4' class='text-right'>SUBTOTAL (Cart Items)</td>
                                                <td> 
                                                    <span>&#8369;</span>
                                                    <span id='grand_total_price'> 
                                                        <?php
                                                            $cartTotal = displayGrandTotalOfSeller($conn, $cartSession, $storeId);
                                                            $cartTotalDisplay = number_format((float)$cartTotal, 2, '.', ',');
                                                            echo $cartTotalDisplay;
                                                        ?> 
                                                        <!-- <input type="text" value="<?=$cartTotal?>"> -->
                                                    </span> 
                                                </td>
                                            </tr>  

                                        </table>
                                    </div>
                                </div>
                            </div>


                            <!-- SHIPPING AND TOTALS -->
                            <div class="container my-5 px-0">
                                <div class="row">

                                    <div class="col">
                                        <div>
                                            <div class='text-secondary'>Shipping Fee</div>
                                        </div>
                                        <table class="table table-hover borderless mt-5">
                                            
                                            <tr id="table-header" class='text-secondary bg-gray text-center border-bottom'>
                                                <th></th>
                                                <th class='text-left'>STORE</th>
                                                <th> SHIPPING FEE </th>
                                                
                                            </tr>
                                            
                    
                                            

                                            <tr class='text-secondary'>
                                                
                                                <!-- STORE LOGO AND NAME -->
                                                <td></td>
                                                <td> 
                                                    <div class='d-flex flex-row' style='justify-content:flex-start;'>
                                                        <div class='flex pr-2'>
                                                            <a href="store-profile.php?id=<?=$storeId?>">
                                                                <img src='<?php 
                                                                    $logo = getStoreLogo ($conn,$userId);
                                                                    $logo = BASE_URL."/". $logo . ".jpg";
                                                                    echo $logo;
                                                                ?>' style='width:50px;height:50px;' class='circle'> 
                                                            </a>
                                                        </div>   
                                                        <div class='flex-fill'>
                                                            <div class='d-flex flex-column'>
                                                            <div class='pt-2'><?= getStoreName($conn,$userId) ?></div> 
                                                            <div class='text-gray'><?= getStoreAddress ($conn,$userId) ?></div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </td>

                                                
                                                <!-- SHIPPING FEE -->
                                                <td class='text-center'>
                                                    <div class='pt-2'>

                                                        <?php 
                                                            $standardShipping = displayStoreShippingFee($conn,$storeId);
                                                            $freeShippingMinimumx = displayStoreFreeShipping($conn,$storeId, false);
                                                            $freeShippingMinimum = number_format($freeShippingMinimumx,2,'.','');
                                                            
                                                            if($cartTotal >= $freeShippingMinimum ){
                                                                echo "FREE!";
                                                            } else {
                                                                echo $standardShipping;
                                                            }
                                                        ?>

                                                      
                                           
                                                    </div>
                                                </td>
                                            </tr>

                                         

                                            <!-- SHIPPING FEE TOTAL -->
                                            <tr class='tr-gray text-secondary font-weight-bold text-center'>
                                                <td colspan='2' class='text-right'>SUBTOTAL (Shipping Fee)</td>
                                                    <?php 
                                                            $finalShippingFee = "";
                                
                                                            if($cartTotal >= $freeShippingMinimum ){
                                                                $finalShippingFee = "0.00";
                                                            } else {
                                                                $finalShippingFee = $standardShipping;
                                                            }
                                                        ?>

                                                <td>
                                                    <input type="hidden" value='<?=$cartTotal?>'> 
                                                    <input type="hidden" value='<?=$freeShippingMinimum?>'> 
                                                    &#8369;<span id='grand_total_price'> <?= number_format((float)$finalShippingFee, 2, '.', ','); ?> </span> 
                                                </td>
                                            </tr>  

                                        </table>
                                    </div>

                                    <div class="col-1"></div>

                                    <!-- SUPER GRAND TOTAL -->
                                    <div class="col">
                                            <div>
                                                <div class='text-secondary'>Grand Total</div>
                                            </div>
                                            <table class="table table-hover borderless mt-5">
                                                
                                                <tr id="table-header" class='text-secondary bg-gray text-center border-bottom'>
                                                    <th class='text-left'>SUBTOTALS</th>
                                                    <th> AMOUNT </th>
                                                    
                                                </tr>
                                                                            
                                                <!-- CART ITEMS -->
                                                <tr class='text-secondary'>
                                                    <td> 
                                                        Cart Items
                                                    </td>
                                                    <td class='text-center'>
                                                        ₱&nbsp;
                                                        <?= $cartTotalDisplay ?>
                                                    </td>
                                                </tr>

                                                <tr class='text-secondary'>
                                                    <td>
                                                        Shipping Fee
                                                    </td>
                                                    <td class='text-center'>
                                                         &#8369;<span> <?= number_format((float)$finalShippingFee, 2, '.', ','); ?> </span> 
                                                    </td>
                                                </tr>


                                               
                                                <tr class='tr-gray font-weight-bold text-center'>
                                                    <td colspan='1' class='text-right pt-3 text-secondary '>GRAND TOTAL</td>
                                                    <td> 
                                                        <div class='d-flex flex-row justify-content-center'>
                                                            <h3 class='text-purple'>&#8369;&nbsp;</h3>
                                                            <h3 id='grand_total_price' class='text-purple'> 
                                                                <?php 
                                                                    $grandtotal = $finalShippingFee + $cartTotal;
                                                                    $grandtotalx = number_format((float)$grandtotal, 2, '.', ','); 
                                                                    echo $grandtotalx;
                                                                ?>
                                                            </h3>
                                                        </div>
                                                    </td>
                                                </tr>  

                                            </table>
                                    </div>
                                </div>
                            </div>

                                    
                        <!-- CHECKOUT BUTTON -->
                            <div class="container my-5 px-0">
                                <div class="row mt-5">
                                    <div class="col-lg-8 col-md-6 col-sm-12"></div>
                                    <div class="col">
                                        <?php 
                                            $modalLinkClassPrefix = ''; 
                                            if(isset($_SESSION['id'])) {
                                                $modalLinkClassPrefix='-big';
                                            }
                                        ?>
                                        <a class='btn btn-lg btn-block py-3 btn-purple mt-5'  id='btn_print_order_copy'>
                                            Save As PDF&nbsp; 
                                            <i class="fas fa-file-download"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>

                        </form>

                    </div>
                </div>

            </div>
            <!-- /INNER CONTAINER -->
        </div>
        <!-- COLUMN -->

    </div>
    <!-- ROW -->

</div>
<!-- /CONTAINER-FLUID -->
