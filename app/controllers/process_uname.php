<?php
require_once '../../config.php';

	$username = $_POST['username'];
	$password = sha1($_POST['password']);

	$sql = "SELECT * FROM tbl_users WHERE username = ? ";
	$statement = $conn->prepare($sql);
    $statement->execute([$username]);
	$count = $statement->rowCount();
	
		if($count) {
			echo "userExists";
		} else {
			$sql = "INSERT tbl_users (username) VALUES (?)";
			$statement = $conn->prepare($sql);
    		$statement->execute([$username]);

			if($result) {
				echo "success";
			} else {
				echo "fail";
			}
		}

?>
