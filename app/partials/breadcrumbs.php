<?php 

// BREADCRUMBS
if(isset($_GET['id'])) {
    $id = $_GET['id'];
    $sql = "SELECT * FROM tbl_categories WHERE id = ? ";
    $statement = $conn->prepare($sql);
    $statement->execute([$id]);
    $row = $statement->fetch();
    $name = $row['name']; 

    $origin = $_SERVER['HTTP_REFERER'];
    $whereUserIsFrom = "";
    $url = "";
    $arrow ="";

    if($origin == BASE_URL . "/app/views/index.php"){
        $whereUserIsFrom = "Home";
        $url = "index.php";
        $arrow = "<i class='fas fa-angle-right text-purple'></i>";
    }elseif($origin == BASE_URL . "app/views/catalog.php?id=$id"){
        $whereUserIsFrom = "Catalog";
        $url = "catalog.php?id=$id";
        $arrow = "<i class='fas fa-angle-right text-purple'></i>";
    }else {
        $whereUserIsFrom ="";
        $url = "";
        $arrow = "";
    }
			
?>

        <div class="container">
            <div class="row my-4">
                <div class="col-12">
                    <span>
                        <a href="<?=$url?>" class='text-purple'>
                           <?=$whereUserIsFrom?>&nbsp;
                           <?= $arrow ?>
                        </a>
                    </span>
                    <span>
                        <a href="#" class='text-purple'>
                            <?= $name ?>&nbsp;
                            <i class="fas fa-angle-right"></i> 
                        </a>
                    </span>

<?php } elseif(isset($_GET['searchKey'])) { 
    $searchkey = $_GET['searchKey'];
?>
    
        <div class="container">
        <div class="row my-4">
            <div class="col-12">
            <span>
                <a href="index.php" class='text-purple'>
                   <?= ucwords(strtolower($searchkey)) ?>
                   <i class="fas fa-angle-right"></i>
                </a>
            </span>

<?php }else { ?>
    <div class="container">
        <div class="row my-4">
            <div class="col-12">
            
<?php } ?> 

                    <span id='level_2_breadcrumb'>
                        <!-- TO BE INSERTED BY JS & AJAX-->
                    </span>
                    <span id='level_3_breadcrumb'>
                        <!-- TO BE INSERTED BY JS & AJAX-->
                    </span>
                </div>
            </div>
        </div>
            