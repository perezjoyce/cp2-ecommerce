<?php 

    session_start(); 
    require_once '../../sources/pdo/src/PDO.class.php';
    require_once '../../controllers/functions.php';

	//set values
	$host = "localhost";
	$db_username = "root";
	$db_password = "";
	$db_name = "db_demoStoreNew";

	$conn = new PDO("mysql:host=$host;dbname=$db_name",$db_username,$db_password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $productId = $_POST['productId'];
    $userId = $_SESSION['id'];
    
?>


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
                       <h3>Review Product</h3>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">            
                        <form>
                            <?php 
                                $sql = "SELECT i.id 
                                        AS 'productId', i.price, i.img_path, i.name 
                                        AS 'productName', s.id 
                                        AS 'storeId', s.name 
                                        AS 'storeName', s.store_address, s.logo 
                                        FROM tbl_items i 
                                        JOIN tbl_stores s 
                                        ON i.store_id=s.id 
                                        WHERE i.id = ?";
                                    $statement = $conn->prepare($sql);
                                    $statement->execute([$productId]);
                                    $row = $statement->fetch();
                                    $productName = $row['productName'];
                                    $price = $row['price'];
                                    $image = $row['img_path'];
                                    $storeId = $row['storeId'];
                                    $storeName = $row['storeName'];
                                    $storeAddress = $row['store_address'];
                                    $storeLogo = $row['logo'];
                            ?>                                

                            <div class="container my-5 px-0">
                                
                                <div class="row mt-5">
                                    <!-- IMAGE -->
                                    <div class="col-5" style='width:100%;'>  
                                        <div>
                                            <img src="<?=$image?>" alt="product_image">
                                        </div>
                                    </div>
                                    <!-- NAME -->
                                    <div class="col">
                                        <div class='d-flex flex-column'>
                                            <div>
                                                <h4><?=$productName?></h4>
                                            </div>
                                            <div>
                                                <h4 class='text-gray'>
                                                    &#8369;&nbsp;
                                                    <?=$price?>
                                                </h4>
                                            </div>
                                            <div class='d-flex align-items-center'>
                                                <div class='pr-3'>
                                                    <img src="<?=$storeLogo?>" alt="<?=$storeName?>" class='circle' style='height:40px;'>
                                                </div>
                                                <div class='d-flex flex-column'>
                                                   
                                                    <span><?=$storeName?></span>
                                                    <span><?=$storeAddress?></span>
                                                </div>
                                            </div>
                                            <!-- STARS -->
                                            <div class='mt-5'>
                                                <div class="rating">
                                                    <input type="radio" id="star5" name="rating" value="5" /><label for="star5" title="Rocks!">5 stars</label>
                                                    <input type="radio" id="star4" name="rating" value="4" /><label for="star4" title="Pretty good">4 stars</label>
                                                    <input type="radio" id="star3" name="rating" value="3" /><label for="star3" title="Meh">3 stars</label>
                                                    <input type="radio" id="star2" name="rating" value="2" /><label for="star2" title="Kinda bad">2 stars</label>
                                                    <input type="radio" id="star1" name="rating" value="1" /><label for="star1" title="Sucks big time">1 star</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>


                                <!-- TRANSACTION DATE -->
                                <div class="row mt-5">
                                    <div class="col-4">  
                                        <div>
                                            <h4 class='text-secondary'>Transaction Date</h4>
                                        </div>
                                    </div>
                                    <div class="col">
                                        <div>
                                            <?=$purchaseDate?>
                                        </div>
                                    </div>
                                </div>

                                <!-- TRANSACTION DATE -->
                                <div class="row mt-5">
                                    <div class="col-4">  
                                        <div>
                                            <h4 class='text-secondary'>Status</h4>
                                        </div>
                                    </div>
                                    <div class="col">
                                        <div>
                                            <?= displayOrderStatus($conn, $statusId)?>
                                        </div>
                                    </div>
                                </div>

                                <!-- PAYMENT MODE -->
                                <div class="row mt-5">
                                    <div class="col-4">  
                                        <div>
                                            <h4 class='text-secondary'>Mode Of Payment</h4>
                                        </div>
                                    </div>
                                    <div class="col">
                                        <div>
                                            <?=getModeOfPayment($conn, $paymentModeId)?>
                                        </div>
                                    </div>
                                </div>
                                
                                
                                <!-- DELIVERY ADDRESS -->
                                <div class="row mt-5">
                                    <div class="col-12">
                                            
                                        <div>
                                            <h4 class='text-secondary'>Shipping & Billing Info</h4>
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
                                            <h4 class='text-secondary'>Cart Items</h4>
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
                                                    $image = $row['img_path'];  
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
                                                    <span>&#8369; </span>
                                                    <span class="subtotal_price<?=$variationId?>"> <?= number_format((float)$subtotalPrice, 2, '.', ',') ?> </span> 
                                                </td>
                        
                                            </tr>
                    
                                            <?php } ?>
                                        

                                            <!-- GRAND TOTAL /SUBTOTAL CART ITEMS -->
                                            <tr class='tr-gray text-secondary font-weight-bold text-center'>
                                                <td colspan='2' class='text-right'>SUBTOTAL (Cart Items)</td>
                                                <td> 
                                                    <span>&#8369;</span>
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
                                            <h4 class='text-secondary'>Shipping Fee</h4>
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
                                                            <a href="store.php?id=<?=$storeId?>">
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
                                                                echo "₱&nbsp;$standardShipping";
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
                                                <td> &#8369;<span id='grand_total_price'> <?= number_format((float)$combinedShippingFee, 2, '.', ','); ?> </span> </td>
                                            </tr>  

                                        </table>
                                    </div>

                                    <div class="col-1"></div>

                                    <!-- SUPER GRAND TOTAL -->
                                    <div class="col">
                                            <div>
                                                <h4 class='text-secondary'>Grand Total</h4>
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
                                                            echo "₱&nbsp;$totalForItems";
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
                                                            echo "₱&nbsp;$combinedShippingFee";
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
                                                            <h3>&#8369;&nbsp;</h3>
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
