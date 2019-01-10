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

  // get cart 
  $sql = "
        SELECT c.*, v.*, p.* FROM tbl_carts c 
        JOIN tbl_variations v ON v.id=c.variation_id
        LEFT JOIN tbl_items p on p.id=v.product_id
        WHERE cart_session=?";
  $statement0 = $conn->prepare($sql);
  $statement0->execute([$cartSession]);

  while($row0 = $statement0->fetch()) {
    $storeId = $row0['store_id'];
    $sql = "INSERT INTO tbl_seller_account(store_id, debit, credit, description, `timestamp`) VALUES(?,?,?,?, NOW())";
    $statement = $conn->prepare($sql);
    $statement->execute([$storeId, 0, $amount, 'payment recieved from customer: '. $userId]);

    // charge the seller for using shoperoo
    $sql2 = "INSERT INTO tbl_seller_account(store_id, debit, credit, description, `timestamp`) VALUES(?,?,?,?, NOW())";
    $statement2 = $conn->prepare($sql2);
    $shoperooServiceCharge = $amount * .03; // 3% of total Amount
    $statement->execute([$storeId, $shoperooServiceCharge, 0, 'Service Charge to Mamaroo: '. $userId]);
  }
  $transactionCode = $_SESSION['transaction_code'];
  session_start(); // INITIATE
//   unset($_SESSION["cart_session"]);
//   unset($_SESSION['paymentMode']);
//   unset($_SESSION['transaction_code']);
  
  // SEND an email to customer and seller for the info about the transaction
  // echo "Payment successful!";

//   echo "<script>
    //   setTimeout(function(){windlow.location.href='".BASE_URL."/app/views/stripe_confirmation.php?transactionCode=".base64_encode($transactionCode)."}, 1500);
//   </script>";
  // unset session
  //header('location: ../app/views/stripe_confirmation.php?transactionCode='.$transactionCode);

  header("Location: ../views/stripe_confirmation.php?transactionCode=".base64_encode($transactionCode));