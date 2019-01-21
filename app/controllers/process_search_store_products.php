<?php
    require_once '../../config.php';

    $searchkey = $_GET['searchkey'];
    $storeId = $_GET['storeId'];

    $sql = "SELECT * FROM tbl_items WHERE name LIKE ? AND store_id = ?";

	$statement = $conn->prepare($sql);
    $statement->execute(["%$searchkey%", $storeId]);
	$count = $statement->rowCount();
	
    if($count) {
        while($row = $statement->fetch()) {
            $id = $row['id'];
            $name = $row['name'];
            $price = $row['price'];
            $price = number_format((float)$price, 2, '.', '');
            $logo = productprofile($conn,$id);
            $logo = BASE_URL ."/".$logo.".jpg";
           

                echo 

                "<div class='col-lg-3 col-md-4 col-sm-6 p-2'>
                    <input type='hidden' value='$storeId'>
                    <a href='product.php?id=<?= $id ?>'>
                        <div class='card border-0'>
                            <a href='product.php?id=<?= $id ?>'>
                                <img class='card-img-top card-profilepic' src='$logo'>
                                <div class='card-body p-lg-4 p-md-3 p-sm-p5'>
                                    <div>
                                        $name
                                    </div>
                                    <div>&#36; 
                                        $price
                                    </div>

                                    <div class='d-flex flex-row mt-3'>
                                    
                                        <div class='flex-fill' style='cursor:default;'>";
                                            $wishCount = getProductWishlishtCount($conn,$id);
                                            $productRating = getProductRating($conn, $id);
                                            if(isset($_SESSION['id'])) {
                                            if(checkIfInWishlist($conn,$id) == 1 ) { 

                                echo        "<a class='heart-toggler' data-id='<?= $id ?>' role='button' data-enabled='0' style='float:left'>
                                                <span class='wish_heart'>
                                                    <i class='fas fa-heart text-purple' id></i>
                                                </span> 
                                                <span class='product_wish_count'>
                                                    <small>$wishCount</small>
                                                </span>
                                            </a>";

                                        } else { 
                                            
                                echo        "<a class='heart-toggler' data-id='<?= $id ?>' data-enabled='1' style='float:left'>
                                                <span class='wish_heart'>
                                                    <i class='far fa-heart text-purple'></i>
                                                </span> 
                                                <span class='product_wish_count'>
                                                    <small>";
                                            
                                                        if($wishCount == 0){
                                                        echo "";
                                                        } else {
                                                        echo $wishCount;
                                                        }
                                                
                                echo                "</small>
                                                </span>
                                            </a>";

                                            } } else {

                                                if($wishCount >= 1) {
                                echo        "<a class='btn_wishlist_logout_view' data-id='<?= $id ?>' disabled style='cursor:default; float:left'>
                                                <i class='far fa-heart text-purple'></i> 
                                                <span class='product_wish_count'>
                                                    <small$wishCount</small>
                                                </span>
                                            </a>";

                                            } else {

                                echo        "<a class='btn_wishlist_logout_view' data-id='<?= $id ?>' disabled style='cursor:default; float:left'>
                                                <i class='far fa-heart text-gray'></i> 
                                            </a>";

                                            } }
                                echo    "</div>
                                        <div class='flex-fill text-right'>
                                            <div class='ratings'>
                                                <div class='empty-stars'></div>
                                                <div class='full-stars' style='width:$productRating%'></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </a> 
                        </div>
                    </a>
                </div>";

             
        }
    } else {
        echo "fail";
    }
?>
