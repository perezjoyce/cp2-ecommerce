<?php
	// connect to database
	session_start();
	require_once "connect.php";
    require_once "functions.php";
    // require_once "../assets/js/script.js";

    if(isset($_POST['rating'])){

        $rating = $_POST['rating'];
        $rating = number_format((float)$rating, 2, '.', '');
        
        // var_dump($rating);die();
        $productId = $_POST['productId'];
        $storeId = $_POST['storeId'];

        if($rating < 6 ){

            $sql ="SELECT r.*, u.first_name, u.last_name FROM tbl_ratings r JOIN tbl_users u ON r.user_id = u.id WHERE product_id = ? AND product_rating = ?";
            $statement = $conn->prepare($sql);
            $statement->execute([$productId, $rating]);	

        } else {
        
            $sql ="SELECT r.*, u.first_name, u.last_name FROM tbl_ratings r JOIN tbl_users u ON r.user_id = u.id WHERE product_id = ?";
            $statement = $conn->prepare($sql);
            $statement->execute([$productId]);

        }

        $count = $statement->rowCount();

        $sql = "SELECT * FROM tbl_stores WHERE id = ?";
                    $statement2 = $conn->prepare($sql);
                    $statement2->execute([$storeId]);	
                    $row2 = $statement2->fetch();
                    $storeName = $row2['name'];
                    $storeLogo = $row2['logo'];
                    $storeAddress = $row2['store_address'];
                    $sellerId = $row2['user_id'];
        
        if($count) {
          while($row = $statement->fetch()) {

            $ratingId = $row['id'];
            $clientId = $row['user_id'];
            $clientFname = $row['first_name'];
            $clientFname = ucwords(strtolower($clientFname));
            $clientLname = $row['last_name'];
            $clientLname = ucwords(strtolower($clientLname ));
            $clientRating = $row['product_rating'];
            $clientProductReview = $row['product_review'];
            $clientRatingDate = $row['date_given'];
            $sellerResponse = $row['seller_response'];
            $sellerResponseDate = $row['response_date'];



        ?>
        

        <div class='col-12 pt-4 border-top'>

            <!-- CLIENT RATING -->
            <div class='row mb-1'>

                <?php 
                    if($clientRating != null || $clientRating != "") {
                ?>

                <div class='test-container' data-rating="<?= $clientRating ?>" data-id='<?= $clientId ?>'></div>

                <?php } else { ?>

                <div class="star-gray">★</div>
                <div class="star-gray">★</div>
                <div class="star-gray">★</div>
                <div class="star-gray">★</div>
                <div class="star-gray">★</div>

                <?php } ?>

            </div>

            <!-- CLIENT NAME -->
            <div class='row text-gray mb-2'>
                <span><?=$clientFname." ".$clientLname ?></span>
            </div>


            <!-- VERFICATION BADGE AND DATE -->
            <div class='row mb-4'>
                <img src="../assets/images/verified-gradient.png" alt="verified_user" style='height:10px;width:10px;'>
                <small class='text-purple'>&nbsp;Verified Purchase&nbsp;|
                <?= date("M d, Y", strtotime($clientRatingDate));  ?>
                </small>
            </div>



            <!-- REVIEW -->
                <?php 
                    if($clientRating != null || $clientRating != "") {
                ?>  
            <div class='row mb-2'>  
                <p style='line-height:1.5em;'><?=$clientProductReview?></p>
            </div>
                <?php } else { echo ""; } ?>


            <!-- REVIEW IMAGES -->  
            <div class='row mb-2'>
                <div class="col-12">
                    <div class="row mb-2">

                        <?php 
                            $sql = "SELECT * FROM tbl_rating_images WHERE rating_id =?";
                            $statement2 = $conn->prepare($sql);
                            $statement2->execute([$ratingId]);	
                            $count2 = $statement2->rowCount();

                            if($count2) {
                        
                            while($row2 = $statement2->fetch()){
                            $reviewImageId = $row2['id'];
                            $reviewImageUrl = $row2['url'];
                        ?>

                        <img src="<?=$reviewImageUrl?>" 
                            alt="review_image" style='width:50px;max-height:50px;cursor: zoom-in;' 
                            class='review_thumbnail mr-2' 
                            data-id='<?=$reviewImageId?>' data-clientid='<?=$clientId?>'
                            data-url='<?=$reviewImageUrl?>'>

                        <?php } } ?>

                    </div>
                
                    <div class="row">
                        <div class="col-lg-6 col-md-12 col-sm-12 px-0">
                        <div id='review_iframe<?=$clientId?>'></div>
                        </div>   
                    </div>
                </div>
            </div>



                <!-- SELLER RESPONSE -->
                <?php
                    if($sellerResponse) {
                ?>
            <div class='row my-4'>
                <div class='col-1'></div>
                <div class='col mb-2 pt-4 px-5 seller_response_container' style='background:#eff0f5'>
                    
                    <!-- SELLER DETAILS -->
                    <div class='row flex-row text-gray mb-4'> 
                        <a href='store.php?id=<?=$storeId ?>'></a>
                        <img src='<?=$storeLogo?>' alt="<?=$storeName?>" style='width:30px;max-height:30px;' class='circle'>
                        <div>
                            <div>&nbsp;<?=$storeName?></div>
                            <small class='text-purple'>
                                &nbsp;
                                <?= date("M d, Y", strtotime($sellerResponseDate));  ?>
                            </small>
                        </div>
                    </div>

                    <div class="row">
                        <p style='line-height:1.5em;'><?=$sellerResponse?></p>
                    </div>
                </div>
            </div>
                <?php } else { echo ""; } ?>


        </div>

                     

        
    <?php } } else echo "<div class='row border-top'>
                            <div class='col-12 text-center my-5'>
                                No client gave this rating yet.
                            </div>
                        </div>"; }?>

        
	
    
