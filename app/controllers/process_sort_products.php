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

			$sql = "SELECT COUNT(variation_id) 
			AS 'popularity', c.cart_session,v.product_id 
			AS 'id' ,i.name,i.price, i.category_id, i.brand_id, pi.url, pi.is_primary 
			FROM tbl_carts c 
			JOIN tbl_items i 
			JOIN tbl_variations v 
			JOIN tbl_product_images pi 
			ON c.variation_id=v.id 
			AND v.product_id=i.id 
			AND pi.product_id=i.id 
			WHERE is_primary = 1 
			AND category_id = ? 
			$brandJoin
			GROUP BY v.product_id 
			ORDER BY COUNT(variation_id) 
			DESC" ; 
			
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
			<div class='col-lg-3 col-md-4 col-sm-6 px-1 pb-2'>
				<a href='product.php?id=<?=$id?>'>
					<div class = 'card h-700 border-0'>
						<img class='card-img-top card-profilepic-catalog' src='<?=$item_img?>'>
						<div class='card-body p-lg-4 p-md-3 p-sm-p5'>
							<div class='font-weight-bold'><?=$name?></div>
							<div>&#36;<?=$row['price'] ?></div>

								
								<div class='d-flex flex-row mt-3'>
									
									<div class='flex-fill' style='cursor:default;'>

										<?php 
											$wishCount = getProductWishlishtCount($conn,$id);
											if(isset($_SESSION['id'])) {
												if (checkIfInWishlist($conn,$id)) {
										?>
											<a class='heart-toggler' data-id='<?=$id?>' role='button' data-enabled="0" style='float:left'>
											<span class='wish_heart'><i class='fas fa-heart text-purple'></i></span>
											<span class='product_wish_count'>
												<small>
												<?= $wishCount ?>
												</small>
											</span>
											</a>
								
										<?php  } else { ?>

											<a class='heart-toggler' data-id='<?=$id?>' data-enabled="1" style='float:left'>
											<span class='wish_heart'><i class='far fa-heart text-purple'></i></span> 
											<span class='product_wish_count'>
												<small>
												<?php
												if($wishCount == 0){
													echo "";
												} else {
													echo $wishCount;
												}
												?>
												</small>
											</span>
											</a>

										<!-- IF LOGGED OUT -->
										<?php }  } else { 
										
											if($wishCount >= 1) {
										?>
										
											<a class='btn_wishlist_logout_view' data-id='<?=$id?>' disabled style='cursor:default; float:left'>
											<i class='far fa-heart text-purple'></i> 
											<span class='product_wish_count'>
												<small>
												<?= $wishCount ?>
												</small>
											</span>
											</a>
										
										<?php } else { ?>
											<a class='btn_wishlist_logout_view' data-id='<?=$id?>' disabled style='cursor:default; float:left'>
											<i class='far fa-heart text-gray'></i> 
											</a>
											
										<?php } } ?>
									</div>

									<!-- AVERAGE STAR RATING -->
									<div class='flex-fill text-right'>
										<div class="ratings">
											<div class="empty-stars"></div>
											<div class="full-stars" style="width:<?=getProductRating($conn,$productId)?>%"></div>
										</div>
									</div>
								
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