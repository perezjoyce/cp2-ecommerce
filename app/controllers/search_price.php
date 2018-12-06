<?php
	// connect to database
	session_start();
	require_once "connect.php";
	require_once "../controllers/functions.php";

	$value = $_POST['value'];
	$data = '';

	if ($value == 'lowestToHighest') {
		$sql = "SELECT * FROM tbl_items ORDER BY price"; 

	} elseif ($value == 'highestToLowest') {
		$sql = "SELECT * FROM tbl_items ORDER BY price DESC"; 
	} else {
		$sql = "SELECT * FROM tbl_items"; 
	}

	$statement = $conn->prepare($sql);
    $statement->execute();
	if($statement->rowCount()){

		while($row = $statement->fetch()){
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