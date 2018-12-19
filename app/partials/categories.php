    <!-- CATEGORIES: FOR INDEX AND CATALOG PAGE ONLY -->
    <div class="container-fluid border-bottom vanish-sm">
        <div class="row px-5">
            <div class="col-1"></div>
            <div class="col-10 px-5">
                <div class="row text-center">
            
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
            <div class="col-1"></div>
        </div>  
    </div>