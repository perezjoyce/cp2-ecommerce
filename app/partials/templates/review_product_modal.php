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
                                <div class="row">
                                    <div class="col-5">
                                        <div class="card" style="width: 100%;">
                                            <img class="card-img-top" src="<?=$image?>" alt="product_image">
                                            <div class="card-body">
                                                <h5 class="card-title"><?=$productName?></h5>
                                                <p class="card-text"> &#8369;&nbsp;<?=$price?></p>
                                            </div>
                                            <ul class="list-group list-group-flush">
                                                <li class="list-group-item">
                                                    <div class='d-flex align-items-center'>
                                                        <div class='pr-3'>
                                                            <img src="<?=$storeLogo?>" alt="<?=$storeName?>" class='circle' style='height:40px;'>
                                                        </div>
                                                        <div class='d-flex flex-column'>
                                                        
                                                            <span><?=$storeName?></span>
                                                            <span><?=$storeAddress?></span>
                                                        </div>
                                                    </div>
                                                </li>
                                            </ul>
                                            <div class="card-body">
                                                <a href="#" class="card-link">Card link</a>
                                                <a href="#" class="card-link">Another link</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>

                            <div class="container my-5 px-0">
                                <div class="row mt-5">
                                    <!-- IMAGE -->
                                    <div class="col-5">  
                                        <div>
                                            <img src="<?=$image?>" alt="product_image">
                                        </div>
                                    </div>
                                    <!-- NAME -->
                                    <div class="col">
                                        <div class='d-flex flex-column'>
                                            <div>
                                                <h3 class='text-secondary'><?=$productName?></h3>
                                            </div>
                                            <div>
                                                <h3 class='text-gray'>
                                                    &#8369;&nbsp;
                                                    <?=$price?>
                                                </h3>
                                            </div>
                                            <div class='d-flex align-items-center mt-5'>
                                                <div class='pr-3'>
                                                    <img src="<?=$storeLogo?>" alt="<?=$storeName?>" class='circle' style='height:40px;'>
                                                </div>
                                                <div class='d-flex flex-column'>
                                                   
                                                    <span><?=$storeName?></span>
                                                    <span><?=$storeAddress?></span>
                                                </div>
                                            </div>
                                            <!-- STARS -->
                                            <div class='mt-5'>Rate this product:</div>
                                            <div>
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
                                    <div class="col-6">  
                                        <div>
                                            <h4 class='text-secondary'>Review</h4>
                                        </div>
                                    </div>
                                    <div class="col">
                                        
                                    </div>
                                </div>

                            </div>

                          

                            
                                    
                        <!-- CHECKOUT BUTTON -->
                            <div class="container my-5 px-0">
                                <div class="row mt-5">
                                    <div class="col">
                                        <?php 
                                            $modalLinkClassPrefix = ''; 
                                            if(isset($_SESSION['id'])) {
                                                $modalLinkClassPrefix='-big';
                                            }
                                        ?>
                                        <a class='btn btn-lg btn-block py-3 border-0 text-gray mt-5'  id='btn_print_order_copy'>
                                            Report Seller&nbsp; 
                                            <i class="far fa-flag"></i>
                                        </a>
                                    </div>

                                    <div class="col-4"></div>
                                    <div class="col">
                                        <?php 
                                            $modalLinkClassPrefix = ''; 
                                            if(isset($_SESSION['id'])) {
                                                $modalLinkClassPrefix='-big';
                                            }
                                        ?>
                                        <a class='btn btn-lg btn-block py-3 btn-purple mt-5'  id='btn_print_order_copy'>
                                            Submit&nbsp; 
                                            <i class="far fa-paper-plane"></i>
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
