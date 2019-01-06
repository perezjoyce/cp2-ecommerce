<?php
  require_once('../../config.php');

  require_once '../sources/pdo/src/PDO.class.php';
  require_once './functions.php';

  $conn = new PDO("mysql:host=$host;dbname=$db_name",$db_username,$db_password);
  $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

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
    $statement->execute([$storeId, $shoperooServiceCharge, 0, 'Service Charge to shopee: '. $userId]);
  }
  

  // SEND an email to customer and seller for the info about the transaction
    echo "Payment successful!";
  echo "<script>
    $.get('../../app/controllers/process_unset_session.php', function(){
        setTimeout(function(){windlow.location.href='".BASE_URL."'}, 2000);
    });
  </script>";