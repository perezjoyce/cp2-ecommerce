<?php

	include_once '../sources/pdo/src/PDO.class.php';

	//set values
	$host = "localhost";
	$db_username = "root";
	$db_password = "";
	$db_name = "db_demoStoreNew";

	$conn = new PDO("mysql:host=$host;dbname=$db_name",$db_username,$db_password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);


	//establish connection to database
	//$conn = mysqli_connect($host, $db_username, $db_password, $db_name);

	//check connection
	if(!$conn) {
		die("Connection failed: " . mysqli_error($conn));
	}