<?php require_once "../../config.php";?>
<?php require_once BASE_DIR . "/app/partials/header_stripe.php";?>
<?php 

$transactionCode = $_GET['transactionCode'];
$transactionCode = base64_decode($transactionCode);
$cartSession = $_SESSION['cart_session'];
$userId = $_SESSION['id'];

?>

<div class="container-fluid" id='confirmation_modal'>
    <div class="row">

        <div class="col" id='printThis'>

            
            <div class="container px-5 pb-2 pt-5 mb-4 rounded" style="background:white;">
                <input type="hidden" value='1' id='variation_id_hidden_modal'>
                <div class="row mb-5 mt-4"> 
                    <div class='col'>
                       <h3>Your Order Summary</h3>
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
                                    $status = $row['status_id'];
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
                                            <h5 class='text-secondary'>Transaction Code</h5>
                                        </div>
                                        
                                        
                                    </div>
                                    <div class="col">
                                        <h4 class='text-purple font-weight-bold'><?=$transactionCode?></h4>
                                    </div>
                                </div>


                                <!-- TRANSACTION DATE -->
                                <div class="row mt-5">
                                    <div class="col-4">  
                                        <div>
                                            <h5 class='text-secondary'>Transaction Date</h5>
                                        </div>
                                    </div>
                                    <div class="col">
                                        <div>
                                            <?=$purchaseDate?>
                                        </div>
                                    </div>
                                </div>

                                <!-- PAYMENT MODE -->
                                <div class="row mt-5">
                                    <div class="col-4">  
                                        <div>
                                            <h5 class='text-secondary'>Mode Of Payment</h5>
                                        </div>
                                    </div>
                                    <div class="col">
                                        <div>
                                           Credit Card
                                        </div>
                                    </div>
                                </div>
                                
                                
                                <!-- DELIVERY ADDRESS -->
                                <div class="row mt-5">
                                    <div class="col-12">
                                            
                                        <div>
                                            <h5 class='text-secondary'>Shipping & Billing Info</h5>
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
                                                <tr>
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

                                                <tr class='tr-gray'>
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
                                            <h5 class='text-secondary'>Cart Items</h5>
                                        </div>
     
                                        <!-- ITEMS SUMMARY -->
                                        <table class="table table-hover borderless">
                                            
                                            <tr id="table-header" class='text-secondary bg-gray border-bottom'>
                                                <th> ITEM </th>
                                                <th class='text-center'> QUANTITY </th>
                                                <th class='text-center'> UNIT PRICE &nbsp;x &nbsp; QUANTITY </th>

                                            </tr>
                                            
                    
                                            <?php 

                                                $sql = "SELECT v.product_id as 'productId', v.variation_stock, v.variation_name, c.variation_id, c.quantity, c.cart_session, p.img_path, p.name, p.price
                                                FROM tbl_carts c 
                                                JOIN tbl_items p 
                                                JOIN tbl_variations v
                                                ON v.product_id = p.id AND c.variation_id=v.id WHERE cart_session= ?";
                                                //$result = mysqli_query($conn, $sql);
                                                $statement = $conn->prepare($sql);
                                                $statement->execute([$cartSession]);
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
                                                    // $image = $row['img_path'];  
                                                    $image = productprofile($conn,$productId);
                                                    $image = BASE_URL ."/".$image.".jpg";
                                                    $subtotalPrice = $price * $quantity;
                                                    $totalStocksAvailable = getTotalProductStocks ($conn,$productId);
                                            ?>

                                            
                                            <tr>
                                                <!-- IMAGE, NAME AND VARIATION -->
                                                <td> 
                                                    <div class='d-flex flex-row' style='justify-content:flex-start;'>
                                                        <div class='flex pr-2'>
                                                            <a href="product.php?id=<?=$productId?>">
                                                                <img src='<?=$image?>' style='width:50px;height:50px;'>
                                                            </a> 
                                                        </div>   
                                                        <div class='flex-fill'>
                                                            <div class='d-flex flex-column'>
                                                                <?= $name ?>
                                                                <div class='text-gray italics'><?= $variationName ?></div>
                                                                <div class='text-gray italics'><span class="unitPrice<?=$variationId?>"> <?= number_format((float)$price, 2, '.', ',') ?> </span></div>
                                                            </div>
                                                        </div>
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
                                                    <span>&#36;</span>
                                                    <span class="subtotal_price<?=$variationId?>"> <?= number_format((float)$subtotalPrice, 2, '.', ',') ?> </span> 
                                                </td>
                        
                                            </tr>
                    
                                            <?php } ?>
                                        

                                            <!-- GRAND TOTAL /SUBTOTAL CART ITEMS -->
                                            <tr class='tr-gray text-secondary font-weight-bold text-center'>
                                                <td colspan='2' class='text-right'>SUBTOTAL (Cart Items)</td>
                                                <td> 
                                                    <span>&#36;</span>
                                                    <span id='grand_total_price'> 
                                                        <?php
                                                            $cartTotal = displayGrandTotal($conn, $cartSession);
                                                            $cartTotal = number_format((float)$cartTotal, 2, '.', ',');
                                                            echo $cartTotal;
                                                        ?> 
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
                                    <div class="col-6">
                                        <div>
                                            <h5 class='text-secondary'>Shipping Fee</h5>
                                        </div>
                                        <table class="table table-hover borderless mt-5">
                                            
                                            <tr id="table-header" class='text-secondary bg-gray text-center border-bottom'>
                                                <th></th>
                                                <th class='text-left'>STORE</th>
                                                <th> SHIPPING FEE </th>
                                                
                                            </tr>
                                            
                    
                                            <?php 
                                                $sql2 = "SELECT c.cart_session, i.store_id, 
                                                    SUM(i.price * c.quantity) AS 'totalPerStore', s.logo, s.store_address, s.name, s.standard_shipping, s.free_shipping_minimum 
                                                    FROM tbl_items i 
                                                    JOIN tbl_carts c 
                                                    JOIN tbl_variations v 
                                                    JOIN tbl_stores s 
                                                    ON v.product_id=i.id 
                                                    AND c.variation_id=v.id 
                                                    AND i.store_id=s.id 
                                                    WHERE c.cart_session = ? 
                                                    GROUP BY store_id";

                                                    $statement2 = $conn->prepare($sql2);
                                                    $statement2->execute([$cartSession]);
                                                    $combinedShippingFee = 0;
                                                    while($row2 = $statement2->fetch()) {
                                                        $storeId = $row2['store_id'];
                                                        $storeName = $row2['name'];  
                                                        $storeAddress = $row2['store_address'];
                                                        $storeLogo = $row2['logo'];   
                                                        if($storeLogo == null) {
                                                            $storeLogo = DEFAULT_STORE; 
                                                           
                                                        } else {
                                                            $storeLogo = BASE_URL ."/". $storeLogo . ".jpg";
                                                    
                                                        } 
                                                        $totalPerStore = $row2['totalPerStore'];
                                                        $standardShipping = $row2['standard_shipping'];
                                                        $freeShippingMinimum = $row2['free_shipping_minimum'];                                                 
                                            ?>

                                            <tr>
                                                <!-- DELETE STORE AND ITS PRODUCTS BUTTON -->
                                                <td class='pr-0'>
                                                    <!-- <div class='text-left' style='align-self:end;'>
                                                        <a data-productid='<?=$productId?>' data-vname='<?=$variationName?>' data-variationid='<?= $variationId ?>' data-quantity='<?=$quantity?>' role='button' class='btn_delete_item text-gray flex-fill font-weight-light' style='font-size:16px;'>
                                                        &times;
                                                        </a>
                                                    </div> -->
                                                </td>
                                                
                                                <!-- STORE LOGO AND NAME -->
                                                <td> 
                                                    <div class='d-flex flex-row' style='justify-content:flex-start;'>
                                                        <div class='flex pr-2'>
                                                            <a href="store-profile.php?id=<?=$storeId?>">
                                                                <img src='<?=$storeLogo?>' style='width:50px;height:50px;' class='circle'> 
                                                            </a>
                                                        </div>   
                                                        <div class='flex-fill'>
                                                            <div class='d-flex flex-column'>
                                                            <div class='pt-2'><?= $storeName ?></div> 
                                                            <div class='text-gray'><?= $storeAddress ?></div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </td>

                                                
                                                <!-- SHIPPING FEE -->
                                                <td class='text-center'>
                                                    <div class='pt-2'>
                                                        <?php 

                                                            if($freeShippingMinimum != 0 && $totalPerStore >= $freeShippingMinimum) {
                                                                echo "Free Shipping!";
                                                            } else {
                                                                $combinedShippingFee = $combinedShippingFee + $standardShipping;
                                                                $standardShipping = number_format((float)$standardShipping, 2, '.', ',');
                                                                echo "&#36;&nbsp;$standardShipping";
                                                            }
                                                        
                                                        ?>
                                                    </div>
                                                </td>
                                            </tr>

                                            <?php } ?>

                                            <!-- SHIPPING FEE TOTAL -->
                                            <tr class='tr-gray text-secondary font-weight-bold text-center'>
                                                <td colspan='2' class='text-right'>SUBTOTAL (Shipping Fee)</td>
                                                <!-- <td></td> -->
                                                <td>&#36;<span id='grand_total_price'> <?= number_format((float)$combinedShippingFee, 2, '.', ','); ?> </span> </td>
                                            </tr>  

                                        </table>
                                    </div>

                                    <div class="col-1"></div>

                                    <!-- SUPER GRAND TOTAL -->
                                    <div class="col">
                                            <div>
                                                <h5 class='text-secondary'>Grand Total</h5>
                                            </div>
                                            <table class="table table-hover borderless mt-5">
                                                
                                                <tr id="table-header" class='text-secondary bg-gray text-center border-bottom'>
                                                    <th class='text-left'>SUBTOTALS</th>
                                                    <th> AMOUNT </th>
                                                    
                                                </tr>
                                                                            
                                                <!-- CART ITEMS -->
                                                <tr>
                                                    <td> 
                                                        Cart Items
                                                    </td>
                                                    <td class='text-center'>
                                                        <?php 
                                                            $totalForItems = displayGrandTotal($conn, $cartSession); 
                                                            // echo $totalForItems;
                                                            $finalItemsFee = number_format((float)$totalForItems, 2, '.', ',');    
                                                            echo "&#36;&nbsp;$totalForItems";
                                                        // ?>
                                                    </td>
                                                </tr>

                                                <tr>
                                                    <td>
                                                        Shipping Fee(s)
                                                    </td>
                                                    <td class='text-center'>
                                                        <?php
                                                            $finalShippingFee = number_format((float)$combinedShippingFee, 2, '.', ',');    
                                                            echo "&#36;&nbsp;$combinedShippingFee";
                                                            // echo $combinedShippingFee;
                                                        ?>
                                                    </td>
                                                </tr>


                                                <!-- SHIPPING FEE TOTAL -->
                                                <tr class='tr-gray text-secondary font-weight-bold text-center'>
                                                    <td colspan='1' class='text-right pt-3'>GRAND TOTAL</td>
                                                    <!-- <td></td> -->
                                                    <td> 
                                                        <div class='d-flex flex-row justify-content-center'>
                                                            <h3>&#36;&nbsp;</h3>
                                                            <h3 id='grand_total_price'> 
                                                                <?php 
                                                                    $superGrandTotal = $totalForItems + $combinedShippingFee;
                                                                    $grandTotal = number_format((float)$superGrandTotal, 2, '.', ','); 
                                                                    echo $grandTotal;
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
                                    <div class="col-6"></div>
                                    <div class="col">
                                        <?php 
                                            $modalLinkClassPrefix = ''; 
                                            if(isset($_SESSION['id'])) {
                                                $modalLinkClassPrefix='-big';
                                            }
                                        ?>
                                        <a class='btn btn-lg btn-block py-3 btn-purple mt-5'  id='btnPrint'>
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


<?php require_once BASE_DIR . "/app/partials/footer.php";?>
<?php require_once BASE_DIR . "/app/partials/modal_container.php"; ?>
<?php require_once BASE_DIR . "/app/partials/modal_container_big.php"; ?>

<script>
    // RELOAD THE PAGE TO GET NEW CART SESSION ID
    $("#modalContainerBig").on('hidden.bs.modal', function(){
       // ;

       $.get("../controllers/process_unset_session.php", function(data) {
			let response = $.parseJSON(data);
			if(response.message == 'success'){
                window.location.href="index.php?id=<?= $userId ?>";
			}
        });
        
    })

    $("#website_name").on("click",function(){
        $.get("../controllers/process_unset_session.php", function(data) {
			let response = $.parseJSON(data);
			if(response.message == 'success'){
                window.location.href="index.php?id=<?= $userId ?>";
			}
        });
    })
</script>