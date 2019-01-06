<?php
require_once "connect.php";
require_once "../../config.php";
require_once "functions.php";

    if(isset($_POST['storeId'])){
        $storeId = $_POST['storeId'];
        $columnName = $_POST['columnName'];
        $order = $_POST['order'];

        $sql = "SELECT i.id AS 'product_id', i.name, i.price, SUM(variation_stock) 
                AS 'stocks' FROM tbl_items i JOIN tbl_variations v 
                ON v.product_id=i.id WHERE store_id = ? GROUP BY product_id ORDER BY $columnName $order";
                $statement = $conn->prepare($sql);
                $statement->execute([$storeId]);
                $count = $statement->rowCount();

                if($count) {
                    while($row = $statement->fetch()){
                    $productId = $row['product_id'];
                    $productName = $row['name'];
                    $productPrice = $row['price'];
                    $stocks = $row['stocks'];
                    // $productSubCategoryId = $row['category_id'];
                    // $productBrandId = $row['brand_id'];
                    // $productPrimaryImage = $row['img_path'];
        ?>

                        <tr>
                            <div>
                                <td class='mx-0' width='10%'> 
                                    <a data-url="../partials/templates/view_order_summary_modal.php" data-id='#' class='border-0 btn_view_order_history' style='cursor:pointer;size:15px;'>
                                        <div class='py-3 text-secondary'>
                                            <?= $productId ?>
                                        </div>
                                    </a>
                                </td>
                              
                                <td class='mx-0' width='20%'> 
                                    <a data-url="../partials/templates/view_order_summary_modal.php" data-id='#' class='border-0 btn_view_order_history' style='cursor:pointer;size:15px;'>
                                        <div class='py-3 text-secondary'>
                                            <?= $productName ?>
                                        </div>
                                    </a>
                                </td>
                                
                                
                             
                                <td class='mx-0' width='20%'> 
                                    <a data-url="../partials/templates/view_order_summary_modal.php" data-id='#' class='border-0 btn_view_order_history' style='cursor:pointer;size:15px;'>
                                        <div class='py-3 text-secondary'>
                                            <span>&#8369;&nbsp;</span>
                                            <span><?= $productPrice ?></span>
                                        </div>
                                    </a>
                                </td>

                                
                                <td class='mx-0' width='15%'>
                                    <a data-url="../partials/templates/view_order_summary_modal.php" data-id='#' class='border-0 btn_view_order_history' style='cursor:pointer;size:15px;'>
                                        <div class='py-3 text-gray'>
                                            <div class="dropdown show">
                                                <a class="btn border dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                    <small>QUICK VIEW</small>    
                                                </a>

                                                <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                                                    <a class="dropdown-item" href="#">
                                                        <table class='borderless text-center'>
                                                            <tr>
                                                                <th class='width:50%;'><small class='font-weight-bold'>VARIATION</small></th>
                                                                <th class='width:50%;'><small class='font-weight-bold'>STOCK</small></th>
                                                            </tr>
                                                    
                                                    <?php 
                                                        $sql2 = "SELECT * FROM tbl_variations WHERE product_id =?";
                                                        $statement2 = $conn->prepare($sql2);
                                                        $statement2->execute([$productId]);

                                                        $count2 = $statement2->rowCount();

                                                        if($count2) {
                                                            while($row2 = $statement2->fetch()){
                                                            $variationName = $row2['variation_name'];
                                                            $variationStock = $row2['variation_stock'];
                                                    ?>
                                                    
                                                            <tr>
                                                                <td><small><?= $variationName ?></small></td>
                                                                <td><small><?= $variationStock ?></small></td>
                                                            </tr>
                                                        
                                                    
                                                    <?php } } ?>
                                                        </table>
                                                    </a>
                                                    
                                                    
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                </td>

                       
                                <td class='mx-0' width='20%'> 
                                    <div class="d-flex align-items-center py-3">
                                        <a data-url="../partials/templates/view_order_summary_modal.php" data-id='#' class='btn_view_order_history flex-fill text-center text-secondary' style='cursor:pointer;size:15px;'>
                                        <?=$stocks?>
                                        </a>
                                    </div>
                                    
                                </td>


                     
                                <td class='mx-0' width='15%'> 
                                    <a data-url="../partials/templates/view_order_summary_modal.php" data-id='#' class='border-0 btn_view_order_history' style='cursor:pointer;size:15px;'>
                                        <div class='py-3 text-gray'>
                                            <div class="dropdown show">
                                                <a class="btn border dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                    <small>CHOOSE 1</small>    
                                                </a>
                                                <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">  
                                                    <a class="dropdown-item btn_store_products_view" data-href='<?= BASE_URL ."/app/partials/templates/product_modal.php?id=". $productId?>'>
                                                        <small>VIEW</small>
                                                    </a>
                                                    <a class="dropdown-item" href="store-edit-product.php?id=<?=$storeId?>&productid=<?=$productId?>">
                                                        <small>EDIT</small>
                                                    </a>
                                                    <a class="dropdown-item btn_delete_product" data-productId='<?= $productId ?>' href="#">
                                                        <small>DELETE</small>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                </td>
                            </div>
                        </tr>

                
<?php } } } ?>





