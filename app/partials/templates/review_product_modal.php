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

    // check if there is already a product review for this
    $sql = "SELECT * FROM tbl_ratings WHERE user_id=? AND product_id=?";
    $statement = $conn->prepare($sql);
    $statement->execute([$userId, $productId]);

    if($statement->rowCount()) {
        $row = $statement->fetch();
        $ratingId = $row['id'];
        $productReview = $row['product_review'];
        $productRatingScore = $row['product_rating'];
        $ratingScoreIsFinal = $row['rating_is_final'];
    } else {
    // insert a record in tbl_ratings, get the id for referencing to tbl_rating_images table

        $sql = "INSERT INTO tbl_rating set user_id=?, product_id=?, response_date=now()";
        $statement = $conn->prepare($sql);
        $statement->execute($userId, $productId);
        
        $sql = "SELECT * FROM tbl_ratings WHERE user_id=? AND product_id=?";
        $statement = $conn->prepare($sql);
        $statement->execute([$userId, $productId]);
        $row = $statement->fetch();
        $ratingId = $row['id'];
        $productReview = $row['product_review'];
        $productRatingScore = $row['product_rating'];
        $ratingScoreIsFinal = $row['rating_is_final'];

    }

?>


<div class="container-fluid" id='confirmation_modal'>
    <div class="row">

        <div class="col" style='height:80vh;overflow-y:auto;' id='printThis'>
            <input type="hidden" value="<?= $ratingId?>" id='rating_id_hidden'>
            <input type="hidden" value="1" id='rating_score_hidden'>
            <div class="row float-right">
                <button id='close_modal' type="button" class="close mr-3 mt-2" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true" class='font-weight-light text-secondary' style='font-size:20px;'>&times;</span>
                </button>
            </div>

            <div class="container px-5 pb-2 pt-5 mb-4">
                <div class="row mb-4 mt-4"> 
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
                                <div class="row mb-5">
                                    <div class="col-lg-3 col-md-5 col-sm-12">
                                        <div class="card" style="width: 100%;">
                                            <img class="card-img-top" src="<?=$image?>" alt="product_image">
                                        </div>
                                    </div>
                                    <div class="col">
                                        <div class="card" style="width:100%;border:0;">
                                            <div class="card-body">
                                                <h5 class="card-title"><?=$productName?></h5>
                                                <p class="card-text"> &#8369;&nbsp;<?=$price?></p>
                                            </div>
                                            <ul class="list-group list-group-flush">
                                                <a href="store.php?id=<?=$storeId?>"></a>
                                                <li class="list-group-item py-3">
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
                                            <!-- <div class="card-body">
                                                <a href="#" class="card-link">Card link</a>
                                                <a href="#" class="card-link">Another link</a>
                                            </div> -->
                                        </div>
                                    </div>
                                
                                </div>

                                <!-- RATING -->
                                <div class="row mb-5">
                                <?php if($ratingScoreIsFinal == 0){ ?>
                                    <div class="col-lg-4">             
                                    <div class='text-secondary'>RATE THIS PRODUCT</div>
                                    </div>
                                    <div class="col">
                                        <div class="rating" id='product_rating_score'>
                                            <input type="radio" id="star5" name="rating" class='product_rating_score' value="5" /><label for="star5">5 stars</label>
                                            <input type="radio" id="star4" name="rating" class='product_rating_score' value="4" /><label for="star4">4 stars</label>
                                            <input type="radio" id="star3" name="rating" class='product_rating_score' value="3" /><label for="star3">3 stars</label>
                                            <input type="radio" id="star2" name="rating" class='product_rating_score' value="2" /><label for="star2">2 stars</label>
                                            <input type="radio" id="star1" name="rating" class='product_rating_score' value="1" /><label for="star1">1 star</label>
                                        </div>
                                    </div>

                                        <?php } else { ?>
                                    <div class="col-lg-4">             
                                        <div class='text-secondary'>YOUR PRODUCT RATING</div>
                                    </div>
                                    <div class="col"> 
                                            
                                        <?    if($ratingScoreIsFinal == 1){
                                                echo
                                                "<div>" .
                                                "<span class='star2x'>★</span>" .
                                                "<span class='star2x-gray'>★</span>" .
                                                "<span class='star2x-gray'>★</span>" .
                                                "<span class='star2x-gray'>★</span>" .
                                                "<span class='star2x-gray'>★</span>" .
                                                "</div>";
                                            }elseif($ratingScoreIsFinal == 2){
                                                echo 
                                                "<div>" .
                                                "<span class='star2x'>★&nbsp;</span>" .
                                                "<span class='star2x'>★&nbsp;</span>" .
                                                "<span class='star2x-gray'>★&nbsp;</span>" .
                                                "<span class='star2x-gray'>★&nbsp;</span>" .
                                                "<span class='star2x-gray'>★</span>" .
                                                "</div>";
                                            }elseif($ratingScoreIsFinal == 3){
                                                echo
                                                "<div>" .
                                                "<span class='star2x'>★&nbsp;</span>" .
                                                "<span class='star2x'>★&nbsp;</span>" .
                                                "<span class='star2x'>★&nbsp;</span>" .
                                                "<span class='star2x-gray'>★&nbsp;</span>" .
                                                "<span class='star2x-gray'>★</span>" .
                                                "</div>";
                                            }elseif($ratingScoreIsFinal == 4){
                                                echo 
                                                "<div>" .
                                                "<span class='star2x'>★&nbsp;</span>" .
                                                "<span class='star2x'>★&nbsp;</span>" .
                                                "<span class='star2x'>★&nbsp;</span>" .
                                                "<span class='star2x'>★&nbsp;</span>" .
                                                "<span class='star2x-gray'>★</span>" .
                                                "</div>";
                                            }else{
                                                echo 
                                                "<div>" .
                                                "<span class='star2x'>★&nbsp;</span>" .
                                                "<span class='star2x'>★&nbsp;</span>" .
                                                "<span class='star2x'>★&nbsp;</span>" .
                                                "<span class='star2x'>★&nbsp;</span>" .
                                                "<span class='star2x'>★</span>" .
                                                "</div>";
                                            }
                                        }
                                            
                                        ?>



                                    </div>
                                </div>

                                <!-- REVIEW -->
                                <div class="row mb-5">
                                    <?php  if(!$productReview){  ?>
                                    <div class="col-lg-12">
                                        <div class='text-secondary'>WRITE A REVIEW</div>
                                    </div>
                                    <div class="col-lg-12">
                                        <textarea class="form-control border-0" id="product_review" style='width:100%;background:#eff0f5;' rows='4'></textarea>  
                                    </div>
                                
                                    <?php } else { ?>
                                    <div class="col-lg-4">
                                        <div class='text-secondary'>YOUR PRODUCT REVIEW</div>
                                    </div>
                                    <div class='col'>
                                            <div><?= $productReview ?></div>
                                    </div>

                                    <?php } ?>
                                </div>

                                <!-- UPLOAD BUTTON -->
                                <div class="row">
                                            <?php
                                                // get review images
                                                $sql2 = "SELECT * FROM tbl_rating_images WHERE rating_id = ? ";
                                                $statement2 = $conn->prepare($sql2);
                                                $statement2->execute([$ratingId]);
                                                $row2 = $statement2->fetch();
                                                $imagesAreFinal = $row2['is_final'];

                                                if($imagesAreFinal == 0) {
                                            ?>
                                    <div class="col-lg-4">
                                        <div class='text-secondary mb-lg-5 mb-md-4 mb-sm-4'>ATTACH PHOTOS</div>
                                        <label for="fileBox" style='border:1 px solid; height: 40px; width: 120px;' id='fileBox__label'>
                                            <div class='btn border'>Choose File</div>
                                            <input type="file" name="reviewPhotos" id="fileBox" style='display:none'>
                                        </label>
                                        <input type='button' id='fileBoxSave' value='Save Photo' data-ratingid='<?= $ratingId ?>' class='btn border bg-light small'>
                                        
                                    </div>  
                                        <?php } else { ?>
                                    <div class="col-lg-4">
                                        <div class='text-secondary'>YOUR ATTACHED PHOTOS</div>
                                    </div>
                                        <?php } ?>
                                

                                    <!-- UPLOADED IMAGES -->
                                    <div class="col">
                                        <div class='d-flex flex-wrap' id='review_images_container'>
                        
                                                <?php 
                                                if($statement2->rowCount()) {
                                                    while($row2 = $statement2->fetch()) {
                                                ?>
                                                    <div class='pr-1'>
                                                        <img class="" src="../../<?=$row2['url']?>" alt="review_image" height='120' width='120'>    
                                                    </div>
                                                <?php
                                                    }
                                                }
                                                ?>                                            
                                        </div> 
                                    </div>      
                                </div>
                                        
     
                                <!-- BUTTONS -->
                                <div class="row mt-5">
                                    <div class="col-lg-3 col-md-6">
                                        <?php 
                                            $modalLinkClassPrefix = ''; 
                                            if(isset($_SESSION['id'])) {
                                                $modalLinkClassPrefix='-big';
                                            }
                                        ?>
                                        <a class='btn btn-lg btn-block py-3 text-gray text-left pl-0 mt-5 font-weight-light vanish-sm'  id='btn_print_order_copy' href="#">
                                            Report Seller&nbsp;
                                            <i class="far fa-flag"></i>
                                        </a>
                                    </div>

                                    <div class="col-lg-5 vanish-md vanish-sm"></div>
                                    <div class="col-lg-4 col-md-6">
                                        <?php 
                                            $modalLinkClassPrefix = ''; 
                                            if(isset($_SESSION['id'])) {
                                                $modalLinkClassPrefix='-big';
                                            }
                                        ?>
                                        <a class='btn btn-lg btn-block py-3 btn-purple mt-5'  id='btn_submit_review' data-productid='<?=$productId?>'>
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
