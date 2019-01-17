<?php
 require_once '../../config.php';

  $token  = $_POST['stripeToken'];
  $email  = $_POST['stripeEmail'];
  $amount = $_SESSION['total_amount'];
  $userId = $_SESSION['id'];
  $cartSession = $_SESSION['cart_session'];

  $customer = \Stripe\Customer::create([
      'email' => $email,
      'source'  => $token,
  ]);

  $charge = \Stripe\Charge::create([
      'customer' => $customer->id,
      'amount'   => $amount,
      'currency' => 'usd',
  ]);

  // get cart for specific seller
  // 
  $sql = "
        SELECT c.*, v.*, p.* FROM tbl_carts c 
        JOIN tbl_variations v ON v.id=c.variation_id
        LEFT JOIN tbl_items p on p.id=v.product_id
        WHERE cart_session=?";
  $statement0 = $conn->prepare($sql);
  $statement0->execute([$cartSession]);

  while($row0 = $statement0->fetch()) {
    $storeId = $row0['store_id'];
    $sql = "INSERT INTO tbl_seller_accounts(store_id, debit, credit, description, `timestamp`) VALUES(?,?,?,?, NOW())";
    $statement = $conn->prepare($sql);
    $statement->execute([$storeId, 0, $amount, 'payment recieved from customer: '. $userId]);

    // charge the seller for using shoperoo
    $sql2 = "INSERT INTO tbl_seller_accounts(store_id, debit, credit, description, `timestamp`) VALUES(?,?,?,?, NOW())";
    $statement2 = $conn->prepare($sql2);
    $shoperooServiceCharge = $amount * .03; // 3% of total Amount
    $statement->execute([$storeId, $shoperooServiceCharge, 0, 'Service Charge to Mamaroo: '. $userId]);
  }
  $transactionCode = $_SESSION['transaction_code'];

  header("Location: ../views/stripe_confirmation.php?transactionCode=".base64_encode($transactionCode));