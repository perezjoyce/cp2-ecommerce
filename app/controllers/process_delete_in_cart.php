<?php
require_once '../../config.php';

if (isset($_POST['variationId'])) {

    $cartSession = $_SESSION['cart_session'];
    $variationId = $_POST['variationId'];
    $response = [];

    // DELETE ITEM FROM CART
    $sql = " DELETE FROM tbl_carts WHERE cart_session=? AND variation_id=? ";
    $statement = $conn->prepare($sql);
    $result = $statement->execute([$cartSession, $variationId]);

    // COUNT NEW NUMBER OF ITEMS IN CART
    $sql = " SELECT SUM(quantity) as 'itemsInCart' FROM tbl_carts WHERE cart_session = ? ";
    $statement = $conn->prepare($sql);
    $statement->execute([$cartSession]);
    $row = $statement->fetch();
    $itemsInCart = $row['itemsInCart'];

    echo $itemsInCart;
       

}