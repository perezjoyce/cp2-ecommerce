    <!-- CATEGORIES: FOR INDEX AND CATALOG PAGE ONLY -->
    <div class="container-fluid border-bottom vanish-sm white-bg">
        <div class="row px-5">
            <div class="col-12 text-center">
                <div class="container">
                    <!-- <div class="row text-center"> -->
                    <div class="d-flex flex-row">
                        <?php 
                            $sql = " SELECT * FROM tbl_categories LIMIT 8";
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
            <div class="col-1"></div>
        </div>  
    </div>