<?php 

    require_once '../../../config.php';
    // date_default_timezone_set('Asia/Manila');

    require_once BASE_DIR . '/app/sources/pdo/src/PDO.class.php';
    require_once '../../controllers/functions.php';
    require_once '../../controllers/connect.php';
    // session_start(); 
    // require_once "../../controllers/connect.php";
    // require_once "../../controllers/functions.php";


    $cartSession = $_SESSION['cart_session'];
    
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
        
            if($count) {
?>


<div class="container-fluid">
    <div class="row">

        <div class="col-lg-3 ml-0 py-0 px-0 my-0 ml-0 d-none d-lg-block d-xl-block">
            <div id='login_image'></div>
            <!-- <div id='login_ad'>
                <h1>fdsfsd</h1>
            </div> -->
        </div>

        <div class="col" style='height:80vh;overflow-y:auto;'>

            <div class="row float-right">
                <button id='close_modal' type="button" class="close mr-3 mt-2" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true" class='font-weight-light text-secondary' style='font-size:20px;'>&times;</span>
                </button>
            </div>

            <div class="container px-5 pb-2 pt-5 mb-4">
                <div class="row mb-5 mt-4"> 
                    <div class='col'>
                       <h3>Your Shopping Cart Details</h3>
                    </div>
                </div>

                <input type="hidden" value='1' id='variation_id_hidden_modal'>
                <div class="row">
                    <div class="col">            
                        <form action="../controllers/process_add_to_cart.php" method="POST" id="form_cart">
                            <div class='mb-5'>
                                <h4 class='text-secondary'>Cart Items</h4>
                            </div>
     
                            <!-- ITEMS SUMMARY -->
                            <table class="table table-hover borderless">
                                
                                <tr class='text-secondary bg-gray border-bottom'>
                                    <th></th>
                                    <th> ITEM </th>
                                    <th> UNIT PRICE </th>
                                    <th class='text-center'> QUANTITY </th>
                                    <th class='text-center'> UNIT PRICE &nbsp;x &nbsp; QUANTITY </th>

                                </tr>
                                
        
                                <?php 
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
                                    <!-- DELETE BUTTON -->
                                    <td>
                                        <div class='text-left' style='align-self:end;'>
                                            <a data-productid='<?=$productId?>' data-vname='<?=$variationName?>' data-variationid='<?= $variationId ?>' data-quantity='<?=$quantity?>' role='button' class='btn_delete_item text-gray flex-fill font-weight-light' style='font-size:16px;'>
                                            &times;
                                            </a>
                                        </div>
                                    </td>
                                    
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
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    
                                    <!-- PRICE -->
                                    <td>&#8369; <span class="unitPrice<?=$variationId?>"> <?= number_format((float)$price, 2, '.', ',') ?> </span> </td>
                                    
                                    <!-- QUANTITY -->
                                    <td> 
                                        <div class='d-flex flex-column'>
                                            <div class='flex-fill'>
                                                <div class="input-group justify-content-center">
                                                    <div class="input-group-prepend">
                                                        <button class="btn border btn_minus modal_btn" type="button" onclick="passVariationIdToSubtract(<?=$variationId?>)">&#8212;</button>
                                                    </div>
                                                    <input class='itemQuantity text-center' id='variation_quantity<?=$variationId?>'
                                                        type="number" 
                                                        style='width:35%;' 
                                                        value="<?=$quantity?>"
                                                        data-productid="<?= $productId ?>"
                                                        min="1" 
                                                        max="<?= $variationStock ?>" >
                                                    <div class="input-group-append">
                                                        <button class="btn border btn_plus_modal" type="button" onclick="passVariationIdToAdd(<?=$variationId?>)">&#65291;</a>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class='flex-fill'>
                                                <small class='d-flex flex-row justify-content-center'>
                                                    <!-- STOCK AVAILABILITY -->
                                                    <span class='variation_display<?=$variationId?>' id='variation_stock'>
                                                    <?= $variationStock ?>
                                                    </span>
                                                    
                                                    <!-- GRAMMAR -->
                                                    <?php
                                                        if($variationStock == 1) {
                                                    ?>
                                                    <span class='variation_display<?=$variationId?>'>&nbsp;piece</span>
                                                    
                                                    <?php }else{ ?>
                                                
                                                    <span class='variation_display<?=$variationId?>'>&nbsp;pieces available</span>
                                                    <?php } ?>
                                                </small>
                                            </div>

                                        </div>
                                    </td>
                                    
                                    
                                    <!-- UNIT PRICE X QUANTITY -->
                                    <td class='text-center'>
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
                                                $cartTotal = displayGrandTotal($conn, $cartSession);

                                                $cartTotal = number_format((float)$cartTotal, 2, '.', ',');
                                                echo $cartTotal;
                                            ?> 
                                         </span> 
                                    </td>
                                </tr>  

                            </table>
                            
                            <div style='margin-top:60px;'></div>

                            <!-- SHIPPING FEE SUMMARY -->
                            <div class="container my-5 px-0">
                                <div class="row">
                                    <div class="col-6">
                                        <div>
                                            <h4 class='text-secondary'>Shipping Fee</h4>
                                        </div>
                                        <table class="table table-hover borderless mt-5">
                                            
                                            <tr class='text-secondary bg-gray text-center border-bottom'>
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
                                                        $storeLogo = BASE_URL ."/".$storeLogo.".jpg";
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
                                                
                                                <tr class='text-secondary bg-gray text-center border-bottom'>
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
                                                                    $_SESSION['total_amount'] = $superGrandTotal;
                                                                    $grandTotal = number_format((float)$superGrandTotal, 2, '.', ','); 
                                                                    echo $grandTotal;
                                                                ?>
                                                            </h3>
                                                        </div>
                                                    </td>
                                                </tr>  

                                            </table>
                                   
                                        
                                        <!-- CHECKOUT BUTTON -->
                                        <div class="row">
                                            <div class="col">
                                                <?php 
                                                    $modalLinkClassPrefix = ''; 
                                                    if(isset($_SESSION['id'])) {
                                                        $modalLinkClassPrefix='-big';
                                                    }
                                                ?>
                                                <a class='btn btn-lg btn-block py-3 btn-purple modal-link<?= $modalLinkClassPrefix ?> mt-5' data-grandtotal='<?=$superGrandTotal?>'  data-url='../partials/templates/shipping_info_modal.php' id='btn_cart'>Check Out</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </form>

                    </div>
                </div>

                <?php }  else { ?>
                
                <div class="row">
                    <div class='col-12 mb-5 text-center'>
                        <img src="http://www.aimilayurveda.com/catalog/view/theme/aimil/assets//images/empty.png" alt="empty_cart">
                        <div> Your shopping cart is empty</div>
                    </div>
                </div>

                <?php } ?>
            </div>
            <!-- /INNER CONTAINER -->
        </div>
        <!-- COLUMN -->

    </div>
    <!-- ROW -->

</div>
<!-- /CONTAINER-FLUID -->


<?php require_once "../modal_container.php"; ?>
<?php require_once "../modal_container_big.php"; ?>

