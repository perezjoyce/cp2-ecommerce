<?php

	require_once "connect.php";
	session_start();

	

    if(isset($_POST['regionId'])){

        $userId = $_SESSION['id'];
        $regionId = $_POST['regionId'];
        $provinceId = $_POST['provinceId'];
        $cityMunId = $_POST['cityMunId'];
        $brgyId = $_POST['brgyId'];
        $streetBldgUnit = $_POST['streetBldgUnit'];
        $landmark = $_POST['landmark'];
        $addressType = $_POST['addressType'];
        
        //CHECK IF USER ALREADY HAS AN ADDRESS
        $sql = " SELECT * FROM tbl_addresses WHERE `user_id` = $userId ";
        $result = mysqli_query($conn,$sql);
        $count = mysqli_num_rows($result);

        //IF USER HAS ADDRESS...
        if($count) {
            // CHECK IF USER HAS THE GIVEN ADDRESS TYPE
            $sql = " SELECT * FROM tbl_addresses WHERE addressType = $addressType AND `user_id` = $userId ";
            $result = mysqli_query($conn,$sql);
            $count = mysqli_num_rows($result);

            //IF USER HAS GIVEN ADDRESS TYPE, UPDATE IT
            if($count) {
                $sql = " UPDATE tbl_addresses SET region_id = $regionId, province_id = $provinceId, city_id = $cityMunId, brgy_id = $brgyId,
                    street_bldg_unit = '$streetBldgUnit', landmark = '$landmark' WHERE addressType = $addressType AND `user_id` = $userId ";
                $result = mysqli_query($conn,$sql); 
            } else {
                // IF NO, INSERT IT
                $sql = " INSERT INTO tbl_addresses ( addressType, region_id, province_id, city_id, brgy_id, street_bldg_unit, landmark ) 
                VALUES ($addressType, $regionId, $provinceId, $cityMunId, $brgyId, '$streetBldgUnit', '$landmark') WHERE `user_id` = $userId ";
                $result = mysqli_query($conn,$sql); 
            }


        } else {
            // IF USER DOESN'T HAVE ADDRESS YET, INSERT ALL DATA
            $sql = " INSERT INTO tbl_addresses ( `user_id`, addressType ,region_id, province_id, city_id, brgy_id, street_bldg_unit, landmark ) 
                VALUES ($userId, '$addressType', $regionId, $provinceId, $cityMunId, $brgyId, '$streetBldgUnit', '$landmark') ";
            $result = mysqli_query($conn,$sql); 
        }

    }
    

			
        
