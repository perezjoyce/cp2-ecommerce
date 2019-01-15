<?php
require_once '../../config.php';
// session_start(); 
// require_once '../sources/pdo/src/PDO.class.php';
// require_once "connect.php";

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
			$status = $row['status'];

			//if deactivated, update status to active
			if($status == 0){
				$sql = " UPDATE tbl_carts SET status = 1 WHERE id = ? ";
				$statement = $conn->prepare($sql);
				$statement->execute([$id]);
			}

		$response =[];

		if($count == 1) {

			$_SESSION['id'] = $id; 
			$_SESSION['userType'] = $userType;
			$cartSession = $_SESSION['cart_session'];
			
			$sql = " UPDATE tbl_carts SET user_id = ? WHERE cart_session = ? ";
			$statement = $conn->prepare($sql);
			$statement->execute([$id, $cartSession]);

				
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