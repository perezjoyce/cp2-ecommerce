<?php 

    session_start(); 
    require_once "../controllers/connect.php";

    if(isset($_POST['userId'])) {

        $userId = $_POST['userId'];
        $sql = " SELECT * FROM tbl_wishlists WHERE user_id = $userId";
        $result = mysqli_query($conn, $sql);

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

            if(mysqli_num_rows($result) > 0){
                    
                $sql = "SELECT w.*, p.img_path, p.name, p.price, p.id as productId
                FROM tbl_wishlists w 
                JOIN tbl_items p on p.id=w.product_id 
                WHERE user_id= $userId ";
                $result = mysqli_query($conn, $sql);

                // var_dump($result);die();
                while($row = mysqli_fetch_assoc($result)){
                    $productId = $row['product_id'];
                    $name = $row['name'];
                    $price = $row['price'];
                    $image = $row['img_path'];

                echo "      
                    <tr id='wish-row$productId'>
                        <td>
                            $name
                            <br>
                            <a href='product.php?id=$productId'>
                                <img class='unitImage' src='$image' style='width:50px;height:50px;'> 
                            </a>
                        </td>

                        <td>
                                &#8369; 
                            <span class='unitPrice'> 
                                $price 
                            </span> </td>
                        </td>

                        <td> 
                            <a data-productid='$productId' role='button' class='btn_delete_wish' id='btn_delete_wish'>
                                Delete
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