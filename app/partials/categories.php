<?php
require_once '../../../config.php';
?>
    <!-- CATEGORIES: FOR INDEX AND CATALOG PAGE ONLY -->
    <div class="container-fluid border-bottom white-bg">
        <div class="row px-lg-5 px-md-5">
            <div class="col-12 text-center">
                <div class="container">
                    <!-- <div class="row text-center"> -->
                    <div class="d-flex flex-lg-row flex-md-row flex-sm-column">
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
        </div>  
    </div>