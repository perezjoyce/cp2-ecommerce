<?php
	session_start();

	if(isset($_SESSION['id'])) {
		 
		require_once "connect.php";
		if (isset($_POST['password'])) {

			$id = $_POST['id'];
			$fname = $_POST['fname'];
			$lname = $_POST['lname'];
			$email = $_POST['email'];
			$username = $_POST['username'];
			$password = sha1($_POST['password']);

			$sql = " SELECT * FROM tbl_users WHERE `password` = ? AND id = ? ";

			$statement = $conn->prepare($sql);
			$statement->execute([$password, $id]);
			$count = $statement->rowCount();

			if($count) {
				$sql = " UPDATE tbl_users SET first_name = ?, last_name = ?, email = ?, username = ? WHERE id = ? ";
				$statement = $conn->prepare($sql);
				$result = $statement->execute([$fname, $lname, $email, $username, $id]);

				if($result) {
					//GET LAST ID TO BE ABLE TO BE DIRECTED TO CORRECT PROFILE PAGE WHEN UPDATE IS SUCCESSFULLY DONE.
					//$last_id = mysqli_insert_id($conn);
					echo json_encode(["id" => $id]);
					//header("Location: ../views/profile.php?id=$id");
				} else {
					echo "fail";
				}
			} else {

				echo "incorrectPassword";

			}
		} else {
			echo "process_edit_user.php did not receive variables";
		}
	}

		
