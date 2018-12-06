<?php

session_start(); 
require_once "connect.php";

if (isset($_POST['username'])) {
	$username = $_POST['username'];
	$password = sha1($_POST['password']);

	$sql = "SELECT * FROM tbl_users WHERE username = ? AND `password` = ? ";
	$statement = $conn->prepare($sql);
	$statement->execute([$username, $password]);
	$count = $statement->rowCount();
	$row = $statement->fetch();
		$id = $row['id'];
		$username = $row['username'];

	$response =[];
	if($count == 1) {
		// SESSION
		$_SESSION['id'] = $id; 
		$cartSession = $_SESSION['cart_session'];
		$sql = " UPDATE tbl_carts SET user_id = ? WHERE cart_session = ? ";
		$statement = $conn->prepare($sql);
		$statement->execute([$id, $cartSession]);
		
		if(isset($_GET['redirectUrl']) && strlen($_GET['redirectUrl'])>0) {
			$response = ['status' => 'redirect', 'redirectUrl' => 'checkout'];
		} else {
			$response = ['status' => 'loggedIn', 'id' => $id];
		}

	} else {
		$response = ['status' => 'loginFailed', 'message' => 'Login Failed'];
	}

} else {
	$response = ['status' => 'noUsernameProvided', 'message' => 'Username not provided.'];
}

echo json_encode($response);