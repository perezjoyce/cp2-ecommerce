  <?php
    require_once '../../config.php';

    // if(isset($_POST['addressId'])){ 
    //     $addressId = $_POST['addressId'];
    // } else {
    //     $addressId = $_SESSION['preselectedAddressId'];
    // }

        $cartSession = $_SESSION['cart_session'];
        $userId = $_SESSION['id'];
  
        // INSERT CHOSEN BILLING ADDRESS TO TBL_ORDERS BUT FIRST, CHECK IF CART SESSION ALREADY EXISTS IN ORDERS (DELAYED PURCHASE)
        $sql = " SELECT * FROM tbl_orders WHERE cart_session = ? AND `user_id` = ? ";
        $statement = $conn->prepare($sql);
        $statement->execute([$cartSession, $userId]);
        $row = $statement->fetch();
        $billingAddress = $row['address_id'];
        $_SESSION['billingAddressId'] = $billingAddress;
        $count = $statement->rowCount();

        if($count) {
            $sql2 = " UPDATE tbl_orders SET billing_address_id = ? WHERE cart_session = ? AND `user_id` = ? ";
            $statement2 = $conn->prepare($sql2);
            $statement2->execute([$billingAddress, $cartSession, $userId]);
        } else {
            $sql3 = " INSERT INTO tbl_orders (cart_session, `user_id`, billing_address_id) VALUES (?, ?, ?) ";
            $statement3 = $conn->prepare($sql3);
            $statement3->execute([$cartSession, $userId, $billingAddress]);
        }

        $sql4 = "SELECT * FROM tbl_orders WHERE cart_session = ? AND billing_address_id = ?";
        $statement4 = $conn->prepare($sql4);
        $statement4->execute([$cartSession, $addressId]);
        $count4 = $statement4->rowCount();

        if($count4) {

        // $row = $statement->fetch(PDO::FETCH_ASSOC);
        // echo json_encode($row);
            echo "success";
        }
    
    