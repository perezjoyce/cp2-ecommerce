<?php
	require_once '../../config.php';

	$value = $_POST['value'];
	$categoryId = $_POST['categoryId'];
	$brandId = $_POST['brandId'];
	$categoryId = (int)$categoryId;
	$data = '';

	$sql = "SELECT * FROM tbl_categories WHERE id=?";
	$statement = $conn->prepare($sql);
	$statement->execute([$categoryId]);	
	$row = $statement->fetch();
	$parentCategoryId = $row['parent_category_id'];

	if($brandId) {
		$brandJoin = " AND i.brand_id=?";
	} else {
		$brandJoin = "";
	}

	//IF NULL, THEN CATEGORY IS A PARENT CATEGORY
	if($parentCategoryId === null) { // rating
		if ($value === "1") {
			$sql = "SELECT c.*, i.*, AVG(product_rating) 
			FROM tbl_ratings 
			JOIN tbl_items i 
			JOIN tbl_categories c ON product_id =i.id 
			AND i.category_id=c.id 
			WHERE parent_category_id = ? 
			$brandJoin
			GROUP BY product_id ORDER BY AVG(product_rating)";
			
		} elseif ($value === '2') { // lowest price
			$sql = "SELECT i.*, c.parent_category_id 
			FROM tbl_items i 
			JOIN tbl_categories c ON i.category_id=c.id 
			WHERE parent_category_id = ? 
			$brandJoin
			ORDER BY price";
			
		} else { // highest price
			$sql = "SELECT i.*, c.parent_category_id 
			FROM tbl_items i 
			JOIN tbl_categories c ON i.category_id=c.id 
			WHERE parent_category_id = ? 
			$brandJoin
			ORDER BY price DESC";
			
		}
	} else {
		if ($value === "1") {
			$sql = "SELECT c.*, i.*, AVG(product_rating) 
			FROM tbl_ratings 
			JOIN tbl_items i 
			JOIN tbl_categories c ON product_id =i.id 
			AND i.category_id=c.id 
			WHERE category_id = ? 
			$brandJoin
			GROUP BY product_id 
			ORDER BY AVG(product_rating)";
			
		} elseif ($value === '2') {
			$sql = "SELECT i.*, c.parent_category_id 
			FROM tbl_items i 
			JOIN tbl_categories c ON i.category_id=c.id 
			WHERE category_id = ? 
			$brandJoin
			ORDER BY price";
			
		} else {
			$sql = "SELECT i.*, c.parent_category_id 
			FROM tbl_items i 
			JOIN tbl_categories c ON i.category_id=c.id 
			WHERE category_id = ? 
			$brandJoin
			ORDER BY price DESC";		
		}
	}

	$statement = $conn->prepare($sql);
	$param = [$categoryId];
	if($brandId) {
		$param[] = $brandId;
	}
	$statement->execute($param); 
	if($statement->rowCount()) {
		while($row = $statement->fetch()){
			$name = $row['name'];
			$id = $row['id'];
			$price = $row['price'];
			  // $item_img = $row['img_path'];
			$item_img = productprofile($conn,$id);
			$item_img = BASE_URL ."/".$item_img.".jpg";
?>
			<div class='col-lg-3 col-md-4 col-sm-6 px-1 pb-1'>
				<a href='product.php?id=<?=$id?>'>
					<div class = 'card h-700 border-0'>
						<img class='card-img-top card-profilepic-catalog' src='<?=$item_img?>'>
						<div class='card-body pr-lg-1 pr-md-1'>
							<div class='font-weight-bold'><?=$name?></div>
							<div>&#36;<?=$row['price'] ?></div>

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
		$data = "Sorry, no items were found to fit your chosen criteria at the moment. :(";
	}

	echo $data;

	


?>