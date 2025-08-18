<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

class Mail
{

    /*public function verifyMail($recipient_mail,$recipient_name,$token)
    {
        // Load Composer's autoloader
        require '../vendor/autoload.php';

        try {

            // Instantiation and passing `true` enables exceptions
            $mail = new PHPMailer(true);

            //Server settings
            $mail->SMTPDebug = false;                      // Enable verbose debug output
            $mail->isSMTP();                                            // Send using SMTP
            $mail->Host       = 'smtp.gmail.com';                    // Set the SMTP server to send through
            $mail->SMTPAuth   = true;                                   // Enable SMTP authentication
            $mail->Username   = 'kkpp42877@gmail.com';                     // SMTP username
            $mail->Password   = 'bvtlxdcjhikndauu';                               // SMTP password
            $mail->SMTPSecure = 'tls';         // Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` encouraged
            $mail->Port       = 587;                                    // TCP port to connect to, use 465 for `PHPMailer::ENCRYPTION_SMTPS` above

            //Recipients
            $mail->setFrom('kkpp42877@gmail.com', ' My Bus Ticket');  
            $mail->addAddress($recipient_mail,$recipient_name);     // Add a recipient

            // Content
            $mail->isHTML(true);                                  // Set email format to HTML
            $mail->Subject = 'Verify Mail';
            $mail->Body    = "<b> <a href='$token' target='_blank'> Click here </a></b> to verify your registration.";
            $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

            $success = $mail->send();
            //echo 'Message has been sent';
        } catch (Exception $e) {
            //echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
        }

    }*/

    public function sendOtp($recipient_mail,$otp)
    {
        // Load Composer's autoloader
        require '../vendor/autoload.php';

        try {

            // Instantiation and passing `true` enables exceptions
            $mail = new PHPMailer(true);

            //Server settings
            $mail->SMTPDebug = false;                      // Enable verbose debug output
            $mail->isSMTP();                                            // Send using SMTP
            $mail->Host       = 'smtp.gmail.com';                    // Set the SMTP server to send through
            $mail->SMTPAuth   = true;                                   // Enable SMTP authentication
            $mail->Username   = 'kkpp42877@gmail.com';                     // SMTP username
            $mail->Password   = 'bvtlxdcjhikndauu';                               // SMTP password
            $mail->SMTPSecure = 'tls';         // Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` encouraged
            $mail->Port       = 587;                                    // TCP port to connect to, use 465 for `PHPMailer::ENCRYPTION_SMTPS` above

            //Recipients
            $mail->setFrom('kkpp42877@gmail.com', 'My Bus Ticket.');  
            $mail->addAddress($recipient_mail);     // Add a recipient

            // Content
            $mail->isHTML(true);                                  // Set email format to HTML
            
            $mail->Subject = 'Verify Your Email - MyBusTicket';
            $mail->isHTML(true);

            $mail->Body = '
            <!DOCTYPE html>
            <html lang="en">
            <head>
                <meta charset="UTF-8">
                <title>Verify Your Email</title>
                <style>
                    body {
                        font-family: Arial, sans-serif;
                        background-color: #f4f4f4;
                        margin: 0;
                        padding: 0;
                    }
                    .email-container {
                        max-width: 600px;
                        margin: 40px auto;
                        background-color: #ffffff;
                        border-radius: 8px;
                        padding: 20px;
                        box-shadow: 0 4px 6px rgba(0,0,0,0.1);
                    }
                    .header {
                        text-align: center;
                        padding-bottom: 20px;
                        border-bottom: 1px solid #e0e0e0;
                    }
                    .header h1 {
                        color: #333333;
                    }
                    .content {
                        padding: 20px 0;
                        color: #555555;
                    }
                    .otp-code {
                        font-size: 24px;
                        font-weight: bold;
                        color: #007bff;
                        text-align: center;
                        margin: 20px 0;
                        letter-spacing: 4px;
                    }
                    .button {
                        display: block;
                        width: 200px;
                        margin: 20px auto;
                        padding: 12px;
                        text-align: center;
                        background-color: #007bff;
                        color: white;
                        text-decoration: none;
                        border-radius: 6px;
                        font-weight: bold;
                    }
                    .footer {
                        text-align: center;
                        font-size: 12px;
                        color: #999999;
                        padding-top: 20px;
                        border-top: 1px solid #e0e0e0;
                    }
                </style>
            </head>
            <body>
                <div class="email-container">
                    <div class="header">
                        <h1>MyBusTicket</h1>
                    </div>
                    <div class="content">
                        <p>Hi there,</p>
                        <p>Thank you for registering. Please verify your email using the code below:</p>
                        <div class="otp-code">' . $otp . '</div>
                        <a href="http://localhost/mvc-bus-ticket/pages/otp?otp=' . $otp . '" class="button">Verify Email</a>
                        <p>If you did not request this, please ignore this email.</p>
                    </div>
                    <div class="footer">
                        &copy; ' . date('Y') . ' MyBusTicket. All rights reserved.
                    </div>
                </div>
            </body>
            </html>
            ';

            $mail->AltBody = "Your OTP is: $otp. Visit https://yourwebsite.com/verify to complete verification.";



            $success = $mail->send();
            //echo 'Message has been sent';
        } catch (Exception $e) {
            //echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
        }

    }

}




?>