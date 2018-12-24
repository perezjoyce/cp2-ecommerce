<?php

session_start(); 
require_once "connect.php";

if (isset($_POST['productId'])) {

    $cartSession = $_SESSION['cart_session'];
    $productId = $_POST['productId'];

    $quantity = 1;
    $sql = " SELECT * FROM tbl_carts WHERE cart_session=? AND item_id=?";
    $statement = $conn->prepare($sql);
    $statement->execute([$cartSession, $productId]);
    $count = $statement->rowCount();

    date_default_timezone_set('Asia/Manila');
		$date = date('Y-m-d H:i:s');
    
    if($count) {
        $row = $statement->fetch();
        $quantity = $row['quantity'] + 1;
        $sql = " UPDATE tbl_carts SET quantity=? WHERE cart_session=? ";
        $statement = $conn->prepare($sql);
	      $statement->execute([$quantity, $cartSession]);
    } else {
        $sql = " INSERT INTO tbl_carts ( dateCreated, item_id, quantity, cart_session) VALUES (?, ?, ?, ?) ";
        $statement = $conn->prepare($sql);
	      $statement->execute([$date, $productId, $quantity, $cartSession]);
    }

    $sql = " SELECT * FROM tbl_carts WHERE cart_session=? ";
    $statement = $conn->prepare($sql);
    $statement->execute([$cartSession]);
    $count = $statement->rowCount();

    // var_dump($count); die();
    // echo $count;

    $response =[];

    //header cart
    $sql = "SELECT c.item_id, c.quantity, p.img_path, p.name, p.price, p.id as productId
            FROM tbl_carts c 
            JOIN tbl_items p on p.id=c.item_id 
            WHERE cart_session=?";
            //$result = mysqli_query($conn, $sql);
            $statement = $conn->prepare($sql);
            $statement->execute([$cartSession]);

            $count1 = $statement->rowCount();
            // $subtotalPrice = 0;
            if($count1) {
                while($row = $statement->fetch()){
                    $productId = $row['item_id'];
                    $name = $row['name'];
                    $price = $row['price'];
                    $quantity = $row['quantity'];
                    $image = $row['img_path'];  
        
                $newProductAdded = 
                  "<div class='dropdown-item' id='product-row$productId'>
                    <div class='row mx-1'>
                      <div class='d-flex flex-row' style='justify-content:flex-start;width:100%;'>
                        <div class='flex pr-2'>
                            <img src='$image' style='width:30px;height:30px;'> 
                        </div>   
                        <div class='flex-fill'>
                            <div class='d-flex flex-column'>
                                <small>$name</small>
                                <small class='text-gray'>
                                    $price";
                                    if($quantity > 1) {
                                      $newProductAdded .="&nbsp;&nbsp;".$quantity;
                                    } 
                                $newProductAdded .= "</small>
                            </div>
                        </div>
                        <div class='flex-fill text-right' style='align-self:end;'>
                            <a data-productid='$productId' role='button' class='btn_delete_item text-gray flex-fill font-weight-light' style='font-size:16px;'>
                            &times;
                            </a>
                        </div>
                      </div>
                    </div>
                  </div>";
                }
            }
        //header cart button
        
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
            
    $response = ['newProduct' => $newProductAdded, 'itemsInCart' => $count, 'button' => $button];

    echo json_encode($response);
} 





