<?php
// require_once '../../../config.php';
?>
    <!-- CATEGORIES: FOR INDEX AND CATALOG PAGE ONLY -->
    <div class="container-fluid mx-0 px-0 border-bottom white-bg">
        <div class="row">
            <div class="col d-flex flex-lg-row flex-md-row flex-sm-column text-center ">
               
                
                        <?php 
                            $sql = " SELECT * FROM tbl_categories WHERE parent_category_id is null";
                            $statement = $conn->prepare($sql);
                            $statement->execute();
                            
                            while($row = $statement->fetch()){  
                        ?>

                        <div class='flex-fill py-3'>
                            <a class='hover_bold' href="catalog.php?id=<?= $row['id']?>">
                                <?= $row['name'] ?>
                            </a>
                        
                        </div>

                        <?php } ?>
                   

            </div>
        </div>  
    </div>