<?php 

    session_start(); 
    require_once "../controllers/connect.php";

    if(isset($_POST['userId'])) {

        $userId = $_POST['userId'];
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
        WHERE addr.user_id=?";
        $statement = $conn->prepare($sql);
        $statement->execute([$userId]);
        $count = $statement->rowCount();

        var_dump($count);die();
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
                                $addressType = ucfirst($row['addressType']);
                            ?>
                                <div class="row mb-5">
                                    <div class="col-lg-3">
                                       <?= $addressType ?>
                                    </div>
                                    <div class="col">
                                        
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


            