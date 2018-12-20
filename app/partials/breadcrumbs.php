<?php 

// BREADCRUMBS
if(isset($_GET['id'])) {
    $id = $_GET['id'];
    $sql = "SELECT * FROM tbl_categories WHERE id=?";
    $statement = $conn->prepare($sql);
    $statement->execute([$id]);
    
    $row = $statement->fetch();
    $name = $row['name']; 
?>

        <div class="container">
            <div class="row my-4">
                <div class="col-12">
                    <span>
                        <a href="index.php" class='text-purple'>
                            Home&nbsp;
                            <i class="fas fa-angle-right"></i> 
                        </a>
                    </span>
                    <span>
                        <a href="#" class='text-purple'>
                            <?= $name ?>&nbsp;
                        </a>
                    </span>
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
            