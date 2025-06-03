<?php
require "../include/header.php";
require "../config/config.php";
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require '../vendor/autoload.php';

// Generate OTP
$otp = mt_rand(100000, 999999);

// Store OTP and registration data in session (with security improvements)
$_SESSION['otp'] = $otp;
$_SESSION['otp_generated_time'] = time(); // Store generation time for expiration check
$_SESSION['reg_data'] = [
    'username' => htmlspecialchars('Vattra', ENT_QUOTES, 'UTF-8'),
    'email' => filter_var('ravattrasmartboy@gmail.com', FILTER_SANITIZE_EMAIL),
    'password' => '', // Never store plain text passwords, even in session
    'phone' => preg_replace('/[^0-9]/', '', '0975361899')
];

// Log OTP to file (consider using a database instead for security)
file_put_contents('register.txt', $_SESSION['reg_data']['email'] . "\n" . $otp . "\n", FILE_APPEND | LOCK_EX);

// Send OTP via email
$mail = new PHPMailer(true);
try {
    // Server settings
    $mail->isSMTP();
    $mail->Host = 'mail.ranavattra.com';
    $mail->SMTPAuth = true;
    $mail->Username = 'ra.vattra.official@ranavattra.com';
    $mail->Password = 'v$Is$0f7s4aC';
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port = 587;
    $mail->CharSet = 'UTF-8';

    // Recipients
    $mail->setFrom('ra.vattra.official@ranavattra.com', 'Vattra\'s Hotel');
    $mail->addAddress($_SESSION['reg_data']['email']);

    // Email content with improved responsive design
    $mail->isHTML(true);
    $mail->Subject = 'Your OTP for Registration - Vattra\'s Hotel';
    
    $mail->Body = <<<HTML
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>OTP Verification</title>
    <style type="text/css">
        /* Base styles */
        body, html {
            margin: 0;
            padding: 0;
            font-family: 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
            line-height: 1.6;
            color: #333333;
            background-color: #f7fafc;
        }
        
        /* Email container */
        .email-container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        
        /* Card styling */
        .email-card {
            background-color: #ffffff;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
            overflow: hidden;
            border: 1px solid #e2e8f0;
        }
        
        /* Header */
        .email-header {
            background-color: #4f46e5;
            padding: 25px;
            text-align: center;
            color: white;
        }
        
        .email-logo {
            font-size: 24px;
            font-weight: 700;
            margin-bottom: 10px;
        }
        
        /* Content */
        .email-content {
            padding: 30px;
        }
        
        .otp-container {
            margin: 25px 0;
            text-align: center;
        }
        
        .otp-code {
            display: inline-block;
            font-size: 32px;
            font-weight: 700;
            letter-spacing: 3px;
            color: #4f46e5;
            background-color: #f0f0ff;
            padding: 15px 25px;
            border-radius: 8px;
            margin: 15px 0;
        }
        
        /* Warning box */
        .warning-box {
            background-color: #fff5f5;
            border-left: 4px solid #f56565;
            padding: 15px;
            margin: 25px 0;
            border-radius: 0 8px 8px 0;
        }
        
        .warning-icon {
            color: #f56565;
            margin-right: 8px;
        }
        
        /* Footer */
        .email-footer {
            padding: 20px;
            text-align: center;
            color: #718096;
            font-size: 14px;
            border-top: 1px solid #e2e8f0;
        }
        
        /* Responsive adjustments */
        @media only screen and (max-width: 600px) {
            .email-content {
                padding: 20px;
            }
            
            .otp-code {
                font-size: 28px;
                padding: 12px 20px;
            }
        }
    </style>
</head>
<body>
    <div class="email-container">
        <div class="email-card">
            <div class="email-header">
                <div class="email-logo">Vattra's Hotel</div>
                <h2 style="margin: 0; font-weight: 600;">Account Verification</h2>
            </div>
            
            <div class="email-content">
                <p style="margin-bottom: 20px;">Hello {$_SESSION['reg_data']['username']},</p>
                
                <p>Please use the following One-Time Password (OTP) to verify your account:</p>
                
                <div class="otp-container">
                    <div class="otp-code">{$otp}</div>
                    <p style="color: #718096; font-size: 14px;">(Valid for 10 minutes)</p>
                </div>
                
                <div class="warning-box">
                    <span class="warning-icon">⚠️</span>
                    <strong>Security Alert:</strong> Never share this code with anyone. Our team will never ask for your OTP.
                </div>
                
                <p>If you didn't request this code, please ignore this email or contact our support team immediately.</p>
                
                <p style="margin-top: 30px;">Welcome to Vattra's Hotel!</p>
            </div>
            
            <div class="email-footer">
                <p>&copy; 2025 Vattra's Hotel. All rights reserved.</p>
                <p style="margin-top: 5px; font-size: 13px;">
                    <a href="https://ranavattra.com" style="color: #4f46e5; text-decoration: none;">Visit our website</a> | 
                    <a href="mailto:support@ranavattra.com" style="color: #4f46e5; text-decoration: none;">Contact Support</a>
                </p>
            </div>
        </div>
    </div>
</body>
</html>
HTML;

    $mail->AltBody = "Your Vattra's Hotel verification code is: {$otp}\n\nThis code is valid for 10 minutes. Do not share this code with anyone.\n\nIf you didn't request this, please contact support immediately.";

    $mail->send();
    $otp_sent = true;
} catch (Exception $e) {
    error_log("Mailer Error: " . $e->getMessage());
    echo "<script>alert('We couldn't send the verification email. Please try again later.');</script>";
}