<?php 

    session_start(); 
    require_once "../controllers/connect.php";
    require_once "../controllers/functions.php";

    if(isset($_SESSION['id'])) {

        $userId = $_SESSION['id'];
        $sql = "SELECT addr.*,
        reg.regDesc as region_name, prov.provDesc as province_name, city.citymunDesc as city_name, brgy.brgyDesc as barangay_name 
        FROM tbl_users user
        JOIN tbl_addresses addr on addr.user_id=user.id 
        JOIN tbl_regions reg on reg.id=addr.region_id 
        JOIN tbl_provinces prov on prov.regCode=reg.regCode 
        JOIN tbl_cities city on city.provCode=prov.provCode 
        JOIN tbl_barangays brgy on brgy.citymunCode=city.citymunCode 
        WHERE user.id=? GROUP BY addr.id";

        $statement = $conn->prepare($sql);
        $statement->execute([$userId]);
        $count = $statement->rowCount();

       //$row = $statement->fetch();
?>
            <div class='row pt-5 pl-5 flex-column'>
                <h4>My Shipping Addresses</h4>
                <?php if($count == 0) { ?>
                    <a class='nav-link modal-link px-0' href='#' data-id='<?= $userId ?>' data-url='../partials/templates/edit_address_modal.php' role='button'>
                        <i class='far fa-edit'></i>
                        Add Address
                    </a>
                <?php } else { ?>
                    <a class='nav-link modal-link px-0' href='#' data-id='<?= $userId ?>' data-url='../partials/templates/edit_address_modal.php' role='button'>
                        <i class='far fa-edit'></i>
                        Edit Address
                    </a>
                <?php } ?>
            </div>
            
            

                    <hr class="mb-5">
                    
                    <div class="row">
                        <!-- LEFT OF MAIN BAR -->
                        <div class="col border-right pl-5">
                            <?php 
                            if ($count){
							while($row = $statement->fetch()){ 
                                $addressType = capitalizeFirstLetter($row['addressType']);
                                $street_bldg_unit = capitalizeFirstLetter($row['street_bldg_unit']);
                                $barangay = capitalizeFirstLetter($row['barangay_name']);
                                $cityMun = capitalizeFirstLetter($row['city_name']);
                                $province = capitalizeFirstLetter($row['province_name']);
                                $region = $row['region_name'];
                            ?>
                                <div class="row mb-5">
                                    <div class="col-lg-3">
                                       <?= $addressType ?>
                                    </div>
                                    <div class="col">
                                        <?= $street_bldg_unit . ", Brgy. " . $barangay . ", " . $cityMun . ", " . $province . ", " . $region?>
                                    </div>
                                </div>
                            <?php } } else { ?>
                                <div class="row mb-5">
                                    No address has been added yet.
                                </div>
                            <?php } ?>  
                              
                        </div>
                        <!-- /LEFT OF MAIN BAR -->
                    </div>
                </div>
                <!-- /#MAIN-WRAPPER -->
            </div>
            <!-- /MAIN BAR -->


        </div>
        <!-- /.ROW -->
    </div>
    <!-- /.CONTAINER -->

<?php } ?>


            