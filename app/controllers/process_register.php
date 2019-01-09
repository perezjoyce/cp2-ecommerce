<?php
	require_once '../../config.php';
	
	//CHECK IF DATA WAS FETCHED
	if (isset($_POST['email'])) {

		$fname = $_POST['fname'];
		$lname = $_POST['lname'];
		$email = $_POST['email'];
		$username = $_POST['username'];
		$password = sha1($_POST['password']);

		$sql = "SELECT * FROM tbl_users WHERE username = ? ";
		$statement = $conn->prepare($sql);
		$statement->execute([$username]);
		$count = $statement->rowCount();

		if($count) {
			echo "userExists";
		} else {

			$sql = " INSERT INTO tbl_users (last_name, first_name, email, username, `password`) 
			VALUES (?, ?, ?, ?, ?) ";

			$statement = $conn->prepare($sql);
			$result = $statement->execute([$lname, $fname, $email, $username, $password]);

			if($result) {
				//GET LAST ID TO BE ABLE TO BE DIRECTED TO CORRECT PROFILE PAGE WHEN REGISTRATION IS SUCCESSFULLY DONE.
				$last_id = $conn->lastInsertId();
				echo json_encode(["id" => $last_id]);
			} else {
				echo "fail";
			}

		}
	} else {
		echo "process_register.php did not receive variables";
	}

	
