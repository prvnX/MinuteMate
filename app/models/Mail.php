<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
require __DIR__ . '/../../vendor/autoload.php';
    Class Mail{
        protected $mail;
        public function __construct(){
            $this->configureMail();
        }

        private function configureMail(){     
                $mail = new PHPMailer(true);
                $this->mail=$mail;
        }

        private function configureSMTP(){
            $this->mail->isSMTP();
            $this->mail->Host = 'smtp.gmail.com'; // SMTP server
            $this->mail->SMTPAuth = true;
            $this->mail->Username = 'minutemate111@gmail.com'; 
            $this->mail->Password = ''; // app password
            $this->mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $this->mail->Port = 587;
        }

        public function forwardMinuteContent($depmail,$depname,$contentTitle,$content,$minuteID,$meetingDate,$secname,$meetingType,$depheademail,$depheadname){
            try{
                $this->configureSMTP();
                $this->mail->setFrom('minutemate111@gmail.com', 'MinuteMate');
                $this->mail->addAddress($depmail, $depname); // Recipient
                $this->mail->Subject = 'Forwarded Minute Content';
                $this->mail->addCC($depheademail, $depheadname); //cc
                $this->mail->Body = '
                        <!DOCTYPE html>
                        <html lang="en">
                        <head>
                        <meta charset="UTF-8">
                        <meta http-equiv="X-UA-Compatible" content="IE=edge">
                        <meta name="viewport" content="width=device-width, initial-scale=1.0">
                        <title>Forwarded Minute Content</title>
                        <style>
                            body {
                            font-family: Arial, sans-serif;
                            font-size: 16px;
                            color: #333;
                            background-color: #f9f9f9;
                            padding: 20px;
                            text-align: center;
                            }
                            .email-container {
                            max-width: 600px;
                            margin: 0 auto;
                            background: #fff;
                            padding: 20px;
                            border-radius: 8px;
                            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
                            }
                            .logo img {
                            max-width: 150px;
                            margin-bottom: 20px;
                            }
                            h1 {
                            color: #004085;
                            font-size: 22px;
                            margin-bottom: 10px;
                            }
                            .content-box {
                            background: #f1f1f1;
                            padding: 15px;
                            border-left: 4px solid #004085;
                            text-align: left;
                            margin: 20px 0;
                            border-radius: 5px;
                            }
                            .footer {
                            font-size: 14px;
                            color: #777;
                            margin-top: 20px;
                            border-top: 1px solid #ddd;
                            padding-top: 10px;
                            }
                        </style>
                        </head>
                        <body>
                        <div class="email-container">
                            <div class="logo">
                            <img src="https://res.cloudinary.com/dbleqzcp4/image/upload/v1740161700/img_pjejlg.png" alt="MinuteMate Logo">
                            </div>
                            <h1>Decissions taken on '.$meetingDate.'</h1>
                            <p>Please refer to the decisions taken regarding your department in the ' . $meetingType . ' meeting on ' . $meetingDate . ' below:</p>
                            
                            <div class="content-box">
                            <h2>' . $contentTitle . '</h2>
                            <p>' . $content . '</p>
                            <p> ' . $secname . '<br>Secretary, <br>' . $meetingType . ' Meeting</p>
                            </div>
                            <p>For more details, please refer to the full minute document with ID: ' . $minuteID . '</p>
                            <p>Thank you.</p>
                            
                            <p class="footer">This is an automated email. Please do not reply.</p>
                        </div>
                        </body>
                        </html>';
                $this->mail->AltBody = 'Forwarded Minute Content';
               
                $this->mail->send();
                
                return true;
        }
        catch (Exception $e) {
        return "Email could not be sent. Error: {$this->mail->ErrorInfo}";
    }
}
public function sendMail(){

}

public function sendAcceptanceEmail($recipientEmail, $fullName) {
    try {
        $this->configureSMTP();
        $this->mail->setFrom('minutemate111@gmail.com', 'MinuteMate');
        $this->mail->addAddress($recipientEmail, $fullName);
        $this->mail->Subject = 'Account Request Accepted';

        $this->mail->Body = '
        <!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <style>
                body {
                    font-family: Arial, sans-serif;
                    background-color: #f9f9f9;
                    padding: 20px;
                    color: #333;
                }
                .email-container {
                    background-color: #ffffff;
                    padding: 20px;
                    border-radius: 8px;
                    max-width: 600px;
                    margin: auto;
                    box-shadow: 0 4px 10px rgba(0,0,0,0.1);
                }
                .logo img {
                    max-width: 150px;
                    margin-bottom: 20px;
                }
                h1 {
                    color: #28a745;
                    font-size: 22px;
                }
                p {
                    font-size: 16px;
                    line-height: 1.6;
                }
                .footer {
                    font-size: 14px;
                    color: #777;
                    margin-top: 20px;
                    border-top: 1px solid #ddd;
                    padding-top: 10px;
                }
            </style>
        </head>
        <body>
            <div class="email-container">
                <div class="logo">
                    <img src="https://res.cloudinary.com/dbleqzcp4/image/upload/v1740161700/img_pjejlg.png" alt="MinuteMate Logo">
                </div>
                <h1>Welcome to MinuteMate!</h1>
                <p>Hi ' . $fullName . ',</p>
                <p>Your request to join <strong>MinuteMate</strong> has been accepted! You can now log in using your Lecturer/Student ID as your username and your NIC as your password.</p>
                <p>We’re excited to have you on board.</p>
                <p>Best regards,<br>The MinuteMate Team</p>
                <p class="footer">This is an automated email. Please do not reply.</p>
            </div>
        </body>
        </html>';

        $this->mail->AltBody = "Hi $fullName,\n\nYour request to join MinuteMate has been accepted. You can now log in using your Lecturer/Student ID as your username and your NIC as your password.\n\nBest regards,\nMinuteMate Team";

        $this->mail->send();
    } catch (Exception $e) {
        // log error if needed
    }
}


public function sendDeclineEmail($recipientEmail, $fullName, $reason) {
    try {
        $this->configureSMTP();
        $this->mail->setFrom('minutemate111@gmail.com', 'MinuteMate');
        $this->mail->addAddress($recipientEmail, $fullName);
        $this->mail->Subject = 'Account Request Declined';

        $this->mail->Body = '
        <!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <style>
                body {
                    font-family: Arial, sans-serif;
                    background-color: #f9f9f9;
                    padding: 20px;
                    color: #333;
                }
                .email-container {
                    background-color: #ffffff;
                    padding: 20px;
                    border-radius: 8px;
                    max-width: 600px;
                    margin: auto;
                    box-shadow: 0 4px 10px rgba(0,0,0,0.1);
                }
                .logo img {
                    max-width: 150px;
                    margin-bottom: 20px;
                }
                h1 {
                    color: #dc3545;
                    font-size: 22px;
                }
                p {
                    font-size: 16px;
                    line-height: 1.6;
                }
                .reason {
                    background: #f8d7da;
                    border-left: 4px solid #dc3545;
                    padding: 10px;
                    border-radius: 5px;
                    margin: 15px 0;
                }
                .footer {
                    font-size: 14px;
                    color: #777;
                    margin-top: 20px;
                    border-top: 1px solid #ddd;
                    padding-top: 10px;
                }
            </style>
        </head>
        <body>
            <div class="email-container">
                <div class="logo">
                    <img src="https://res.cloudinary.com/dbleqzcp4/image/upload/v1740161700/img_pjejlg.png" alt="MinuteMate Logo">
                </div>
                <h1>Request Declined</h1>
                <p>Hi ' . $fullName . ',</p>
                <p>We regret to inform you that your request to join <strong>MinuteMate</strong> has been declined.</p>
                <div class="reason">
                    <strong>Reason:</strong> ' . $reason . '
                </div>
                <p>If you believe this was a mistake, feel free to contact the administrator for clarification.</p>
                <p>Best regards,<br>The MinuteMate Team</p>
                <p class="footer">This is an automated email. Please do not reply.</p>
            </div>
        </body>
        </html>';

        $this->mail->AltBody = "Hi $fullName,\n\nYour request to join MinuteMate was declined.\nReason: $reason\n\nPlease contact the admin if you believe this was a mistake.";

        $this->mail->send();
    } catch (Exception $e) {
        // log error if needed
    }
}



}
    
        
