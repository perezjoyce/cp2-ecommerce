<?php 

    session_start(); 
    //require_once "../../controllers/connect.php";

    include_once '../../sources/pdo/src/PDO.class.php';

    //set values
    $host = "localhost";
    $db_username = "root";
    $db_password = "";
    $db_name = "db_demoStoreNew";

    $conn = new PDO("mysql:host=$host;dbname=$db_name",$db_username,$db_password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $userId = $_SESSION['id'];
    $cartSession = $_SESSION['cart_session'];
    
    $sql = " SELECT * FROM tbl_orders WHERE `user_id` = ? AND cart_session = ? ";
    $statement = $conn->prepare($sql);
    $statement->execute([$userId, $cartSession]);
    $row = $statement->fetch();
    

    $transactionCode = $row['transaction_code'];
?>

<form id="form_confirmation">
    
    <label class="my-5">Confirmation Page</label>

    <br>

    <label>Transaction Code</label>
    <div class="mb-5 text-danger"><?= $transactionCode ?></div>

    <div class="mb-5">Thank you for shopping! Your order is being processed.</div>

    <a  class="btn btn-block btn-outline-success mb-5" data-dismiss="modal">CLOSE</a>

</form>





