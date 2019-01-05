  <?php
    require_once '../sources/pdo/src/PDO.class.php';
    require_once "connect.php";
    session_start();

    if(isset($_POST['addressId'])){

        $addressId = $_POST['addressId'];
        $cartSession = $_SESSION['cart_session'];
        $userId = $_SESSION['id'];
  
        // INSERT CHOSEN BILLING ADDRESS TO TBL_ORDERS BUT FIRST, CHECK IF CART SESSION ALREADY EXISTS IN ORDERS (DELAYED PURCHASE)
        $sql = " SELECT * FROM tbl_orders WHERE cart_session = ? AND `user_id` = ? ";
        $statement = $conn->prepare($sql);
        $statement->execute([$cartSession, $userId]);
        $count = $statement->rowCount();

        if($count) {
            $sql = " UPDATE tbl_orders SET billing_address_id = ? WHERE cart_session = ? AND `user_id` = ? ";
            $statement = $conn->prepare($sql);
            $statement->execute([$addressId, $cartSession, $userId]);
        } else {
            $sql = " INSERT INTO tbl_orders (cart_session, `user_id`, billing_address_id) VALUES (?, ?, ?) ";
            $statement = $conn->prepare($sql);
            $statement->execute([$cartSession, $userId, $addressId]);
        }


        // $row = $statement->fetch(PDO::FETCH_ASSOC);
        // echo json_encode($row);

        echo "success";
    
    }