<?php require_once "../partials/header.php";?>
<?php require_once "../controllers/connect.php";?>
<?php require_once "../controllers/functions.php";?>


		<!-- PAGE CONTENT -->
		<div class="container my-5">
			<div class="row">
			
				<!-- FIRST COLUMN -->
				<div class="col-lg-3">
				
					<?php 
						// DISPLAYING CATEGORY ON TOP OF LIST GROUP
						$id = @$_GET['id'];
						$sql = "SELECT * FROM tbl_categories WHERE id=?";
						$statement = $conn->prepare($sql);
						$statement->execute([$id]);
						
						if ($statement->rowCount() == 0) {
							echo "<div class='my-5'>Related Categories</div>";
						} else {
							$row = $statement->fetch();
							$name = $row['name']; 
							echo "<div class='my-5'>$name</div>";
						}

						// DISPLAYING ALL AVAILABLE CATEGORIES
						$sql = "SELECT * FROM tbl_categories";
						$statement = $conn->prepare($sql);
						$statement->execute();

						if ($statement->rowCount() > 0){
							
							while($row = $statement->fetch()){
								$name = $row['name'];
								$id = $row['id'];

					echo 
					"<div class='list-group'>
						<a href='catalog.php?id=$id'class='list-group-item btn-block' onclick='showCategories($id) id=$id'>
							$name
						</a>
					</div>";
							}
						}
		    		?>

		     		<!-- PRICE INPUT -->
					<div class='mt-5'>
						<div class='mb-2'>Sort By: </div>
					</div>
			
					<div class="input-group mb-3">
						<div class="input-group-prepend">
							<label class="input-group-text" for="inputGroupSelect01">&#8369;</label>
						</div>
						<select class="custom-select" id="priceOrder" onchange="priceOrder">
							<option selected>......</option>
						
							<option value="lowestToHighest"> Price: Low to High </option>
							<option value="highestToLowest"> Price: High to Low </option>
						
						</select>
					</div>

				</div>
				<!-- END OF LEFT COLUMN -->
				
				<!-- RIGHT COLUMN -->
				<div class="col-lg-9">

					<!-- SEARCH BOX -->
					<div class="row mx-0">
						<div class="input-group input-group-lg my-5">
							<input type="text" class="form-control" aria-label="Sizing example input" aria-describedby="inputGroup-sizing-lg" id="search">

							<div class="input-group-append">
								<span class="input-group-text" id="inputGroup-sizing-lg">
									<i class="fas fa-search"></i>
								</span>
							</div>

						</div>
					</div>
					<!-- /.SEARCH BOX -->
			
			

	

					<!-- PRODUCT DISPLAY -->
					<div class="row" id="products">

   
						<?php 
						
						$id = @$_GET['id'];
									
							if ($id) {
								$sql = "SELECT * FROM tbl_items WHERE category_id = ?";	
								$statement = $conn->prepare($sql);
								$statement->execute([$id]);	
							} else {
								$sql = "SELECT * FROM tbl_items";
								$statement = $conn->prepare($sql);
								$statement->execute();
							}
						

						if ($statement->rowCount()){
							while($row = $statement->fetch()){
								$id = $row['id'];
								$name = $row['name'];
								$price = $row['price'];
								$item_img = $row['img_path'];
						?>
						
						<!-- PRODUCT CARDS -->
						<div class='col-lg-4 col-md-6 mb-5'>
							<a href='product.php?id=<?= $id ?>'>
								<div class = 'card h-700'>
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