<?php

	require_once "connect.php";
	session_start();

    if(isset($_POST['regionId'])){

        $password = sha1($_POST['password']);
        $userId = $_SESSION['id'];
        $regionId = $_POST['regionId'];
        $provinceId = $_POST['provinceId'];
        $cityMunId = $_POST['cityMunId'];
        $brgyId = $_POST['brgyId'];
        $streetBldgUnit = $_POST['streetBldgUnit'];
        $landmark = $_POST['landmark'];
        $addressType = $_POST['addressType'];
        // $name = $_POST['name'];

        $sql = " SELECT * FROM tbl_users WHERE `password` = ? AND id = ? ";

			$statement = $conn->prepare($sql);
			$statement->execute([$password, $userId]);
            $count = $statement->rowCount();
            
            if($count) {
       
            //CHECK IF USER ALREADY HAS AN ADDRESS
            $sql = " SELECT * FROM tbl_addresses WHERE `user_id` = ? ";
            $statement = $conn->prepare($sql);
            $statement->execute([$userId]);

            $count = $statement->rowCount();

            //IF USER HAS ADDRESS...
            if($count) {
                // CHECK IF USER HAS THE GIVEN ADDRESS TYPE
                $sql = " SELECT * FROM tbl_addresses WHERE addressType = ? AND `user_id` = ? ";
                $statement = $conn->prepare($sql);
                $statement->execute([$addressType, $userId]);
                $count = $statement->rowCount();

                //IF USER HAS GIVEN ADDRESS TYPE, UPDATE IT
                if($count) {
                    $sql = " UPDATE tbl_addresses SET region_id = ?, province_id = ?, city_id = ?, brgy_id = ?,
                        street_bldg_unit = ?, landmark = ? WHERE addressType = ? AND `user_id` = ? ";
                    $statement = $conn->prepare($sql);
                    $statement->execute([$regionId, $provinceId, $cityMunId, $brgyId, $streetBldgUnit, $landmark, $addressType, $userId]);

                } else {
                    // IF NO, INSERT IT
                    $sql = " INSERT INTO tbl_addresses ( `user_id`, addressType ,region_id, province_id, city_id, brgy_id, street_bldg_unit, landmark, 
                    ) 
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?) ";
                    $statement = $conn->prepare($sql);
                    $statement->execute([ $userId, $addressType, $regionId, $provinceId, $cityMunId, $brgyId, $streetBldgUnit, $landmark]);
                
                }


            } else {
                // IF USER DOESN'T HAVE ADDRESS YET, INSERT ALL DATA
                $sql = " INSERT INTO tbl_addresses ( `user_id`, addressType ,region_id, province_id, city_id, brgy_id, street_bldg_unit, landmark ) 
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?) ";
                $statement = $conn->prepare($sql);
                $statement->execute([ $userId, $addressType, $regionId, $provinceId, $cityMunId, $brgyId, $streetBldgUnit, $landmark]);
                $row = $statement->fetch();
                $addressType = $row['addressType'];
            }

            echo "success";

        } else {
            echo "fail";
        }

}