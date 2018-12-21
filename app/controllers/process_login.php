<?php

session_start(); 
require_once "connect.php";

if (isset($_POST['username_email'])) {
	$username_email = $_POST['username_email'];
	$password = sha1($_POST['password']);

	if (filter_var($username_email, FILTER_VALIDATE_EMAIL)) {
		$sql = "SELECT * FROM tbl_users WHERE email = ? AND `password` = ? ";
		$statement = $conn->prepare($sql);
		$statement->execute([$username_email, $password]);

	} else {
		$sql = "SELECT * FROM tbl_users WHERE username = ? AND `password` = ? ";
		$statement = $conn->prepare($sql);
		$statement->execute([$username_email, $password]);
	}

		$count = $statement->rowCount();
		$row = $statement->fetch();
			$id = $row['id'];
			$userType = $row['userType'];

		$response =[];

		if($count == 1) {

			$_SESSION['id'] = $id; 
			$_SESSION['userType'] = $userType;
			$cartSession = $_SESSION['cart_session'];
			
			$sql = " UPDATE tbl_carts SET user_id = ? WHERE cart_session = ? ";
			$statement = $conn->prepare($sql);
			$statement->execute([$id, $cartSession]);

			// update last login
			date_default_timezone_set('Asia/Manila');
			$date = date('Y-m-d H:i:s');

			$sql = "UPDATE tbl_users SET last_login = ? WHERE id = ? ";
			$statement = $conn->prepare($sql);
			$statement->execute([$date,$id]);
				
			if(isset($_GET['redirectUrl']) && strlen($_GET['redirectUrl'])>0) {
				$response = ['status' => 'redirect', 'redirectUrl' => 'checkout'];
			} else {
				if($userType == 'admin') {
					$response = ['status' => 'adminLogIn', 'id' => $id];
				} else {
					$response = ['status' => 'loggedIn', 'id' => $id];
				}
				
			} 
		
		
		} else {
			$response = ['status' => 'loginFailed', 'message' => 'Please use valid login credentials.'];
		}

} else {
	$response = ['status' => 'noUsernameProvided', 'message' => 'Please provide email or username.'];
}

echo json_encode($response);