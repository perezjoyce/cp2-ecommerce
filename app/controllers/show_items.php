<?php
	// connect to database
	require_once "connect.php";

	// receive value and assign to variable
	$categoryId = $_POST['categoryId'];

	// query database using variable
	$sql= "SELECT * FROM tbl_items WHERE category_id = '$categoryId'";

	//get all results and assign to variable
	$result = mysqli_query($conn, $sql);

	$data = '';
	// loop if resuls is >=1
	$result = mysqli_query($conn,$sql);
	if(mysqli_num_rows($result) > 0){

		while($row = mysqli_fetch_assoc($result)){
			$name = $row['name'];
			$id = $row['id'];
			$price = $row['price'];
	      	$item_img = $row['img_path'];
?>
			  <div class='col-lg-4 col-md-6 mb-5'>
				<a href='product.php?id=<?=$id?>'>
					<div class = 'card h-700'>
						<img class='card-img-top' src='<?=$item_img?>'>
						<div class='card-body'>
							<div class='font-weight-bold'><?=$name?></div>
							<div>&#8369;<?=$row['price'] ?></div>

							<!-- WISHLIST BUTTONS -->
							<div class='d-flex flex-column'>
								<?php
								if(isset($_SESSION['id'])) {
									if (checkIfInWishlist($conn,$id) == 0) {
							
										echo	"<a class='mt-3 btn_add_to_wishlist_view' data-id='".$id."' role='button'>
													<i class='far fa-heart' style='color:red'></i> 
													<span class='product-wish-count$id'>
													" . (getProductWishlishtCount($conn, $id) == 0 
													? ""
													: getProductWishlishtCount($conn, $id)) ."
													</span>
												</a>";
							
								} else {

									echo	"<a class='mt-3 btn_already_in_wishlist_view' data-id='$id' disabled>
												<i class='fas fa-heart' style='color:red'></i> 
												<span class='product-wish-count$id'>
												" . (getProductWishlishtCount($conn, $id) == 0 
												? "" 
												: getProductWishlishtCount($conn, $id) ) . "
												</span>
											</a>";

								}  } else { 
									echo 	"<a class='mt-3' data-id='$id' disabled>
												<i class='far fa-heart' style='color:gray'></i> 
												<span class='product-wish-count$id'>"
												. (getProductWishlishtCount($conn, $id) == 0 
												? "" 
												: getProductWishlishtCount($conn, $id)) . " 
												</span>
											</a>";
								}
							echo "		
							</div>
							<!-- /WISH LIST BUTTONS -->
						</div>
					</div>
				</a>
			</div>";
		}
	} else {
		$data = "No records found!";
	}

	echo $data;

	


?>