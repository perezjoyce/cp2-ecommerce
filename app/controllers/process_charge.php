<?php
 require_once '../../config.php';

  $token  = $_POST['stripeToken'];
  $email  = $_POST['stripeEmail'];
  $amount = $_SESSION['total_amount'];
  $userId = $_SESSION['id']; //client
  $clientFirstName = getFirstName($conn,$userId);
  $clientLastName = getLastName($conn,$userId);
  $clientName = $clientFirstName ." ". $clientLastName;
  $clientEmail = getEmail($conn,$userId);
  $cartSession = $_SESSION['cart_session'];
  $transactionCode = $_SESSION['transaction_code'];
  $payment_mode_id = 3;
  $messageForSeller =   "<form>
                <h4>Your Client is Waiting!</h4>
                <div style='padding-top:18px;'>Hello, Seller!</div> 
                <div style='padding-top:20px;'>You have received an order with the following transaction code:</div>

                <h4 style='color:#c471ed;'>$transactionCode</h4>
                <div>Please confirmation as soon as possible.</div>
                <div>Thank you!</div>   
                <div style='padding-top:30px;font-weight:bold;'>Team Mamaroo</div>
                <div style='padding-top:10px;'>mamaroo@gmail.com</div>
                <div>+06907-1234-4560</div>
                <div>+06919-1454-1160</div>
            </form>";

  $customer = \Stripe\Customer::create([
      'email' => $email,
      'source'  => $token
  ]);

  $charge = \Stripe\Charge::create([
      'customer' => $customer->id,
      'amount'   => $amount,
      'currency' => 'usd'
  ]);

  $sql = " SELECT * FROM tbl_orders WHERE cart_session = ? AND `user_id` = ? ";
  $statement = $conn->prepare($sql);
  $statement->execute([$cartSession, $userId]);
  $count = $statement->rowCount();
  
  if($count) {
      $sql2 = " UPDATE tbl_orders SET payment_mode_id = ?, transaction_code = ? WHERE `user_id` = ?  AND cart_session = ? ";
      $statement2 = $conn->prepare($sql2);
      $result2 = $statement2->execute([$payment_mode_id, $transactionCode, $userId, $cartSession]);

      $paymentModeName = "Credit Card";
      $_SESSION['paymentMode'] = $paymentModeName;

      //UPDATE STATUS OF ORDER IN TBL CARTS (1 IS PENDING SO SELLER CAN STILL CHECK PAYMENT TRANSACTION BEFORE CONFIRMING)
      $sql3 = "UPDATE tbl_carts SET status_id = 1 WHERE cart_session=?";
      $statement3 = $conn->prepare($sql3);
      $statement3->execute([$cartSession]);
  
  }

  // get cart for specific seller
  $sql4 = "SELECT SUM(quantity*price) 
            AS 'subtotalPerStore', i.store_id, s.name, s.free_shipping_minimum, s.standard_shipping, u.email 
            FROM tbl_carts c 
            JOIN tbl_variations v 
            JOIN tbl_items i 
            JOIN tbl_stores s 
            JOIN tbl_users u 
            ON c.variation_id=v.id 
            AND v.product_id=i.id 
            AND i.store_id=s.id 
            AND s.user_id=u.id 
            WHERE cart_session = ? 
            GROUP BY store_id";
            $statement4 = $conn->prepare($sql4);
            $statement4->execute([$cartSession]);

    while($row4 = $statement4->fetch()) {
        $storeId = $row4['store_id'];
        $storeEmail = $row4['email'];
        $storeName = $row4['name'];

        $subtotalPerStore = $row4['subtotalPerStore'];
        $standardShippingPerStore = $row4['standard_shipping'];
        $freeShippingMinimumPerStore = $row4['free_shipping_minimum'];
        $totalPerStore = 0;

        if($subtotalPerStore >= $freeShippingMinimumPerStore){
            $totalPerStore = $subtotalPerStore;
        } else {
            $totalPerStore = $subtotalPerStore + $freeShippingMinimumPerStore;
        }

        $mamarooServiceCharge = $totalPerStore * .03; // 3% of total Amount

        //INSERT CREDIT AND DEBIT/SERVICE CHARGE PER SELLER -- PATTERNED AFTER METROBANK
        $sql5 = "INSERT INTO tbl_seller_accounts(store_id, debit, credit, transaction_code, description, `timestamp`) VALUES(?,?,?,?,?, NOW())";
        $statement5 = $conn->prepare($sql5);
        $statement5->execute([$storeId, 0, $totalPerStore, $transactionCode, 'payment recieved from customer: '. $clientName]);

        $sql6 = "INSERT INTO tbl_seller_accounts(store_id, debit, credit, transaction_code, description, `timestamp`) VALUES(?,?,?,?, NOW())";
        $statement6 = $conn->prepare($sql6);
        $statement6->execute([$storeId, $mamarooServiceCharge, 0, $transactionCode, 'service charge from Mamaroo to: '. $storeName]);

        //CHECK IF SUCCESSFUL
        $sql7 = "SELECT * FROM tbl_seller_accounts WHERE transaction_code = ? AND store_id ?";
        $statement7 = $conn->prepare($sql7);
        $statement7->execute([$transactionCode, $storeId]);
        $count7 = $statement7->rowCount();

        //SEND EMAIL TO EACH SELLER
        if($count7) {
            $mail2 = new PHPMailer\PHPMailer\PHPMailer(true);
            $staff_email2 = 'jpgarcia.ph@gmail.com'; // where the email is comming from // replace with admin email in the future
            $users_email2 = $storeEmail;//Where the email will go // replace with $email
            $email_subject2 = 'Mamaroo Order Alert!';
            $email_body2 = $messageForSeller;

            try{
                $mail2->isSMTP();
                $mail2->Host = 'smtp.gmail.com';
                $mail2->SMTPAuth = true;
                $mail2->Username = $staff_email2;
                $mail2->Password = '1Borongan!'; // totoong password
                $mail2->SMTPSecure = 'tls';
                $mail2->Port = 587;
                $mail2->setFrom($staff_email2,'Mamaroo');
                $mail2->addAddress($users_email2);
                $mail2->isHTML(true);
                $mail2->Subject = $email_subject2;
                $mail2->Body = $email_body2;
                $mail2->send();

                echo "Message sent to seller!".$sellerEmail;
                // $emailForSeller = "sent";

            } catch (Exception $e2){
                echo "Seller Side: Sorry ".$mail2->ErrorInfo;
                // $emailForSeller = "notSent";
            }
        }
    }
        

  
    //SEND 1 EMAIL TO CLIENT
    $messageForClient =   
    "<form>
        <h4>Mamaroo Order Receipt</h4>
        <div style='padding-top:18px;'>Hi there!</div> 
        <div style='padding-top:20px;'>Your order, with the following transaction code, is being processed:</div>
        <h4 style='color:#c471ed;'>$transactionCode</h4>
        <div>You will get confirmation message from the seller afterwards.</div>
        <div>Thank you for shopping with us!</div>   
        <div style='padding-top:30px;font-weight:bold;'>Team Mamaroo</div>
        <div style='padding-top:10px;'>mamaroo@gmail.com</div>
        <div>+06907-1234-4560</div>
        <div>+06919-1454-1160</div>
    </form>";

    if($count7) {
    $mail = new PHPMailer\PHPMailer\PHPMailer(true);
    $staff_email = 'jpgarcia.ph@gmail.com'; // where the email is comming from // replace with admin email in the future
    $users_email = $clientEmail;//Where the email will go // replace with $email
    $email_subject = 'Mamaroo Order Confirmation';
    $email_body = $messageForClient;

    try{
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->CharSet = 'UTF-8';
        $mail->SMTPAuth = true;
        $mail->Username = $staff_email;
        $mail->Password = '1Borongan!'; // totoong password
        $mail->SMTPSecure = 'tls';
        $mail->Port = 587;
        $mail->setFrom($staff_email,'Mamaroo');
        $mail->addAddress($users_email);
        $mail->isHTML(true);
        $mail->Subject = $email_subject;
        $mail->Body = $email_body;
        $mail->send();

            echo "Message sent to buyer!".$buyerEmail;
            //    $emailForClient = "sent";
        } catch (Exception $e){
            echo "Buyer Side: Sorry".$mail->ErrorInfo;
            // $emailForClient = "notSent";
        }
    }
                    
    header("Location: ../views/stripe_confirmation.php?id".$userId);