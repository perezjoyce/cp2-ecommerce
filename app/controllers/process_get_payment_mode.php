<?
session_start(); 
require_once '../sources/pdo/src/PDO.class.php';
require_once "connect.php";


if(isset($_POST['modeOfPaymentId'])) {
   $payment_mode_id = $_POST['modeOfPaymentId'];
   $cartSession = $_SESSION['cart_session'];
   $userId = $_SESSION['id'];
    //  SET UNIQUE TRANSACTION CODE  
    // $_SESSION['transaction_code'];
    $unique_num = str_replace(".","",microtime(true)).rand(000,999);
    $unique_mix = substr(hash('sha256', mt_rand()), 0, 10);
    $_SESSION['transaction_code'] = $unique_num . " - " . $unique_mix;
    $transactionCode = $_SESSION['transaction_code'];


   $sql = " SELECT * FROM tbl_orders WHERE cart_session = ? AND `user_id` = ? ";
   $statement = $conn->prepare($sql);
   $statement->execute([$cartSession, $userId]);
   $count = $statement->rowCount();

        
   if($count) {
    $sql = " UPDATE tbl_orders SET payment_mode_id = ?, transaction_code = ? WHERE `user_id` = ?  AND cart_session = ? ";
    $statement = $conn->prepare($sql);
    $result = $statement->execute([$payment_mode_id, $transactionCode, $userId, $cartSession]);

        $sql2= " SELECT name FROM tbl_payment_modes WHERE id = ?";
        $statement2 = $conn->prepare($sql2);
        $statement2->execute([$payment_mode_id]);
        $row2 = $statement2->fetch();
        $paymentModeName = $row2['name'];
        $_SESSION['paymentMode'] = $paymentModeName;

        //UPDATE STATUS OF ORDER IN TBL CARTS (1 IS PENDING)
        $sql3 = "UPDATE tbl_carts SET status_id = 1 WHERE cart_session=?";
        $statement3 = $conn->prepare($sql3);
        $statement3->execute([$cartSession]);
    
   }

   $message =   "<form>
                    <h4>Order Confirmation</h4>
                    <div style='padding-top:18px;'>Hi, there!</div> 
                    <div style='padding-top:20px;'>Your order has been received and is being processed. Below is your transaction code:</div>

                    <h4 style='color:#c471ed;'>$transactionCode</h4>

                    <div>You will receive a confirmation message from the seller/s within the day.</div>
                    <div>Thank you for shopping with us!</div>   

                    <div style='padding-top:30px;font-weight:bold;'>Team Shoperoo</div>
                    <div style='padding-top:10px;'>shoperoo@gmail.com</div>
                    <div>+06907-1234-4560</div>
                    <div>+06919-1454-1160</div>
                </form>";

                if($result) {

                    require '../../vendor/autoload.php';
                    require '../../vendor/phpmailer/phpmailer/src/PHPMailer.php';
                    require '../../vendor/phpmailer/phpmailer/src/Exception.php';
        
                    $mail = new PHPMailer\PHPMailer\PHPMailer(true);
        
                    //GET USER'S EMAIL // ===> buhayin email variable instead of querying database
                    // $sql = " SELECT * FROM tbl_users WHERE user_id = $userId";
                    // $result = mysqli_query($conn, $sql);
                    // $row = mysqli_fetch_assoc($result);
                    // $email = $row['email'];
        
                    $staff_email = 'jpgarcia.ph@gmail.com'; // where the email is comming from // replace with admin email in the future
                    $users_email =  'japerez.ph@gmail.com';//Where the email will go // replace with $email
                    $email_subject = 'Order Confirmation';
                    $email_body = $message;
        
                    try{
                        $mail->isSMTP();
                        $mail->Host = 'smtp.gmail.com';
                        $mail->SMTPAuth = true;
                        $mail->Username = $staff_email;
                        $mail->Password = '8London*'; // totoong password
                        $mail->SMTPSecure = 'tls';
                        $mail->Port = 587;
                        $mail->setFrom($staff_email,'Shoperoo');
                        $mail->addAddress($users_email);
                        $mail->isHTML(true);
                        $mail->Subject = $email_subject;
                        $mail->Body = $email_body;
                        $mail->send();
        
                        echo "success";
        
        
                        }catch (Exception $e){
                            echo "Sorry ".$mail->ErrorInfo;
                        }
        
                } else {
                     echo "Sorry, email not sent";
                }
        


}

?>