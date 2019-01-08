<?php

	require_once '../../config.php';
	require_once BASE_DIR . '/app/sources/pdo/src/PDO.class.php';

	//set values
	$host = "db4free.net";
	$db_username = "japerez";
	$db_password = "16b786e8";
	$db_name = "db_demostorenew";

	$conn = new PDO("mysql:host=$host;dbname=$db_name",$db_username,$db_password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);


	//establish connection to database
	//$conn = mysqli_connect($host, $db_username, $db_password, $db_name);

	//check connection
	if(!$conn) {
		die("Connection failed: " . mysqli_error($conn));
	}