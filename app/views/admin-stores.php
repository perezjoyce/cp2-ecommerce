<?php require_once "../../config.php";?>
<?php

    if(empty($_GET['id'])){ 
        echo "<script>window.location.href='".BASE_URL."/app/views/'</script>";
    } else {

        if(isset($_SESSION['id'])){
            $adminid = $_SESSION['id'];
            $currentUser = getUser($conn, $adminid);
            if($currentUser['userType'] == "admin") {
                require_once "../partials/admin_header.php";
            } else {
                echo "<script>window.location.href='".BASE_URL."/app/views/'</script>";
            }
        } else {
            echo "<script>window.location.href='".BASE_URL."/app/views/'</script>";
        }

    }

?>
    <!-- PAGE CONTENT -->
    <br>
    <div class="container p-0 my-lg-5 mt-md-5">

        


        <div class="row mx-0">

          
            <!-- MAIN BAR-->
            <div class="col">

                <!-- SEARCH BAR -->
                <div class='container p-5 rounded' style='background:white;'>
                    <div class="row mx-0">
                        <div class="col-lg-6 col-md-4 col-sm-12">
                            <h4>Manage Users</h4>
                        </div>
                        <div class="col">
                            <div class="input-group input-group-lg">
                                <div class="input-group-prepend">
                                    <span class="input-group-text border-right-0 border-left-0 border-top-0" style='background:white;'>
                                        <i class="fas fa-search" style='background:white;'></i>
                                    </span>
                                </div>
                                <input type="text" class="form-control border-right-0 border-left-0 border-top-0" id="btn_search_inventory">
                            </div>
                        </div>
						
					</div>
                </div>

                
                
                <!-- NEW ORDERS -->
                <div class="container px-5 pb-5 rounded" style='background:white;'>
                               
                    <?php 

                        $sql = " SELECT s.id AS 'store_id', s.logo, s.name, s.date_created, s.store_address, s.user_id, u.first_name, u.last_name 
                                FROM tbl_stores s 
                                JOIN tbl_users u 
                                ON s.user_id=u.id ";
                                $statement = $conn->prepare($sql);
                                $statement->execute();
                      
                    ?>
                     <div class="row">
                        <div class="col-12 px-2 mb-0 table-responsive-sm">
                            
                            <table class="table table-hover borderless text-center bg-gray mb-0">
                                <thead>
                                    <tr class='py-0'>
                                        <td width='5%'>Id</td>
                                        <td width='15%'>
                                            <div class="d-flex align-items-center text-center justify-content-center">
                                                <div>
                                                    Shop
                                                </div>
                                                <div class='d-flex flex-column'>
                                                    <i class="fas fa-angle-up text-gray pl-2 sort_inventory" data-userid='<?=$id?>' data-column="username" data-order='ASC' style='cursor: pointer;'></i>
                                                    <i class="fas fa-angle-down text-gray pl-2 sort_inventory" data-userid='<?=$id?>' data-column="username" data-order='DESC' style='cursor: pointer;'></i>
                                                </div>
                                            </div>
                                        </td>
                                        <td width='15%'>
                                            <div class="d-flex align-items-center text-center justify-content-center">
                                                <div>
                                                   Owner
                                                </div>
                                                <div class='d-flex flex-column'>
                                                    <i class="fas fa-angle-up text-gray pl-2 sort_inventory" data-email='<?=$id?>' data-column="email" data-order='ASC' style='cursor: pointer;'></i>
                                                    <i class="fas fa-angle-down text-gray pl-2 sort_inventory" data-email='<?=$id?>' data-column="email" data-order='DESC' style='cursor: pointer;'></i>
                                                </div>
                                            </div>
                                        </td>
                                        <td width='20%'>Address</td>
                                        <td width='15%'>Member Since</td>
                                        <td width='15%'>Status</td>
                                        <td width='15%'>Action</td>
                                    </tr> 
                                </thead>
                                <tbody style='background:white;height:600px;overflow-y:auto;font-size:12px;' id='data-container'>
                                    
                                    <?php 
                                    
                                            while($row = $statement->fetch()){
                                                $storeId = $row['store_id'];
                                                // $firstName = $row['first_name'];
                                                // $lastName = $row['last_name'];
                                                $storeName = $row['name'];
                                                $dateCreated = $row['date_created'];
                                                $storeAddress = $row['store_address'];
                                                $firstName = $row['first_name'];
                                                $firstName = ucwords($firstName);
                                                $lastName = $row['last_name'];
                                                $lastName = ucwords($lastName);
                                                $sellerId = $row['user_id'];
                                                

                                                // $logo = $row['logo'];


                                                // if($logo == "") {
                                                //     $logo = DEFAULT_PROFILE; 
                                                //     $prefix = "rounded";
                                                // } else {
                                                //     $logo = BASE_URL ."/". $logo . "_80x80.jpg";
                                                //     $prefix = "";
                                                // } 
                        
                                               
                                          
                        
                                    ?>
                                        <tr>
                                            <div>

                                                <!-- STORE ID -->
                                                <td class='mx-0' width='5%'> 
                                                    <a data-url="../partials/templates/view_order_summary_modal.php" data-id='#' class='border-0 btn_view_order_history' style='cursor:pointer;size:15px;'>
                                                        <div class='py-3 text-secondary'>
                                                        <?= $storeId ?>
                                                        </div>
                                                    </a>
                                                </td>

                                                <!-- NAME -->
                                                <td class='mx-0' width='15%'> 
                                                    <a data-url="../partials/templates/view_order_summary_modal.php" data-id='#' class='border-0 btn_view_order_history' style='cursor:pointer;size:15px;'>
                                                        <div class='py-3 text-secondary'>
                                                        <?= $storeName ?>
                                                        </div>
                                                    </a>
                                                </td>
                                                
                                                <!-- OWNER -->
                                                <td class='mx-0' width='15%'> 
                                                    <a data-url="../partials/templates/view_order_summary_modal.php" data-id='#' class='border-0 btn_view_order_history' style='cursor:pointer;size:15px;'>
                                                        <div class='py-3 text-secondary'>
                                                            <span><?= $firstName . "&nbsp;" . $lastName ?></span>
                                                        </div>
                                                    </a>
                                                </td>

                                                 <!-- ADDRESS -->
                                                 <td class='mx-0' width='20%'> 
                                                    <a data-url="../partials/templates/view_order_summary_modal.php" data-id='#' class='border-0 btn_view_order_history' style='cursor:pointer;size:15px;'>
                                                        <div class='py-3 text-secondary'>
                                                            <span><?= $storeAddress ?></span>
                                                        </div>
                                                    </a>
                                                </td>

                                                

                                                <!-- MEMEBER SINCE -->
                                                <td class='mx-0' width='15%'> 
                                                    <a data-url="../partials/templates/view_order_summary_modal.php" data-id='#' class='border-0 btn_view_order_history' style='cursor:pointer;size:15px;'>
                                                        <div class='py-3 text-secondary memberSince'>
                                                            <span><?= $dateCreate ?></span>
                                                        </div>
                                                    </a>
                                                </td>


                                                <!-- STATUS -->
                                                <td class='mx-0' width='15%'> 
                                                    <div class="d-flex align-items-center py-3">
                                                        <a data-url="../partials/templates/view_order_summary_modal.php" data-id='#' class='btn_view_order_history flex-fill text-center text-secondary' style='cursor:pointer;size:15px;'>
                                                        <?php 

                                                            $sql = "SELECT * FROM tbl_users WHERE id =?";
                                                            $statement = $conn->prepare($sql);
                                                            $statement->execute([$sellerId]);
                                                            $row = $statement->fetch();
                                                            $status = $row['status'];

                                                            if($status == 1) {
                                                                echo "Active";
                                                            } elseif ($status == 0) {
                                                                // STORE ACCOUNT IS DELETED UPON DEACTIVATION HENCE THIS WON'T BE DISPLAYED
                                                                echo "";
                                                            } else {
                                                                echo "Pending Deactivation";
                                                            }
                                                        
                                                        ?>
                                                        </a>
                                                    </div>
                                                
                                                </td>


                                                <!-- ACTION -->
                                                <td class='mx-0' width='15%'> 
                                                    <a data-url="../partials/templates/view_order_summary_modal.php" data-id='#' class='border-0 btn_view_order_history' style='cursor:pointer;size:15px;'>
                                                        <div class='py-3 text-gray'>
                                                            <div class="dropdown show">
                                                                <a class="btn border dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                                    <small>CHOOSE 1</small>    
                                                                </a>

                                                                <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                                                                    <!-- ONCE CLICKED, BUTTON WILL BE CHANGED -->
                                                                
                                                                    <a class="dropdown-item btn_store_products_view" data-href='<?= BASE_URL ."/app/partials/templates/product_modal.php?id=". $productId?>'>
                                                                        <small>VIEW SHOP</small>
                                                                    </a>
                                                                    
    
                                                                    <a class="dropdown-item btn_delete_product" data-userid='<?= $id ?>' href="#">
                                                                        <small>VIEW ACCOUNT</small>
                                                                    </a>

                                                                    <!-- ONCE CLICKED, WILL BE TRANSFERRED TO ORDER HISTORY -->
                                                                    <a class="dropdown-item btn_delete_product" data-userid='<?= $id ?>' href="#">
                                                                        <small>DELETE ACCOUNT</small>
                                                                    </a>
                                                                    
                                                                </div>
                                                                <!-- put dropdown with two buttons: SEND MESSAGE, CONFIRM, CANCELL, COMPLETE -->
                                                            </div>
                                                        </div>
                                                    </a>
                                                </td>

                                                
                                                
                                            </div>
                                            
                                        </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                           
                        </div>
                    </div>
                </div>
                            


                
            </div>
            <!-- /MAIN BAR -->


        </div>
        <!-- /.ROW -->


    </div>
    <!-- /.CONTAINER -->


<?php require_once "../partials/footer.php";?>
<?php require_once "../partials/modal_container.php";?>
<?php require_once "../partials/modal_container_big.php"; ?>

  