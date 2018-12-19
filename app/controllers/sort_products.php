<?php
	// connect to database
	session_start();
	require_once "connect.php";
	require_once "../controllers/functions.php";

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
		$brandJoin = " AND i.brandId=?";
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
	      	$item_img = $row['img_path'];
?>
			<div class='col-lg-3 col-md-3 px-1 pb-2'>
				<a href='product.php?id=<?=$id?>'>
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