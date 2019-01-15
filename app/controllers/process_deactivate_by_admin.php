<?php 
require_once '../../config.php';

if(isset($_SESSION['id'])){
    $userId = $_POST['userId'];
    $isSeller = $_POST['isSeller'];
    $status = $_POST['status'];
    $username = $_POST['userName'];

    // VERIFY IF USER IS SELLER & HAS AN EXISTING STORE
    if($isSeller == "yes" && $status == 2) {
    $sql = "SELECT * FROM tbl_users WHERE isSeller = 'yes' AND id = ?";
            $statement = $conn->prepare($sql);
            $statement->execute([$userId]);
            $count = $statement->rowCount();
            $row = $statement->fetch();
            $email = $row['email'];
            // $firstName = $row['first_name'];
            // $lastName = $row['last_name'];
            

            if($count){
                // CHANAGE STATUS FROM APPLYING FOR DEACTIVATION TO DEACTIVATED
               $sql2 =  "UPDATE tbl_users SET `status` = 0, first_name = 
                NULL, last_name = 
                NULL, profile_pic = 
                NULL, isSeller = 'no'
                WHERE id = ?";
                    $statement2 = $conn->prepare($sql2);
                    $statement2->execute([$userId]);
                
                // GET STORE ID
                $sql5 = "SELECT * FROM tbl_stores WHERE `user_id` = ?";
                $statement5 = $conn->prepare($sql5);
                $statement5->execute([$userId]);
                $row5 = $statement5->fetch();
                $storeId = $row5['id'];

                // DELETE STORE
                $sql3 =  "DELETE FROM tbl_stores WHERE `user_id` = ?  AND id = ?";
                    $statement3 = $conn->prepare($sql3);
                    $statement3->execute([$userId, $storeId]);

                //VERIFY 
                $sql4 = "SELECT * FROM tbl_stores WHERE `user_id` = ? AND id = ?";
                $statement4 = $conn->prepare($sql4);
                $statement4->execute([$userId, $storeId]);
                $count4 = $statement4->rowCount();
                if(!$count4){
                    //SEND EMAIL

                    $messageForClient =   
                    "<form>
                        <div style='padding-top:20px;'>As per your request, your Mamaroo account has been deactivated. Consequently, 
                        your store has been deleted from the website. You may reactivate your account by logging in with your email or username and password but, 
                        you'll have to go through the application process once again to be able to set up an online shop. 
                        We wish you well in your future endeavors.</div>   
                        <div style='padding-top:20px;'>Sincerely, </div>   
                        <div style='padding-top:30px;font-weight:bold;'>Team Mamaroo</div>
                        <div style='padding-top:10px;'>mamaroo@gmail.com</div>
                        <div>+06907-1234-4560</div>
                        <div>+06919-1454-1160</div>
                    </form>";

                    
                    $mail = new PHPMailer\PHPMailer\PHPMailer(true);
                    $staff_email = 'jpgarcia.ph@gmail.com'; // where the email is comming from // replace with admin email in the future
                    $users_email = $email;//Where the email will go // replace with $email
                    $email_subject = 'Mamaroo Account Deactivation & Store Deletion';
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
        
                        echo "success";
        
        
                        }catch (Exception $e){
                            echo "Buyer Side: Sorry".$mail->ErrorInfo;
                        }
                    }

                }

            } else {

                    echo "fail";

            }
    } else {
        echo "unauthorized";
    }


?>