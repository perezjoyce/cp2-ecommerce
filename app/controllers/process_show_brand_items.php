<?php
	session_start();
	require_once "connect.php";
	require_once "../controllers/functions.php";

	$categoryId = $_POST['categoryId'];
	$brandId = $_POST['brandId'];
	$data = '';


	// query database using variable
	$sql= "SELECT c.name, c.id, c.parent_category_id, i.id AS product_id, i.name, i.price, i.img_path 
		FROM tbl_categories c 
		JOIN tbl_items i ON i.category_id = c.id 
		WHERE c.id = ? AND i.brand_id=? ";
	$statement = $conn->prepare($sql);
	$statement->execute([$categoryId, $brandId]);	

	if($statement->rowCount()){

		while($row = $statement->fetch()){
			$id = $row['id'];
			$name = $row['name'];
			$productId = $row['product_id'];
			$price = $row['price'];
	      	$item_img = $row['img_path'];
?>
			<div class='col-lg-3 col-md-3 px-1 pb-2'>
				<a href='product.php?id=<?=$productId?>'>
					<div class = 'card h-700 border-0'>
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
								?>		
							</div>
							<!-- /WISH LIST BUTTONS -->
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