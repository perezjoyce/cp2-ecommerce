<?php

    session_start(); 
    require_once "../../controllers/connect.php";


    $cartSession = $_SESSION['cart_session'];
    if(!$_SESSION['id']) {
        // pass redirect url so that after logging in you will be able to return to the intended page, in this case check-out
        header("location: login_modal.php?redirectUrl=checkout");
    } else {

        //select tbl_users to check if the user has address id
    }