<?php 
require_once '../../config.php';

if(isset($_POST['storeName'])) {
    $cartSession = $_POST['cartSession'];
    $storeId = $_POST['storeId'];
    $storeName = $_POST['storeName'];
    $storeName = $storeName."&nbsp;";

    // var_dump($storeName);die();//


    $sql = "SELECT c.*, i.store_id,o.transaction_code, (v.variation_stock-quantity) AS 'new_stock'
            FROM tbl_carts c 
            JOIN tbl_variations v 
            JOIN tbl_items i 
            JOIN tbl_orders o
            ON v.product_id=i.id 
            AND c.variation_id=v.id 
            AND o.cart_session=c.cart_session
            WHERE c.cart_session = ?
            AND store_id = ?";
    $statement = $conn->prepare($sql);
    $statement->execute([$cartSession, $storeId]);
    $count = $statement->rowCount();

   if($count){ 
        while($row = $statement->fetch()){  
            $cartItemId = $row['id'];
            $userId = $row['user_id'];
            $variationId = $row['variation_id'];           
            $transactionCode =$row['transaction_code'];
            $newStock = $row['new_stock'];


            
            //UPDATE STATUS
            $sql2 = "UPDATE tbl_carts SET status_id = 2 WHERE id=?";
            $statement2 = $conn->prepare($sql2);
            $statement2->execute([$cartItemId]);

            echo "status udpated";

            //UPDATE STOCKS
            $sql3 = "UPDATE tbl_variations SET variation_stock = ? WHERE id=?";
            $statement3 = $conn->prepare($sql3);
            $statement3->execute([$newStock,$variationId]);

            echo "stocks updated";

        }
           

            $messageForBuyer =   
                "<form>
                    <h4>Mamaroo Order Confirmation!</h4>
                    <div style='padding-top:18px;'>Thank you for waiting!</div> 
                    <div style='padding-top:20px;'>". $storeName . "confirmed your order with the following transaction code.</div>
                    <h4 style='color:#c471ed;'>". $transactionCode . "</h4>
                    <div>Your order from this seller will be shipped within 24 hours.</div>
                    <div style='padding-top:30px;font-weight:bold;'>Team Mamaroo</div>
                    <div style='padding-top:10px;'>mamaroo@gmail.com</div>
                    <div>+06907-1234-4560</div>
                    <div>+06919-1454-1160</div>
                </form>";

                    require '../../vendor/autoload.php';
                    require '../../vendor/phpmailer/phpmailer/src/PHPMailer.php';
                    require '../../vendor/phpmailer/phpmailer/src/Exception.php';

                    $buyerEmail = getEmail($conn,$userId);
                    $mail = new PHPMailer\PHPMailer\PHPMailer(true);
                    $staff_email = 'jpgarcia.ph@gmail.com'; // where the email is comming from // replace with admin email in the future
                    $users_email = $buyerEmail;//Where the email will go // replace with $email
                    $email_subject = 'Mamaroo Order Confirmation';
                    $email_body = $messageForBuyer;
        
                    try{
                        $mail->isSMTP();
                        $mail->Host = 'smtp.gmail.com';
                        $mail->SMTPAuth = true;
                        $mail->Username = $staff_email;
                        $mail->Password = '8London*'; // totoong password
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

        


    }
    


