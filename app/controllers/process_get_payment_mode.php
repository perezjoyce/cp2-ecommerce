<?php
require_once '../../config.php';

// if(isset($_POST['modeOfPaymentId'])) {
    $payment_mode_id = 1; // $_POST['modeOfPaymentId'] ?? 2;
    $cartSession = $_SESSION['cart_session'];
    $userId = $_SESSION['id'];
    $transactionCode = $_SESSION['transaction_code'];
    
    $sql = " SELECT * FROM tbl_orders WHERE cart_session = ? AND `user_id` = ? ";
    $statement = $conn->prepare($sql);
    $statement->execute([$cartSession, $userId]);
    $count = $statement->rowCount();
    
    if($count) {
        $sql2 = " UPDATE tbl_orders SET payment_mode_id = ?, transaction_code = ? WHERE `user_id` = ?  AND cart_session = ? ";
        $statement2 = $conn->prepare($sql2);
        $result2 = $statement2->execute([$payment_mode_id, $transactionCode, $userId, $cartSession]);

        $sql3= " SELECT name FROM tbl_payment_modes WHERE id = ?";
        $statement3 = $conn->prepare($sql3);
        $statement3->execute([$payment_mode_id]);
        $row3 = $statement3->fetch();
        $paymentModeName = $row3['name'];
        $_SESSION['paymentMode'] = $paymentModeName;

        //UPDATE STATUS OF ORDER IN TBL CARTS (1 IS PENDING)
        $sql3 = "UPDATE tbl_carts SET status_id = 1 WHERE cart_session=?";
        $statement3 = $conn->prepare($sql3);
        $statement3->execute([$cartSession]);
    
    }

        $messageForBuyer =   
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

                if($result2) {

                    // require '../../vendor/autoload.php';
                    // require '../../vendor/phpmailer/phpmailer/src/PHPMailer.php';
                    // require '../../vendor/phpmailer/phpmailer/src/Exception.php';

                    $buyerEmail = getEmail($conn,$userId);
                    $mail = new PHPMailer\PHPMailer\PHPMailer(true);
                    $staff_email = 'jpgarcia.ph@gmail.com'; // where the email is comming from // replace with admin email in the future
                    $users_email = $buyerEmail;//Where the email will go // replace with $email
                    $email_subject = 'Mamaroo Order Confirmation';
                    $email_body = $messageForBuyer;
        
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
        
        
                        }catch (Exception $e){
                            echo "Buyer Side: Sorry".$mail->ErrorInfo;
                        }
                    }
                    

                    if($result2) {
                    //GET EMAIL ADDRESSES OF SELLERS
                    $sql4 = "SELECT c.*, i.store_id 
                        FROM tbl_carts c 
                        JOIN tbl_variations v 
                        JOIN tbl_items i 
                        ON v.product_id=i.id 
                        AND c.variation_id=v.id 
                        WHERE cart_session = ?";
                        $statement4 = $conn->prepare($sql4);
                        $statement4->execute([$cartSession]);
                        $count4 = $statement4->rowCount();


                    if($count4){     

                    $messageForSeller =   
                        "<form>
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


                        while($row4 = $statement4->fetch()){  
                            $storeId = $row4['store_id'];
                            $sellerEmail = getSellerEmail($conn,$storeId);
                            // var_dump($sellerEmail);die();
                            $mail2 = new PHPMailer\PHPMailer\PHPMailer(true);
                
                            $staff_email2 = 'jpgarcia.ph@gmail.com'; // where the email is comming from // replace with admin email in the future
                            $users_email2 = $sellerEmail;//Where the email will go // replace with $email
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
                
                
                            } catch (Exception $e2){
                                    echo "Seller Side: Sorry ".$mail2->ErrorInfo;
                                }
                        }
                }
        }

        // if($payment_mode_id == 3) {
        //     echo "creditCard";
        // }
 //   }

?>