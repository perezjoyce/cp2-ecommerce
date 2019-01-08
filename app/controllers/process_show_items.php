<?php
	require_once '../../config.php';

	$categoryId = $_POST['categoryId'];
	$data = '';

	// query database using variable
	$sql= "SELECT c.name, c.id, c.parent_category_id, i.id AS product_id, i.name, i.price, i.img_path FROM tbl_categories c JOIN tbl_items i ON i.category_id = c.id WHERE c.id = ? ";
	$statement = $conn->prepare($sql);
	$statement->execute([$categoryId]);	

	if($statement->rowCount()){

		while($row = $statement->fetch()){
			$id = $row['id'];
			$name = $row['name'];
			$productId = $row['product_id'];
			$price = $row['price'];
			// $item_img = $row['img_path'];
			$item_img = productprofile($conn,$productId);
			$item_img = BASE_URL ."/".$item_img.".jpg";
?>
			<div class='col-lg-3 col-md-3 px-1 pb-2'>
				<a href='product.php?id=<?=$productId?>'>
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
								<div class='flex-fill' style="display:flex; flex-direction: column; width:81%; align-items:flex-end">  
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
		$data = "No records found!";
	}


	echo $data;

?>