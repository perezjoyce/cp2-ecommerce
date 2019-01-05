<?php 
    require_once '../sources/pdo/src/PDO.class.php';
	session_start(); // INITIATE
    unset($_SESSION["cart_session"]);
    // header("Location: ../views/index.php"); // redirected to index.php
    $response = [''];
    $response = ['userId' => $_SESSION['id'], 'message' => 'success'];
    echo json_encode($response);

?>
