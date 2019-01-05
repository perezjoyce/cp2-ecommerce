<?php
	require_once "connect.php";
	require_once '../sources/pdo/src/PDO.class.php';
	session_start();

	if(isset($_SESSION['id'])) {
		
		if (isset($_POST['email'])) {
			$user_id = $_POST['id'];
			$email = $_POST['email']; 

			if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
				echo "invalidEmail";
			} else {

			$sql = "SELECT * FROM tbl_users WHERE email = ? ";
			$statement = $conn->prepare($sql);
			$statement->execute([$email]);

			$row = $statement->fetch();
			$target_id = $row['id'];

			$count = $statement->rowCount();
					if($count) {
						if($user_id == $target_id) {
							echo "sameEmail";
						} else {
							echo "emailExists";
						}
					} else {
						echo "success";
					} 
			}
		} else {
			echo "process_email.php did not receive variable";
			//var_dump($email); die(); //NULL
		}
	}