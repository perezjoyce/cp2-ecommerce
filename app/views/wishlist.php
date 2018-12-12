<?php 

    session_start(); 
    require_once "../controllers/connect.php";

    if(isset($_POST['userId'])) {

        $userId = $_POST['userId'];
        $sql = " SELECT * FROM tbl_wishlists WHERE user_id = ? ";
        $statement = $conn->prepare($sql);
        $statement->execute([$userId]);

            echo "
            <div class='row pt-5 pl-5 flex-column'>
                <h4>My Wish List</h4>
            </div>
            <hr class='mb-5'>

            <div class='row'>
                <table class='table table-bordered mx-4 mb-5'>
                    <tr>
                        <th> Item </th>
                        <th> Price </th>
                        <th> Action </th>
                    </tr>
            ";

            if($statement->rowCount()){
                    
                $sql = " SELECT w.*, p.img_path, p.name, p.price, p.id as productId
                FROM tbl_wishlists w 
                JOIN tbl_items p on p.id=w.product_id 
                WHERE user_id= ? ";
               $statement = $conn->prepare($sql);
               $statement->execute([$userId]);

                // var_dump($result);die();
                while($row = $statement->fetch()){
                    $productId = $row['product_id'];
                    $name = $row['name'];
                    $price = $row['price'];
                    $image = $row['img_path'];

                echo "      
                    <tr id='wish-row$productId'>
                        <td>
                         
                            <div class='row'>
                                <div class='col-lg-2'>
                                    <a href='product.php?id=$productId'>
                                        <img class='unitImage' src='$image' style='width:70px;height:70px;'> 
                                    </a>
                                </div>
                                    
                                <div class='col'>
                                    $name
                                    <br>
                                    <a data-productid='$productId' role='button' class='btn_delete_wish' id='btn_delete_wish'>
                                        <i class='far fa-trash-alt text-secondary'></i>
                                    </a>
                                </div>
                            </div>
                            
                        </td>

                        <td>
                                &#8369; 
                            <span class='unitPrice'> 
                                $price 
                            </span> </td>
                        </td>

                        <td> 
                            <a data-id='$productId' role='button' class='btn_add_to_cart_profile' id='btn_add_to_cart_profile'>
                                <i class='fas fa-cart-plus'></i>
                            </a>
                        </td>
                    </tr>
              
                    ";

                
                }    

            }
            echo "
                </table>
            </row>
            ";
    }