<?php
require 'vendor/autoload.php';
require 'db.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

function sendVerificationEmail($email, $name, $token) {
    $mail = new PHPMailer(true);
    
    try {
        // Server settings
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'jaredjerome16@gmail.com'; // Your Gmail
        $mail->Password   = 'kvup xcue roee gwiz';     // App Password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = 587;
        $mail->SMTPDebug  = 2; // Enable verbose debug output
        $mail->Debugoutput = function($str, $level) {
            error_log("SMTP: $str");
        };

        // Recipients
        $mail->setFrom('jaredjerome16@gmail.com', 'Coffee Brews');
        $mail->addAddress($email, $name);

        // Content
        $mail->isHTML(true);
        $mail->Subject = 'Verify Your Email Address';
        
        // In mailer.php
        $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http";
        $host = $_SERVER['HTTP_HOST'];
        $path = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
        $verificationUrl = "$protocol://$host$path/verify.php?token=$token";
        
        $mail->Body = "
            <h2>Welcome to Home Cafe!</h2>
            <p>Please click the link below to verify your email address:</p>
            <p><a href='$verificationUrl'>$verificationUrl</a></p>
            <p>This link will expire in 24 hours.</p>
        ";
        
        $mail->AltBody = "Verify your email: $verificationUrl";

        if (!$mail->send()) {
            throw new Exception('Mailer Error: ' . $mail->ErrorInfo);
        }
        
        error_log("Verification email sent to $email");
        return true;
    } catch (Exception $e) {
        error_log("Mailer Error: " . $e->getMessage());
        return false;
    }
}
?>