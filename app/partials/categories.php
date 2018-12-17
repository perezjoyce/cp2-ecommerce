    <!-- CATEGORIES: FOR INDEX AND CATALOG PAGE ONLY -->
    <div class="container-fluid border-bottom vanish-sm">
        <div class="row px-5">
            <div class="col-1"></div>
            <div class="col-10 px-5">
                <div class="row text-center">
            
                    <?php 
                        $sql = " SELECT * FROM tbl_categories LIMIT 7";
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

                    <div class='flex-fill py-3'>
                        <a class='font-weight-bold hover_bold' href="catalog.php?id=<?= $row['id']?>">
                             SALE
                        </a>
                    </div>

                </div>
            </div>
            <div class="col-1"></div>
        </div>  
    </div>