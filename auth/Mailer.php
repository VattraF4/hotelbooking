<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require '../vendor/autoload.php';
// require "../config/config.php";
function sendPasswordResetOTP($email): bool
{
    // Generate OTP
    $otp = mt_rand(100000, 999999);

    // Store OTP in session
    $_SESSION['reset_otp'] = $otp;
    $_SESSION['reset_email'] = $email;
    // Store OTP and email to file
    $file = fopen('reset.txt', 'w');
    fwrite($file, $email . "\n" . $otp);
    fclose($file);

    // Send OTP via email
    $mail = new PHPMailer(true);
    try {
        // SMTP settings
        $mail->isSMTP();
        $mail->Host = 'mail.ranavattra.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'ra.vattra.official@ranavattra.com';
        $mail->Password = 'v$Is$0f7s4aC';
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        // Recipients
        $mail->setFrom('ra.vattra.official@ranavattra.com', 'Ra Vattra Official');
        $mail->addAddress($email);

        // Content
        $mail->isHTML(true);
        $mail->Subject = 'Your Password Reset OTP';
        $mail->Body = <<<HTML
                <!-- Email template -->
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
                                <div class="email-logo">Ra Vattra</div>
                                <h2 style="margin: 0; font-weight: 600;">OTP Verification</h2>
                            </div>
                            
                            <div class="email-content">
                                <p style="margin-bottom: 20px;">Hello,</p>
                                
                                <p>Please use the following One-Time Password (OTP) to complete your verification:</p>
                                
                                <div class="otp-container">
                                    <div class="otp-code">{$otp}</div>
                                    <p style="color: #718096; font-size: 14px;">(Valid for 10 minutes)</p>
                                </div>
                                
                                <div class="warning-box">
                                    <span class="warning-icon">⚠️</span>
                                    <strong>Security Alert:</strong> Never share this code with anyone. Our team will never ask for your OTP.
                                </div>
                                
                                <p>If you didn't request this code, please ignore this email or contact our support team immediately.</p>
                                
                                <p style="margin-top: 30px;">Thank you for choosing Ra Vattra!</p>
                            </div>
                            
                            <div class="email-footer">
                                <p>&copy; 2025 Ra Vattra. All rights reserved.</p>
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
        return $mail->send();
    } catch (Exception $e) {
        error_log("Mailer Error: " . $e->getMessage());
        return false;
    }
}
function sendEmail($email, $subject, $message): bool
{
    // Generate OTP
    $otp = mt_rand(100000, 999999);

    // Store OTP in session
    $_SESSION['reset_otp'] = $otp;
    $_SESSION['reset_email'] = $email;
    // Store OTP and email to file
    $file = fopen('reset.txt', 'w');
    fwrite($file, $email . "\n" . $otp);
    fclose($file);

    // Send OTP via email
    $mail = new PHPMailer(true);
    try {
        // SMTP settings
        $mail->isSMTP();
        $mail->Host = 'mail.ranavattra.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'ra.vattra.official@ranavattra.com';
        $mail->Password = 'v$Is$0f7s4aC';
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        // Recipients
        $mail->setFrom('ra.vattra.official@ranavattra.com', 'Ra Vattra Official');
        $mail->addAddress($email);

        // Content
        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body = $message;

        return $mail->send();
    } catch (Exception $e) {
        error_log("Mailer Error: " . $e->getMessage());
        return false;
    }
}
?>