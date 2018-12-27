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

    $cartSession = $_SESSION['cart_session'];
    
    $sql = "SELECT v.product_id as 'productId', v.variation_stock, v.variation_name, c.variation_id, c.quantity, c.cart_session, p.img_path, p.name, p.price
            FROM tbl_carts c 
            JOIN tbl_items p 
            JOIN tbl_variations v
            ON v.product_id = p.id AND c.variation_id=v.id WHERE cart_session= ?";
            //$result = mysqli_query($conn, $sql);
            $statement = $conn->prepare($sql);
            $statement->execute([$cartSession]);
?>


<div class="container-fluid" style='overflow-y:scroll;'>
    <div class="row">

        <div class="col-lg-3 ml-0 py-0 px-0 my-0 ml-0 d-none d-lg-block d-xl-block">
            <div id='login_image'></div>
            <!-- <div id='login_ad'>
                <h1>fdsfsd</h1>
            </div> -->
        </div>

        <div class="col">

            <div class="row float-right">
                <button id='close_modal' type="button" class="close mr-1" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="container px-5 pb-2 pt-5 mb-4">
                <div class="row mb-5 mt-4"> 
                    <div class='col'>
                       <h4>Your Shopping Cart Details</h4>
                    </div>
                </div>

                <input type="hidden" value='1' id='variation_id_hidden_modal'>
               <div class="row">
                    <div class="col">
                        
                        <form action="../controllers/process_add_to_cart.php" method="POST" id="form_cart">
                    
                            <?php
                            $count = $statement->rowCount();
                            $subtotalPrice = 0;
                        
                            if($count) {
                            ?>

                                
                                
                                <table class="table table-hover borderless">
                                    <thead class="thead-purple">
                                        <tr id="table-header pb-4">
                                            <th></th>
                                            <th> Item </th>
                                            <th> Unit Price </th>
                                            <th> Quantity </th>
                                            <th> Subtotal Price </th>
                                            
                            
                                        </tr>
                                    </thead>
            
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
                                            $image = $row['img_path'];  
                                            $subtotalPrice = $price * $quantity;
                                            $totalStocksAvailable = getTotalProductStocks ($conn,$productId);
                                    ?>


                                    <tr>
                                        <td>
                                            <div class='flex-fill text-right' style='align-self:end;'>
                                                <a data-productid='<?=$productId?>' data-vname='<?=$variationName?>' data-variationid='<?= $variationId ?>' data-quantity='<?=$quantity?>' role='button' class='btn_delete_item text-gray flex-fill font-weight-light' style='font-size:16px;'>
                                                &times;
                                                </a>
                                            </div>
                                        </td>
        
                                        <td> 
                                            <div class='d-flex flex-row' style='justify-content:flex-start;width:100%;'>
                                                <div class='flex pr-2'>
                                                    <img src='<?=$image?>' style='width:50px;height:50px;'> 
                                                </div>   
                                                <div class='flex-fill'>
                                                    <div class='d-flex flex-column'>
                                                        <?= $name ?>
                                                        <div class='text-gray italics'><?= $variationName ?></div>
                                                        <div class='text-gray'>&#8369;&nbsp;<?= number_format((float)$price, 2, '.', '') ?></div>
                                                </div>
                                                
                                            
                                            </div>
                                        </td>
                                        <td>&#8369; <span class="unitPrice<?=$variationId?>"> <?= number_format((float)$price, 2, '.', '') ?> </span> </td>
                                        <td> 
                                            <div class="d-flex flex-row mb-3">
                                                
                                                <!-- INPUT FIELD -->
                                                <span class='flex-fill'>
                                                    <div class="input-group" id=''>
                                                        <div class="input-group-prepend">
                                                            <button class="btn border btn_minus modal_btn" type="button" onclick="passVariationIdToSubtract(<?=$variationId?>)">&#8212;</button>
                                                        </div>
                                                        <input class='itemQuantity text-center' id='variation_quantity<?=$variationId?>'
                                                            type="number" 
                                                            style='width:35%;' 
                                                            value="<?=$quantity?>"
                                                            data-productid="<?= $id ?>"
                                                            min="1" 
                                                            max="<?= $variationStock ?>" >
                                                        <div class="input-group-append">
                                                            <button class="btn border btn_plus_modal" type="button" onclick="passVariationIdToAdd(<?=$variationId?>)">&#65291;</a>
                                                        </div>
                                                    </div>
                                                </span>
                
                                                <div class='flex-grow-1 pt-2'>
                                                    <!-- STOCK AVAILABILITY -->
                                                    <span class='variation_display<?=$variationId?>' id='variation_stock'>
                                                    <?= $variationStock ?>
                                                    </span>
                                                    
                                                    <!-- GRAMMAR -->
                                                    <?php
                                                        if($variationStock == 1) {
                                                    ?>
                                                    <span class='variation_display<?=$variationId?>'>&nbsp;piece available</span>
                                                    
                                                    <?php }else{ ?>
                                                
                                                    <span class='variation_display<?=$variationId?>'>&nbsp;pieces available</span>
                                                    <?php } ?>
                                                </div>
                                            </div>
                                        </td>
                                        <td>&#8369; <span class="subtotal_price<?=$variationId?>"> <?= number_format((float)$subtotalPrice, 2, '.', '') ?> </span> </td>
                
                                    </tr>
            
                                    <?php } ?>
                                </table>
        
    
                                <table class='table table-bordered mb-5'>

                                    <tr>
                                        <th>Grand Total Price</th>
                                        <td colspan='6'> &#8369;<span id='grand_total_price'> <?= displayGrandTotal($conn, $cartSession) ?> </span> </td>
                                    </tr>

                                </table>

                                <div class='d-flex justify-content-center mb-5'>

                                    <a class='modal-link' data-url='../partials/templates/shipping_info_modal.php' id='btn_cart'>
                                        <i class='fas fa-3x fa-arrow-circle-right'></i>
                                    </a>

                                </div>

                             <?php }  else { ?>

                                <div class='container mb-5 text-center'>
                                    <img src="http://www.aimilayurveda.com/catalog/view/theme/aimil/assets//images/empty.png" alt="empty_cart">
                                    <div> Your shopping cart is empty</div>
                                </div>

                                <?php }?>

                        </form>

                    </div>
                </div>

                <div class="row my-5">
                    <div class="col">
                        <a class='btn btn-lg btn-gradient' href="https://perezjoyce.github.io/portfolio/" target="_blank">View My Resume</a>
                    </div>
                </div>

            </div>

        </div>

    </div>

    
</div>

