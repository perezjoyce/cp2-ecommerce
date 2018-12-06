<?php

	require_once "connect.php";


	if (isset($_POST['email'])) {
		$email = $_POST['email'];
	
	// ADD $user = filter_var($_POST['user'], FILTER_SANITIZE_STRING);
	// $isValid = filter_var($user, FILTER_VALIDATE_REGEXP, array('options' => array('regexp' => "/^[a-zA-Z0-9]+$/")));

	// if ($isValid) {
	//     // do something
	// }

		if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
		 	echo "invalidEmail";
		} else {

		  $sql = "SELECT * FROM tbl_users WHERE email = ? ";
		  $statement = $conn->prepare($sql);
		  $statement->execute([$email]);
		  $count = $statement->rowCount();
				if($count) {
					echo "emailExists";
				} else {
					echo "success";
				} 
		}
	} else {
		echo "process_email.php did not receive variable";
		//var_dump($email); die(); //NULL
	}