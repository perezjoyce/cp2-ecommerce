<?php
require_once '../../config.php';
 
$location = $_POST['location'];
$columnName = $_POST['columnName'];
$order = $_POST['order'];
$table = "";

if($location == 'users'){
    $table = 'tbl_users';
} elseif($location == "stores") {
    $table = 'tbl_stores';
} else {
    $table ='tbl_seller_accounts';
}

if($table != 'tbl_seller_accounts') {
    $sql = "SELECT * FROM $table ORDER BY $columnName $order";
    $statement = $conn->prepare($sql);
    $statement->execute();

    if($location == 'users'){
        while($row = $statement->fetch()){
        $id = $row['id'];
        $username = $row['username'];
        $memberSince = $row['date_created'];
        $email = $row['email'];
        $email = hide_email($email);
        $userType = $row['userType'];
        $userType = strtoupper($userType);
        $isSeller = $row['isSeller'];
        $status = $row['status'];
    ?>

            <tr>
                <!-- USER ID -->
                <td class='mx-0' width='5%'> 
                    <div class='py-3 text-secondary'>
                        <?= $id ?>
                    </div>
                </td>

                <!-- USERNAME -->
                <td class='mx-0' width='15%'> 
                    <div class='py-3 text-secondary'>
                        <?= $username ?>
                    </div>
                </td>
                    
                <!-- EMAIL -->
                <td class='mx-0'> 
                    <div class='py-3 text-secondary'>
                        <?= $email ?>
                    </div>
                </td>

                <!-- USER TYPE -->
                <td class='mx-0' width='15%'> 
                    <div class='py-3 text-secondary'>
                        <?=$userType?>   
                    </div>
                </td>

                <!-- MEMEBER SINCE -->
                <td class='mx-0 vanish-md' width='15%'> 
                    <div class='py-3 text-secondary memberSince'>
                        <?= $memberSince ?>
                    </div>
                </td>


                <!-- STATUS -->
                <td class='mx-0' width='15%'> 
                    <div class='py-3 text-secondary'>
                        <?php 

                            if($status == 1) {
                                echo "Active";
                            } elseif ($status == 0) {
                                echo "Deactivated";
                            } else {
                                echo "Pending Deactivation";
                            }
                        
                        ?>
                    </div>
                </td>


                <!-- ACTION -->
                <td class='mx-0' width='15%'> 
                    <div class='py-3 text-gray'>
                        <div class="dropdown show">
                            <a class="btn border dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <small class='vanish-md'>CHOOSE 1</small>    
                            </a>

                            <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">

                                    <?php
                                        if($userType == 'USER') {
                                    ?>
                                <a class="dropdown-item btn_set" data-userid='<?=$id?>' data-role='admin' data-username='<?=$username?>'>

                                    <small>SET AS ADMIN</small>
                                </a>
                                    <?php } else {?>

                                <a class="dropdown-item btn_set" data-userid='<?=$id?>' data-role='user' data-username='<?=$username?>'>
                                    <small>SET AS USER</small>
                                </a>

                                    <?php } ?>
                                
                                <a class="dropdown-item btn_deactivate" data-userid='<?= $id ?>' href="#" data-username='<?=$username?>' data-isseller='<?=$isSeller?>' data-status='<?=$status?>'>
                                    <small>DEACTIVATE</small>
                                </a>

                            </div>
                            
                        </div>
                    </div>
                </td>
                
            </tr>


    <?php } } else {
        while($row = $statement->fetch()){
            $storeId = $row['store_id'];
            $storeName = $row['name'];
            $memberSince = $row['date_created'];
            $storeAddress = $row['store_address'];
            $sellerId = $row['user_id']; 
    ?>

            <tr>


                <!-- STORE ID -->
                <td class='mx-0' width='5%'> 
                    <div class='py-3 text-secondary'>
                        <?= getStoreId ($conn,$sellerId) ?>
                    </div>
                </td>

                <!-- NAME -->
                <td class='mx-0' width='15%'> 
                    <div class='py-3 text-secondary'>
                        <?= $storeName ?>
                    </div>
                </td>
                    
                <!-- OWNER -->
                <td class='mx-0' width='15%'> 
                    <div class='py-3 text-secondary'>
                        <?php 
                        $firstName = getFirstName ($conn,$sellerId);
                        $firstName = ucwords(strtolower($firstName));
                        $lastName = getLastName ($conn,$sellerId);
                        $lastName = ucwords(strtolower($lastName));
                        echo $firstName ." ". $lastName;
                        
                        ?>
                    </div>
                </td>

                <!-- ADDRESS -->
                <td class='mx-0' width='20%'> 
                    <div class='py-3 text-secondary'>
                        <?= $storeAddress ?>
                    </div>
                </td>

                    

                <!-- MEMEBER SINCE -->
                <td class='mx-0' width='15%'> 
                    <div class='py-3 text-secondary memberSince'>
                        <?= $memberSince ?>
                    </div>
                </td>


                <!-- STATUS -->
                <td class='mx-0' width='15%'> 
                    <div class='py-3 text-secondary'>
                        <?php 

                            $sql2 = "SELECT * FROM tbl_users WHERE id =?";
                            $statement2 = $conn->prepare($sql2);
                            $statement2->execute([$sellerId]);
                            $row2 = $statement2->fetch();
                            $status = $row2['status'];

                            if($status == 1) {
                                echo "Active";
                            } elseif ($status == 0) {
                                // STORE ACCOUNT IS DELETED UPON DEACTIVATION HENCE THIS WON'T BE DISPLAYED
                                echo "";
                            } else {
                                echo "Pending Deactivation";
                            }
                        
                        ?>
                    </div>
                </td>


                <!-- ACTION -->
                <td class='mx-0' width='15%'> 
                    <div class='py-3 text-gray'>
                        <div class="dropdown show">
                            <a class="btn border dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <small>CHOOSE 1</small>    
                            </a>

                            <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                            
                                <a class="dropdown-item btn_view_account" href="#" data-href='<?= BASE_URL ."/app/partials/templates/view_shop_modal.php?id=". $storeId?>'>
                                    <small>VIEW SHOP</small>
                                </a>
                                
                                <a class="dropdown-item btn_view_account" href="#" data-href="../partials/templates/account_summary_modal.php?id=<?=$storeId?>" >
                                    <small>VIEW ACCOUNT</small>
                                </a>

                                <a class="dropdown-item btn_deactivate" data-userid='<?= $sellerId ?>' href="#" data-username='<?=$owner?>' data-isseller='yes' data-status='<?=$status?>'>
                                    <small>DELETE SHOP</small>
                                </a>
                                
                            </div>
                        </div>
                    </div>
                </td>

            </tr>
    
<?php } } } else { 
    $sql = " SELECT ROUND(COUNT(store_id) /2, 0) 
    AS 'transactionCount', store_id, SUM(credit) 
    AS 'storeCredit', SUM(debit) 
    AS 'storeDebit' 
    FROM tbl_seller_accounts 
    GROUP BY store_id ORDER BY $columnName $order";
        $statement = $conn->prepare($sql);
        $statement->execute();

        while($row = $statement->fetch()){ 
            $storeId = $row['store_id'];
            $transactionCount = $row['transactionCount'];
            // $transactionCount = round($transactionCount, 0);
            $credit = $row['storeCredit'];
            $credit = number_format((float)$credit, 2, '.', ',');
            $debit = $row['storeDebit'];
            $debit = number_format((float)$debit, 2, '.', ',');
            $storeName = getStoreNameFromStoreId($conn, $storeId);
?>
    
            <tr>
                <!-- STORE NAME -->
                <td class='mx-0' width='20%'>
                    <div class='py-4 text-secondary'>
                        <?=$storeName?>
                    </div>
                </td>

                <!-- TRANSACTION COUNT -->
                <td class='mx-0' width='20%'>
                    <div class='py-4 text-secondary'>
                        <?= $transactionCount?>
                    </div>
                </td>

                <!-- BALANCE -->
                <td class='mx-0' width='20%'> 
                    <div class='d-flex flex-row justify-content-center text-secondary py-4'>
                        <div>&#36;&nbsp;</div>
                        <div><?=$credit?></div>
                    </div>
                </td>
                    

                <!-- CREDIT CHARGE -->
                <td class='mx-0' width='20%'> 
                    <div class='d-flex flex-row justify-content-center text-secondary py-4'>
                        <div>&#36;&nbsp;</div>
                        <div><?=$debit?></div>
                    </div>
                </td>

                <!-- VIEW -->
                <td class='mx-0' width='20%'>
                    <a data-href="../partials/templates/account_summary_modal.php?id=<?=$storeId?>" class='border-0 btn_view_account' style='cursor:pointer;size:15px;'>
                        <i class="far fa-file-pdf text-gray py-4" style='width:100%;'></i>
                    </a>
                </td>

                
            </tr>

<?php } } ?>

<script>
    var utcDateTime3 = $('.memberSince');
	var tz2 = moment.tz.guess();
	$.each(utcDateTime3, function(i, element){
		var dateTimeStr3 = $(element).text().trim();		
		$(element).text(moment(dateTimeStr3).tz(tz2).format("ll"));
	});
</script>


