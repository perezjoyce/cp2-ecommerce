<?php
require_once '../../config.php';

if(isset($_GET['id'])) {
    $addressId = $_GET['id'];
    $sql = "SELECT 
            addr.*, 
            prov.provDesc as province_name,
            city.citymunDesc as city_name,
            brgy.brgyDesc as barangay_name
        FROM tbl_addresses addr 
        JOIN tbl_regions reg on reg.id=addr.region_id
        JOIN tbl_provinces prov on prov.regCode=reg.regCode
        JOIN tbl_cities city on city.provCode=prov.provCode
        JOIN tbl_barangays brgy on brgy.citymunCode=city.citymunCode
        WHERE addr.id=?";
    $statement = $conn->prepare($sql);
    $statement->execute([$addressId]);
    $row = $statement->fetch(PDO::FETCH_ASSOC);

    $_SESSION['preselectedAddressId'] = $addressId;

    echo json_encode($row);



}