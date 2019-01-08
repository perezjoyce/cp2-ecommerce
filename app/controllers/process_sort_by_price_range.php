<?php
	// connect to database
	// session_start();
	require_once '../sources/pdo/src/PDO.class.php';
	require_once "connect.php";
	require_once "functions.php";
	require_once "../../config.php";

    $minPrice = $_POST['minPrice'];
	$maxPrice = $_POST['maxPrice'];
	
	$sql = "SELECT * FROM `tbl_items` WHERE price BETWEEN 1000 AND 3000 ORDER BY price";
	$statement = $conn->prepare($sql);
	$statement->execute();	
	$count = $statement->rowCount();

	if($count){
		while($row = $statement->fetch()){
			$id = $row['id'];
			$item_img = productprofile($conn, $id);
			$item_img = BASE_URL . "/". $image. ".jpg";
			$name = $row['name'];
	

?>
			<div class='col-lg-3 col-md-3 px-1 pb-2'>
				<a href='product.php?id=<?=$id?>'>
					<div class = 'card h-700 border-0'>
						<img class='card-img-top' src='<?=$item_img?>' style='height:250px;'>
						<div class='card-body pr-1'>
							<div class='font-weight-bold'><?=$name?></div>
							<div>&#8369;<?=$row['price'] ?></div>

								<div class='d-flex flex-row mt-3'>

									<!-- WISHLIST BUTTONS -->
									<div class='' style='cursor:default;'>

										<?php if(checkIfInWishlist($conn,$id) == 1 ) { ?>
										
										<i class='fas fa-heart text-red'></i> 
										<span class='text-gray product-wish-count<?= $id ?>'>
											<small><?= getProductWishlishtCount($conn, $id) ?></small>
										</span>

										<?php } else { 
										
										if(getProductWishlishtCount($conn, $id) == 0) { ?>

										<i class='far fa-heart text-gray'></i> 
										<span class='text-gray product-wish-count<?= $id ?>'>
											<small><?= getProductWishlishtCount($conn, $id) ?></small>
										</span>

										<?php } else { ?>

										<i class='far fa-heart text-red'></i> 
										<span class='text-gray product-wish-count<?= $id ?>'>
											<small><?= getProductWishlishtCount($conn, $id) ?></small>
										</span>

										<?php   } }  ?>
									
									</div>
										

									<!-- AVERAGE STAR RATING -->
									<div class='flex-fill' style="display:flex; flex-direction: column; width:81%; align-items:flex-end pr-3">  
									<div class='stars-outer' 
										data-productrating='<?=getAveProductReview($conn, $id)?>' 
										data-productid='<?=$id?>' 
										id='average_product_stars2<?=$id?>'>
										<span class='stars-inner'></span>
									</div>
									</div>
									<!-- /AVERAGE STAR RATING -->

								</div>
						</div>
					</div>
				</a>
			</div>
	<?php 
		}
	} else {
		$data = "Sorry, no products found!";
	}

	echo $data;

	


?>