<?php require_once "../../config.php";?>
<?php require_once "../controllers/connect.php";?>
<?php require_once "../controllers/functions.php";?>
<?php require_once "../partials/header.php";?>
<?php include_once "../partials/categories.php"; ?>
<?php include_once "../partials/breadcrumbs.php"; ?>

		<!-- PAGE CONTENT -->

		<?php 

		//REDIRECT TO INDEX IF CATEGORY HASN'T BEEN SELECTED YET
		if(!isset($_GET['id']) && !isset($_GET['searchKey'])) {
			echo "<script>window.location.href='".BASE_URL."/app/views/'</script>";
		}
		
		?>

		<div class="container mt-5">
			<div class="row">
				<!-- FIRST COLUMN -->
				<div class="col-lg-2 col-md-2 mb-5">

					<!-- CATEGORY & SUBCATEGORIES -->
					<div class="row">
						<div class="col-12 d-flex flex-column">
					
								<?php
								// DISPLAYING ALL AVAILABLE CATEGORIES
								$id = isset($_GET['id']) ? $_GET['id'] : 1;
								
								$sql = "SELECT * FROM tbl_categories WHERE parent_category_id = ?";
								$statement = $conn->prepare($sql);
								$statement->execute([$id]);
								if ($statement->rowCount() > 0){
							
									while($row = $statement->fetch()){
										$name = $row['name'];
										$id = $row['id'];
								?>

							<a href="#" 
								class='flex-fill btn btn-block purple-link level-2 text-left my-0 py-2 px-0 sub_category_btn' 
								data-id='<?=$id?>'>	
								<?= $name ?>
							</a>

							
								<?php } }  
							
							if(isset($_GET['id'])) {
								$id = $_GET['id'];

								$sql = "SELECT * FROM tbl_categories WHERE id=?";
								$statement = $conn->prepare($sql);
								$statement->execute([$id]);	
								$row = $statement->fetch();
								$parentCategoryId = $row['parent_category_id'];

								if($parentCategoryId !== null) {
							
								$sql ="SELECT bc.*, c.id, c.name, b.brand_name FROM tbl_categories c JOIN tbl_brand_categories bc JOIN tbl_brands b ON bc.category_id=c.id AND bc.brand_id = b.id WHERE category_id = ?";
								$statement = $conn->prepare($sql);
								$statement->execute([$id]);	

								if ($statement->rowCount() > 0){
								
									while($row = $statement->fetch()){
										$brandName = $row['brand_name'];
										$brandCategoryId = $row['id'];
										$brandId = $row['brand_id'];
								?>

							<a class='flex-fill btn btn-block purple-link text-left my-0 py-2 px-0' onclick='showByChildCategoriesAndBrand($brandCategoryId)'>	
								<?= $brandName ?>
							</a>

								<?php } } } } ?>

						</div>
					</div>
					
					 <!-- PASS IDs OF SELECTED BTNS -->
					<input type="hidden" id="selectedBrandId">
					<input type="hidden" id="selectedCategoryId" value="<?= $_GET['id'] ?>">
					<hr class='my-4'>

					<!-- SORT BY POPULARITY AND HIGH-LOW PRICE -->
					<div class='row'>
						<div class='col-12 d-flex flex-column'>
							<div class='flex-fill mb-2'>Sort By </div>
							<div class="flex-fill input-group mb-3">
						
								<select class="custom-select" id="sort_products" onchange="sort_products" data-id="<?= $_GET['id'];?>">
									<option value="1" selected>Popularity</option>
									<option value="2"> Price - low to high </option>
									<option value="3"> Price  - high to low </option>
								</select>
							</div>
						</div>
					</div>
					<hr class='my-4'>
					<!-- /.SORT BY POP -->

					<!-- SORT BY PRICE RANGE -->
					<div class='row'>
						<div class='col-12 d-flex flex-column'>
							<div class='flex-fill mb-2'>Price Range </div>
							<div class="flex-fill">
								<div class="d-flex flex-row">
									<input type="number" style='width:35%'; placeholder='Min' class='py-1 pl-2' id='price_range_min'>
									<div class='pt-2'>&#8212;</div>
									<input type="number" style='width:35%'; placeholder='Max' class='py-1 pl-2' id='price_range_max'>
									<button id='btn_price_range' class='btn border' style='width:20%';><i class="fas fa-caret-right"></i></button>
								</div>
							</div>
						</div>
					</div>
					<hr class='my-4'>
					<!-- /.SORT BY POP -->


					<!-- SORT BY RATING -->
					<div class='row'>
						<div class='col-12 d-flex flex-column'>
							<div class='flex-fill mb-2'>Rating </div>
							<div class="flex-fill input-group mb-3">
						
								<!-- <select class="custom-select" id="sort_products" onchange="sort_products" data-id="<?= $_GET['id'];?>"> -->
								<a class='flex-fill btn btn-block text-left m-0 px-0 pt-1 pb-0 sort_by_rating' data-rating='5'>
									<span class="star">★</span>
									<span class="star">★</span>
									<span class="star">★</span>
									<span class="star">★</span>
									<span class="star">★</span>
								</a>
								
								<a class='flex-fill btn btn-block text-left mx-0 px-0 pb-1  pt-0 sort_by_rating' data-rating='4'>
									<span class="star">★</span>
									<span class="star">★</span>
									<span class="star">★</span>
									<span class="star">★</span>
									<span class="star-gray">★</span>
									& Up
								</a>

								<a class='flex-fill btn btn-block text-left m-0 px-0 py-1 sort_by_rating' data-rating='3'>
									<span class="star">★</span>
									<span class="star">★</span>
									<span class="star">★</span>
									<span class="star-gray">★</span>
									<span class="star-gray">★</span>
									& Up
								</a>

								<a class='flex-fill btn btn-block text-left m-0 px-0 py-1 sort_by_rating' data-rating='2'>
									<span class="star">★</span>
									<span class="star">★</span>
									<span class="star-gray">★</span>
									<span class="star-gray">★</span>
									<span class="star-gray">★</span>
									& Up
								</a>
									
								<a class='flex-fill btn btn-block text-left m-0 px-0 py-1 sort_by_rating' data-rating='1'>
									<span class="star">★</span>
									<span class="star-gray">★</span>
									<span class="star-gray">★</span>
									<span class="star-gray">★</span>
									<span class="star-gray">★</span>
									& Up
								</a>
								
								</select>
							</div>
						</div>
					</div>
					<!-- /.SORT BY RATING -->
				</div>
				<!-- END OF FIRST COLUMN -->

				<div class="col-lg-1 vanish-md vanish-sm"></div>
				
				<!-- RIGHT COLUMN -->
				<div class="col">

					<!-- SEARCH BOX -->
					<!-- <div class="row mx-0">
						<div class="input-group input-group-lg my-5">
							<input type="text" class="form-control" aria-label="Sizing example input" aria-describedby="inputGroup-sizing-lg" id="search">

							<div class="input-group-append">
								<span class="input-group-text" id="inputGroup-sizing-lg">
									<i class="fas fa-search"></i>
								</span>
							</div>

						</div>
					</div> -->
					<!-- /.SEARCH BOX -->

					<!-- PRODUCT DISPLAY -->
					<div class="row" id="products_view">

						<?php

						if(isset($_GET['searchKey'])) {
							$searchkey = $_GET['searchKey'];
							$sql = "SELECT item.name, 
								item.id as productId, 
								item.price,
								item.img_path,
								category.id as id, 
								category.name as catName, 
								brand.brand_name
								 
								FROM tbl_items item
								LEFT JOIN tbl_categories category on item.`category_id`=category.id
								LEFT JOIN tbl_brands as brand on brand.id=item.`brand_id`
								WHERE (item.name like ? ) 
								OR (category.name like ? ) 
								OR (brand.brand_name like ? )";
						

								// var_dump($sql);die();
							$statement = $conn->prepare($sql);
							$statement->execute(["%$searchkey%", "%$searchkey%", "%$searchkey%"]);
							$count = $statement->rowCount();
						
						} else {
								$id = $_GET['id'];
								$sql = "SELECT c.name, c.parent_category_id, c.id, i.id AS 'productId', i.name, i.price, i.img_path FROM tbl_categories c JOIN tbl_items i ON i.category_id = c.id WHERE parent_category_id = ?";	
								$statement = $conn->prepare($sql);
								$statement->execute([$id]);	
						}

							// } else {
							// 	$sql = "SELECT * FROM tbl_items";
							// 	$statement = $conn->prepare($sql);
							// 	$statement->execute();
							// }
			
								if ($statement->rowCount()){
									while($row = $statement->fetch()){
										$productId = $row['productId'];
										$id = $row['id'];
										$name = $row['name'];
										$price = $row['price'];
										$item_img = $row['img_path'];
						?>
						
						<!-- PRODUCT CARDS -->
						<div class='col-lg-3 col-md-3 px-1 pb-2'>
							<a href='product.php?id=<?= $productId ?>'>
								<div class = 'card h-700 border-0'>
									<img class='card-img-top' src='<?= $item_img ?>'>
									<div class='card-body'>
										<div class='font-weight-bold'>
											<?= $name ?>
										</div>
										<div>&#8369; <?= $price ?> </div>

										<!-- WISHLIST BUTTONS -->
										<div class='d-flex flex-column'>
											<?php 
												if(isset($_SESSION['id'])) {
													if (checkIfInWishlist($conn,$id) == 0) {
											?>
											
											<a class='mt-3 btn_add_to_wishlist_view' data-id='<?= $id ?>' role='button'>
												<i class='far fa-heart' style='color:red'></i> 
												<span class='product-wish-count<?= $id ?>'>
													<?= getProductWishlishtCount($conn, $id) == 0 
													? "" 
													: getProductWishlishtCount($conn, $id) ?>
												</span>
											</a>
										
											<?php  } else { ?>

											<a class='mt-3 btn_already_in_wishlist_view' data-id='<?= $id ?>' disabled>
												<i class='fas fa-heart' style='color:red'></i> 
												<span class='product-wish-count<?= $id ?>'>
													<?= getProductWishlishtCount($conn, $id) == 0 
													? "" 
													: getProductWishlishtCount($conn, $id) ?>
												</span>
											</a>

											<?php }  } else { ?>
											
											<!-- IF LOGGED OUT -->
											<a class='mt-3' data-id='<?= $id ?>' disabled>
												<i class='far fa-heart' style='color:gray'></i> 
												<span class='product-wish-count<?= $id ?>'>
													<?= getProductWishlishtCount($conn, $id) == 0 
													? "" 
													: getProductWishlishtCount($conn, $id) ?>
												</span>
											</a>
											
											<?php }  ?>
													
										</div>
										<!-- /WISH LIST BUTTONS -->
									
									</div>
									<!-- /.CARD BODY -->
								</div>
								<!-- /.CARD -->
							</a>
							<!-- /LINK FOR CARD -->
						</div>
						<!-- /PRODUCT CARDS -->
						<?php } } ?>
					
					</div>
					<!-- /.PRODUCT DISPLAY ROW -->
				</div>
				<!-- /. RIGHT COLUMN -->
				
			</div>
			<!-- /.CATALOG.PHP ROW -->
		</div>
		<!-- /.CATALOG.PHP CONTAINER -->
										

	
<?php require_once "../partials/footer.php";?>
<?php require_once "../partials/modal_container.php";?>