<?php
    require_once '../../config.php';

    if(isset($_POST['regionId'])){

        $userId = $_SESSION['id'];
        $regionId = $_POST['regionId'];
        $provinceId = $_POST['provinceId'];
        $cityMunId = $_POST['cityMunId'];
        $brgyId = $_POST['brgyId'];
        $streetBldgUnit = $_POST['streetBldgUnit'];
        $landmark = $_POST['landmark'];
        $addressType = $_POST['addressType'];
        $name = $_POST['name'];
        // $_SESSION['preselectedAddressId'] = $_POST['addressId'];
        
        //CHECK IF USER ALREADY HAS AN ADDRESS AND THE GIVEN ADDRESS TYPE
        $sql = " SELECT * FROM tbl_addresses WHERE `user_id`=? AND addressType = ? ";
        $statement = $conn->prepare($sql);
        $statement->execute([$userId, $addressType]);
        $count = $statement->rowCount();

        //IF YES, UPDATE IT
        if($count) {
            $sql = " UPDATE tbl_addresses SET region_id = ?, province_id = ?, city_id = ?, brgy_id = ?,
                street_bldg_unit = ?, landmark = ?, name = ? WHERE addressType = ? AND `user_id` = ? ";
            $statement = $conn->prepare($sql);
            $statement->execute([$regionId, $provinceId, $cityMunId, $brgyId, $streetBldgUnit, $landmark, $name, $addressType, $userId]);

        } else {
            // IF NO, INSERT IT
            $sql = " INSERT INTO tbl_addresses ( `user_id`, addressType ,region_id, province_id, city_id, brgy_id, street_bldg_unit, landmark, `name` ) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?) ";
            $statement = $conn->prepare($sql);
            $statement->execute([ $userId, $addressType, $regionId, $provinceId, $cityMunId, $brgyId, $streetBldgUnit, $landmark, $name]);
            
        }

        // GET ID OF ADDEDED/UPDATED ADDRESS
        $sql = " SELECT * FROM tbl_addresses WHERE `user_id` = ? AND addressType = ? ";
        $statement = $conn->prepare($sql);
        $statement->execute([$userId,$addressType]);
        $row = $statement->fetch();
        $address_id = $row['id'];
        $_SESSION['billingAddressId'] = $address_id;
        $cartSession = $_SESSION['cart_session'];

        // INSERT CHOSEN BILLING ADDRESS TO TBL_ORDERS BUT FIRST, CHECK IF CART SESSION ALREADY EXISTS
        $sql = " SELECT * FROM tbl_orders WHERE cart_session = ? AND `user_id` = ? ";
        $statement = $conn->prepare($sql);
        $statement->execute([$cartSession, $userId]);
        $count = $statement->rowCount();

        if($count) {
            $sql = " UPDATE tbl_orders SET billing_address_id = ? WHERE cart_session = ? AND `user_id` = ? ";
            $statement = $conn->prepare($sql);
            $statement->execute([$address_id, $cartSession, $userId]);
        } else {
            $sql = " INSERT INTO tbl_orders (cart_session, `user_id`, billing_address_id) VALUES (?, ?, ?) ";
            $statement = $conn->prepare($sql);
            $statement->execute([$cartSession, $userId, $address_id]);
        }


        // $row = $statement->fetch(PDO::FETCH_ASSOC);
        // echo json_encode($row);

        echo "success";

    }
    

			
        
