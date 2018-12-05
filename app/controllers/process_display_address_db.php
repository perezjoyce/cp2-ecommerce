<?php

	require_once "connect.php";
	session_start();

	if(isset($_SESSION['id'])) {

		$_SESSION['id'];
		
		if(isset($_POST['regionId'])){
			$regionId = $_POST['regionId'];

			//CHECK IF IT ALREADY EXISTS
			// $sql = " SELECT * FROM tbl_addresses WHERE region_id = $regionId AND `user_id` = $userId ";
			// $result = mysqli_query($conn,$sql);
			// $count = mysqli_num_rows($result);

			// if($count) {
			// 	// INSERT REGION INTO database
			// 	$sql = " UPDATE tbl_addresses SET region_id=$regionId, user_id=$userId";
			// 	$result = mysqli_query($conn,$sql);
			// } else {
			// 	$sql = " INSERT INTO tbl_addresses (region_id, `user_id`) VALUES ($regionId, $userId) ";
			// 	$result = mysqli_query($conn,$sql);
			// }

			// GET value of choosen region
			$sql = " SELECT * FROM tbl_regions WHERE id = $regionId";
			$result = mysqli_query($conn,$sql);
			$row = mysqli_fetch_assoc($result);
			$regCode = $row['regCode'];
			
			// SELECT values to return
			$data = "";
			$sql = " SELECT * FROM tbl_provinces WHERE regCode = $regCode ";
			$result = mysqli_query($conn, $sql);

			while($row = mysqli_fetch_assoc($result)){ 
				$province = $row['provDesc'];
				$provinceId = $row['id'];
					
				$data .= "<option id='province-option' value='$provinceId'>$province</option>";

			}

			echo $data;

		} 
		
		if (isset($_POST['provinceId'])) {
			$provinceId = $_POST['provinceId'];
			// $regionId = $_POST['regionId'];

			//CHECK IF IT ALREADY EXISTS
			// $sql = " SELECT * FROM tbl_addresses WHERE province_id = $provinceId AND `user_id` = $userId AND region_id = $regionId ";
			// $result = mysqli_query($conn,$sql);
			// $count = mysqli_num_rows($result);

			// if(!$count) {
			// 	$sql = " INSERT INTO tbl_addresses (province_id) VALUES ($provinceId) WHERE `user_id` = $userId AND region_id = $regionId ";
			// 	$result = mysqli_query($conn,$sql);
			// }

			$sql = " SELECT * FROM tbl_provinces WHERE id = $provinceId ";
			$result = mysqli_query($conn,$sql);
			$row = mysqli_fetch_assoc($result);
			$provCode = $row['provCode'];

			$data = "";
			$sql = " SELECT * FROM tbl_cities WHERE provCode = $provCode ";
			$result = mysqli_query($conn, $sql);

			while($row = mysqli_fetch_assoc($result)){ 
				$cityMun = $row['citymunDesc'];
				$cityMunId = $row['id'];
				
				$data .= "<option id='cityMun-option' value='$cityMunId'>$cityMun</option>";

			}

			echo $data;
		} 

		if (isset($_POST['cityMunId'])) {
			$cityMunId = $_POST['cityMunId'];
			// $provinceId = $_POST['provinceId'];

			//CHECK IF IT ALREADY EXISTS
			// $sql = " SELECT * FROM tbl_addresses WHERE city_id = $cityMunId AND `user_id` = $userId AND province_id = $provinceId ";
			// $result = mysqli_query($conn,$sql);
			// $count = mysqli_num_rows($result);

			// if(!$count) {
			// 	$sql = " INSERT INTO tbl_addresses (city_id) VALUES ($cityMunId) WHERE `user_id` = $userId AND province_id = $provinceId ";
			// 	$result = mysqli_query($conn,$sql);
			// }

			$sql = " SELECT * FROM tbl_cities WHERE id = $cityMunId ";
			$result = mysqli_query($conn,$sql);
			$row = mysqli_fetch_assoc($result);
			$cityMunCode = $row['citymunCode'];

			$data = "";
			$sql = " SELECT * FROM tbl_barangays WHERE citymunCode = $cityMunCode ";
			$result = mysqli_query($conn, $sql);

			while($row = mysqli_fetch_assoc($result)){ 
				$brgy = $row['brgyDesc'];
				$brgyId = $row['id'];
				
				$data .= "<option id='brgy-option' value='$brgyId'>$brgy</option>";

			}

			echo $data;
		} 

		// if (isset($_POST['brgyId'])) {
		// 	$brgyId = $_POST['brgyId'];
		// 	// $cityMunId = $_POST['cityMunId'];

		// 	//CHECK IF IT ALREADY EXISTS
		// 	$sql = " SELECT * FROM tbl_addresses WHERE brgy_id = $brgyId AND `user_id` = $userId AND city_id = $cityMunId ";
		// 	$result = mysqli_query($conn,$sql);
		// 	$count = mysqli_num_rows($result);

		// 	if(!$count) {
		// 		$sql = " INSERT INTO tbl_addresses (brgy_id) VALUES ($brgyId) WHERE `user_id` = $userId AND city_id = $cityMunId ";
		// 		$result = mysqli_query($conn,$sql);
		// 	}

		
		// } 

		
	}
			// fetch first then when button is clicked insert them in a separate file