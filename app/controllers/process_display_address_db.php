<?php
	require_once '../../config.php';

	if(isset($_SESSION['id'])) {

		$_SESSION['id'];
		
		if(isset($_POST['regionId'])){
			$regionId = $_POST['regionId'];

			// GET value of choosen region
			$sql = " SELECT * FROM tbl_regions WHERE id = ? ";
			$statement = $conn->prepare($sql);
			$statement->execute([$regionId]);
			$row = $statement->fetch();
			$regCode = $row['regCode'];
			
			// SELECT values to return
			$data = "";
			$sql = " SELECT * FROM tbl_provinces WHERE regCode = ? ";
			$statement = $conn->prepare($sql);
			$statement->execute([$regCode]);

			while($row = $statement->fetch()){ 
				$province = $row['provDesc'];
				$provinceId = $row['id'];
					
				$data .= "<option id='province-option' value='$provinceId'>$province</option>";

			}

			echo $data;

		} 
		
		if (isset($_POST['provinceId'])) {
			$provinceId = $_POST['provinceId'];
			
			$sql = " SELECT * FROM tbl_provinces WHERE id = ? ";
			$statement = $conn->prepare($sql);
			$statement->execute([$provinceId]);
			$row = $statement->fetch();
			$provCode = $row['provCode'];

			$data = "";
			$sql = " SELECT * FROM tbl_cities WHERE provCode = ? ";
			$statement = $conn->prepare($sql);
			$statement->execute([$provCode]);

			while($row = $statement->fetch()){ 
				$cityMun = $row['citymunDesc'];
				$cityMunId = $row['id'];
				
				$data .= "<option id='cityMun-option' value='$cityMunId'>$cityMun</option>";

			}

			echo $data;
		} 

		if (isset($_POST['cityMunId'])) {
			$cityMunId = $_POST['cityMunId'];
			
			$sql = " SELECT * FROM tbl_cities WHERE id = ? ";
			$statement = $conn->prepare($sql);
			$statement->execute([$cityMunId]);
			$row = $statement->fetch();
			$cityMunCode = $row['citymunCode'];

			$data = "";
			$sql = " SELECT * FROM tbl_barangays WHERE citymunCode = ? ";
			$statement = $conn->prepare($sql);
			$statement->execute([$cityMunCode]);

			while($row = $statement->fetch()){ 
				$brgy = $row['brgyDesc'];
				$brgyId = $row['id'];
				
				$data .= "<option id='brgy-option' value='$brgyId'>$brgy</option>";

			}

			echo $data;
		} 
		
	}
			