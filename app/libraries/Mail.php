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
            $mail->Subject = 'Your OTP for My Bus Ticket';
            $mail->isHTML(true);

            $mail->Body = '
            <!DOCTYPE html>
            <html>
            <head>
                <meta charset="UTF-8">
                <meta name="viewport" content="width=device-width, initial-scale=1.0">
                <title>My Bus Ticket OTP</title>
                <style>
                    body {
                        font-family: Arial, sans-serif;
                        background-color: #f7f7f7;
                        margin: 0;
                        padding: 0;
                    }
                    .container {
                        max-width: 600px;
                        margin: 20px auto;
                        background-color: #ffffff;
                        padding: 20px;
                        border-radius: 8px;
                        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
                    }
                    .header {
                        text-align: center;
                        color: #2E86C1;
                    }
                    .otp-code {
                        font-size: 28px;
                        font-weight: bold;
                        color: #ffffff;
                        background-color: #2E86C1;
                        padding: 15px 25px;
                        border-radius: 5px;
                        display: inline-block;
                        margin: 20px 0;
                        letter-spacing: 3px;
                    }
                    .content p {
                        font-size: 16px;
                        color: #333333;
                        line-height: 1.5;
                    }
                    .footer {
                        font-size: 12px;
                        color: #777777;
                        text-align: center;
                        margin-top: 30px;
                    }
                    @media only screen and (max-width: 480px) {
                        .container {
                            padding: 15px;
                        }
                        .otp-code {
                            font-size: 24px;
                            padding: 12px 20px;
                        }
                    }
                </style>
            </head>
            <body>
                <div class="container">
                    <h2 class="header">My Bus Ticket</h2>
                    <div class="content">
                        <p>Dear User,</p>
                        <p>Use the following <strong>OTP code</strong> to reset your password:</p>
                        <div class="otp-code">' . $otp . '</div>
                        <p>If you did not request a password reset, please ignore this email.</p>
                    </div>
                    <div class="footer">
                        &copy; ' . date('Y') . ' My Bus Ticket. All rights reserved.
                    </div>
                </div>
            </body>
            </html>
            ';

            $mail->AltBody = "Your OTP for My Bus Ticket: $otp. This OTP is valid for 10 minutes. If you did not request a password reset, please ignore this email.";



            $success = $mail->send();
            //echo 'Message has been sent';
        } catch (Exception $e) {
            //echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
        }

    }

}




?>