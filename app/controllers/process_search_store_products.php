<?php
    require_once '../sources/pdo/src/PDO.class.php';
    require_once "connect.php";
    require_once "functions.php";
    require_once "../../config.php";

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
                                <img class='card-img-top' src='$logo'>
                                <div class='card-body pr-0'>
                                    <div>
                                        $name
                                    </div>
                                    <div>&#8369; 
                                        $price
                                    </div>

                                    <div class='d-flex flex-row mt-3'>
                                    
                                    <div class='flex-fill' style='cursor:default;'>";

                                         if(checkIfInWishlist($conn,$id) == 1 ) { 

                            echo        "<i class='fas fa-heart text-red'></i> 
                                        <span class='text-gray product-wish-count$id'>
                                            <small>";
                                                echo getProductWishlishtCount($conn, $id)
                                       . "</small>
                                        </span>";

                                       } else { 
                                        
                                        if(getProductWishlishtCount($conn, $id) == 0) {

                            echo        "<i class='far fa-heart text-gray'></i> 
                                        <span class='text-gray product-wish-count$id'>
                                            <small>";
                                             echo   getProductWishlishtCount($conn, $id)
                                        . "</small>
                                        </span>";

                                         } else { 

                            echo       "<i class='far fa-heart text-red'></i> 
                                        <span class='text-gray product-wish-count$id'>
                                            <small>";
                                            echo    getProductWishlishtCount($conn, $id)
                                           .  "</small>
                                        </span>";

                                        } }  
                            echo        "</div>
                                        <!-- AVERAGE STAR RATING -->
                                        <div class='flex-fill' style='display:flex; flex-direction: column; width:81%; align-items:flex-end'>  
                                            <div class='stars-outer' 
                                                data-productrating='";
                                        echo   getAveProductReview($conn, $id) ."'";

                            echo                "data-productid='$id' 
                                                id='average_product_stars2$id'>
                                                <span class='stars-inner'></span>
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
