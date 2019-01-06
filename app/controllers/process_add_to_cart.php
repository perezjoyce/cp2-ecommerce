<?php

session_start(); 
require_once '../sources/pdo/src/PDO.class.php';
require_once "connect.php";
require_once "functions.php";


    $cartSession = $_SESSION['cart_session'];
    $variationId = $_POST['variationId'];
    $quantity = $_POST['quantity'];
    $date = date('Y-m-d H:i:s');
  
    //CHECK IF ITEM VARIATION IS ALREADY IN CART
    $sql = " SELECT * FROM tbl_carts WHERE cart_session=? AND variation_id=? ";
    $statement = $conn->prepare($sql);
    $statement->execute([$cartSession, $variationId]);
    $count = $statement->rowCount();

    //IF IT IS, ASSUME THAT USER WANTS TO UPDATE QUANTITY OF SPECIFIC VARIATION
    if($count) {
        $sql = " UPDATE tbl_carts SET quantity=? WHERE cart_session=? AND variation_id=?";
        $statement = $conn->prepare($sql);
        $statement->execute([$quantity, $cartSession, $variationId]);
        
    //IF IT IS NOT, INSERT IT INTO THE CART SESSION AS NEW PRODUCT ADDED 
    } else {

        //IF USER IS LOGGED IN
        if(isset($_SESSION['id'])) {

            $userId = $_SESSION['id'];
            $sql = " INSERT INTO tbl_carts (cart_session,date_created,variation_id,quantity,`user_id`) VALUES (?, ?, ?, ?, ?) ";
            $statement = $conn->prepare($sql);
            $statement->execute([$cartSession,$date,$variationId,$quantity,$userId]);
       
        //IF USER IS NOT LOGGED IN
        } else {
            $sql = " INSERT INTO tbl_carts (cart_session,date_created,variation_id,quantity) VALUES (?, ?, ?, ?) ";
            $statement = $conn->prepare($sql);
            $statement->execute([$cartSession,$date,$variationId,$quantity]);
        }
    }


    //PREPARE RESONSE TO BE ECHOED
    $response =[];

        //HEADER CART
        $sql = "SELECT v.product_id 
            AS 'productId', v.variation_name, c.variation_id, c.quantity, c.cart_session, p.img_path, p.name, p.price
            FROM tbl_carts c 
            JOIN tbl_items p 
            JOIN tbl_variations v
            ON v.product_id = p.id 
            AND c.variation_id=v.id 
            WHERE cart_session= ?";
            $statement = $conn->prepare($sql);
            $statement->execute([$cartSession]);

            $count1 = $statement->rowCount();
            // $subtotalPrice = 0;
            if($count1) {
                while($row = $statement->fetch()){
                    $variationId = $row['variation_id'];
                    $variationName = $row['variation_name'];
                    $variationName  = ucfirst(strtolower($variationName));
                    $productId = $row['productId'];
                    $name = $row['name'];
                    $price = $row['price'];
                    $quantity = $row['quantity'];
                    $image = $row['img_path'];  

                    

                    if($quantity > 1){
                        $quantityDisplay = "&nbsp;x&nbsp$quantity";
                    } else {
                        $quantityDisplay = "";
                    }

                    if($variationName == 'None') {
                        $newProductAdded = 
                        "<div class='dropdown-item' id='product-row$variationId'>
                          <div class='row mx-1'>
                            <div class='d-flex flex-row' style='justify-content:flex-start;width:100%;'>
                              <div class='flex pr-2'>
                                  <img src='$image' style='width:35px;height:35px;'> 
                              </div>   
                              <div class='flex-fill'>
                                  <div class='d-flex flex-column'>
                                      <small>$name</small>
                                      <small class='text-gray'>
                                          <span>$price</span>
                                          <span>$quantityDisplay</span>
                                      </small>
                                  </div>
                              </div>
                              <div class='flex-fill text-right' style='align-self:end;'>
                                  <a data-productid='$productId' data-vname='$variationName' data-variationid='$variationId' data-quantity='$quantity' role='button' class='btn_delete_item text-gray flex-fill font-weight-light' style='font-size:16px;'>
                                  &times;
                                  </a>
                              </div>
                            </div>
                          </div>
                        </div>";
                    } else {

                        $newProductAdded = 
                        "<div class='dropdown-item' id='product-row$variationId'>
                          <div class='row mx-1'>
                            <div class='d-flex flex-row' style='justify-content:flex-start;width:100%;'>
                              <div class='flex pr-2'>
                                  <img src='$image' style='width:35px;height:35px;'> 
                              </div>   
                              <div class='flex-fill'>
                                  <div class='d-flex flex-column'>
                                      <small>$name</small>
                                      <small>$variationName</small>
                                      <small class='text-gray'>
                                          <span>$price</span>
                                          <span>$quantityDisplay</span>
                                      </small>
                                  </div>
                              </div>
                              <div class='flex-fill text-right' style='align-self:end;'>
                                  <a data-productid='$productId' data-vname='$variationName' data-variationid='$variationId' data-quantity='$quantity' role='button' class='btn_delete_item text-gray flex-fill font-weight-light' style='font-size:16px;'>
                                  &times;
                                  </a>
                              </div>
                            </div>
                          </div>
                        </div>";
                        
                    }
                    
        
             
                }
            }
        
        //HEADER COUNT BUTTON
        $button = "<div class='dropdown-divider my-3'></div>
                  <a class='dropdown-item mb-3'>
                    <button class='modal-link btn btn-block btn-gradient' 
                        href='#' 
                      
                        data-url='../partials/templates/cart_modal.php' 
                        role='button'
                        id='cartModal'>
                        Go To Cart
                    </button>
                  </a>";

        //COUNT TOTAL QUANTITY OF ITEMS IN CART
        $sql = " SELECT SUM(quantity) as 'itemsInCart' FROM tbl_carts WHERE cart_session = ? ";
        $statement = $conn->prepare($sql);
        $statement->execute([$cartSession]);
        $row = $statement->fetch();
        $itemsInCart = $row['itemsInCart'];
            

        
        $response = ['newProduct' => $newProductAdded, 'itemsInCart' =>  $itemsInCart, 'button' => $button];
        echo json_encode($response);
        
            
    





