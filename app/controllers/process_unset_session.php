<?php 
    require_once '../../config.php';
	session_start(); // INITIATE
    unset($_SESSION["cart_session"]);
    // unset($_SESSION['paymentMode']);
    unset($_SESSION['transaction_code']);
    // header("Location: ../views/index.php"); // redirected to index.php
    $response = [''];
    $response = ['userId' => $_SESSION['id'], 'message' => 'success'];
    echo json_encode($response);

?>
