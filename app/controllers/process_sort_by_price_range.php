<?php
	require_once '../../config.php';

    $minPrice = $_POST['minPrice'];
	$maxPrice = $_POST['maxPrice'];
	$data = "";
	
	$sql = "SELECT * FROM `tbl_items` WHERE price BETWEEN ? AND ? ORDER BY price";
	$statement = $conn->prepare($sql);
	$statement->execute([$minPrice, $maxPrice]);	
	$count = $statement->rowCount();

	if($count){
		while($row = $statement->fetch()){
			$id = $row['id'];
			$price = $row['price'];
			$item_img = productprofile($conn, $id);
			$item_img = BASE_URL . "/". $item_img. ".jpg";
			$name = $row['name'];
	

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
											<div class="full-stars" style="width:<?=getProductRating($conn,$id)?>%"></div>
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
		$data = "Sorry, no items were found to fit your preferred price range at the moment. :(";
	}

	echo $data;

	


?>