<?php
// require_once '../../../config.php';
?>
    <!-- CATEGORIES: FOR INDEX AND CATALOG PAGE ONLY -->
    <div class="container-fluid border-bottom">
        <div class="row white-bg">
            <div class="col-12">
                <div class="container">
                    <div class="row">
                        <div class="col d-flex flex-row text-center px-sm-0">
               
                
                            <?php 
                                $sql = " SELECT * FROM tbl_categories WHERE parent_category_id is null ";
                                $statement = $conn->prepare($sql);
                                $statement->execute();
                                
                                while($row = $statement->fetch()){  
                            ?>

                            <div class='flex-fill py-3 mx-0'>
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
    </div>