<?php
	// connect to database
	require_once '../sources/pdo/src/PDO.class.php';
	require_once "connect.php";

	$categoryId = $_POST['categoryId'];

	$sql = "SELECT * FROM tbl_categories WHERE category_id = ? ";
	$statement = $conn->prepare($sql);
    $statement->execute([$categoryId]);
	$data = '';
	if($statement->rowCount()){

		while($statement->fetch()){
			$id = $row['id'];
			$name = $row['name'];
			$price = $row['price'];
	      	$item_img = $row['img_path'];

?>
			<div class='col-lg-4 col-md-6 mb-5'>
					<div class='col-lg-4 col-md-6 mb-5'>
					<a href='product.php?id=$id'>
						<div class = 'card h-700'>
							<img src='$item_img'>
							<div class='card-body'>
								<div class='font-weight-bold'>
									$name
								</div>
								<div>&#36; $row[price]</div>

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
<?php
		}
	} else {
		$data = "No records found!";
	}

	echo $data;

	// var_dump($data); die();


?>