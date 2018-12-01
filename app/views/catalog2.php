<?php include_once "../partials/header.php";?>
<?php require_once "../controllers/connect.php";?>
<?php require_once "../controllers/functions.php";?>


<!-- PAGE CONTENT -->
	<div class="container my-5">

		<div class="row">
			
			<!-- first col -->
			<div class="col-lg-3">
				<!-- <h1 class='mt-3'>Categories</h1> -->

				<?php 

					// DISPLAYING CATEGORY ON TOP OF LIST GROUP
					//@ so undefined index won't appear on screen when  you haven't interacted w/ the page yet(i.e. when page loads)
					$id = @$_GET['id'];
			      
			      	$sql = "SELECT * FROM tbl_categories WHERE id = $id";
			      	$result = mysqli_query($conn,$sql);
	       
			      	$bool = false;
	                
	               	if(!$id || $result === $bool) {

	                	echo "<h1 class='my-5'>Categories</h1>";
	               	} else {
	               		$row = mysqli_fetch_assoc($result);
	                	$name = $row['name']; 
	               		echo "<h1 class='my-5'>$name</h1>";
	               	}


		      		// DISPLAYING ALL AVAILABLE CATEGORIES
		      		$sql = "SELECT * FROM tbl_categories";
		      		$result = mysqli_query($conn,$sql);

		      
			      	if(mysqli_num_rows($result) > 0){
			      		
			      		while($row = mysqli_fetch_assoc($result)){
			      			$name = $row['name'];
			      			$id = $row['id'];

			      				echo 
			      				"<div class='list-group'>
									<a href='catalog2.php?id=$id'class='list-group-item btn-block' onclick='showCategories($id) id=$id'>
										$name
									</a>
			      				</div>";
			      		}
			      	}
		     ?>

		     <!-- PRICE INPUT -->
				<div class='mt-5'>
					<h4 class='mb-2'>PRICE</h4>
					<!-- <input type='number' class='form-control'> -->
				</div>
			
				<div class="input-group mb-3">
				  <div class="input-group-prepend">
				    <label class="input-group-text" for="inputGroupSelect01">&#8369;</label>
				  </div>
				  <select class="custom-select" id="priceOrder" onchange="priceOrder">
				    <option selected>......</option>
				 
				    <option value="lowestToHighest"> Lowest to Highest </option>
				    <option value="highestToLowest"> Highest to Lowest </option>
				  
				  </select>
				</div>
			</div>


			 <!-- END OF LEFT COLUMN -->
			<div class="col-lg-9">

				<div class="row">
				
					<div class="input-group input-group-lg my-5">
					  
					  <input type="text" class="form-control" aria-label="Sizing example input" aria-describedby="inputGroup-sizing-lg" id="search">

					  <div class="input-group-append">
					    <span class="input-group-text" id="inputGroup-sizing-lg">
					    	<i class="fas fa-search"></i>
					    </span>
					  </div>

					</div>
				</div>

	

				<!-- DIPLAYS PRODUCTS -->
				<div class="row" id="products">

   
			        <?php 
						//@ so undefined index won't appear on screen when  you haven't interacted w/ the page yet(i.e. when page loads)
			   		  $id = @$_GET['id'];
								//make sql statement to select all columns in tbl_items
								
								if($id) {
									$sql = "SELECT * FROM tbl_items WHERE category_id = " . $id;	
								} else {
									$sql = "SELECT * FROM tbl_items";
								}
			          
 
			          //get data and assign to result variable
			          $result = mysqli_query($conn,$sql);

			          //check if may laman and if meron saka ka magloop
			          if(mysqli_num_rows($result) > 0){
			            //put result in associative array and assign to row variable
			            while($row = mysqli_fetch_assoc($result)){
			            	$id = $row['id'];
			              	$name = $row['name'];
							$price = $row['price'];
							$item_img = $row['img_path'];
					?>

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
										</div>
									</a>
								</div>
			        <?php } } ?>
			    </div>
		      


			</div>
			<!-- /. RIGHT COLUMN -->


		</div>
		<!-- /.row -->
		
	</div>
	<!-- /.container -->

	
	<?php include_once "../partials/footer.php";?>

	<!-- MODAL -->
<?php include_once "../partials/modal_container.php";?>