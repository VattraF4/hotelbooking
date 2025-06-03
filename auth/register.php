<?php
require "../include/header.php";
require "../config/config.php";
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require '../vendor/autoload.php';

if (isset($_SESSION['username'])) {
    echo "<script>window.location.href = '" . APP_URL . "';</script>";
}

// Handle OTP verification
if (isset($_POST['verify_otp'])) {
    session_start();
    if ($_POST['otp'] == $_SESSION['otp']) {
        // OTP verified, proceed with registration
        $username = $_SESSION['reg_data']['username'];
        $email = $_SESSION['reg_data']['email'];
        $password = $_SESSION['reg_data']['password'];
        $phone = $_SESSION['reg_data']['phone'];

        $insert = $conn->prepare("INSERT INTO user (username, email, phone, my_password) VALUES (:username, :email, :phone, :mypassword)");
        $insert->execute([
            ':username' => $username,
            ':email' => $email,
            ':phone' => $phone,
            ':mypassword' => $password,
        ]);

        // Clear session data
        unset($_SESSION['otp']);
        unset($_SESSION['reg_data']);

        // Redirect to login page
        echo "<script>window.location.href = '" . APP_URL . "auth/login.php';</script>";
        exit;
    } else {
        echo "<script>alert('Invalid OTP. Please try again.');</script>";
        echo "<script>window.location.href = '" . APP_URL . "auth/register.php';</script>";
        exit;
    }
}

// Handle initial registration form submission (send OTP)
if (isset($_POST['submit'])) {
    if (empty($_POST['username']) || empty($_POST['email']) || empty($_POST['password']) || empty($_POST['phone']) || empty($_POST['confirm_password'])) {
        echo "<script>alert('One or more inputs are empty')</script>";
    } else {
        if ($_POST['password'] !== $_POST['confirm_password']) {
            echo "<script>alert('Password does not match')</script>";
        } else {
            $username = $_POST['username'];
            $email = $_POST['email'];
            $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
            $phone = $_POST['phone'];

            // Check if email already exists
            $check = $conn->prepare("SELECT * FROM user WHERE email = :email");
            $check->execute([':email' => $email]);
            if ($check->rowCount() > 0) {
                echo "<script>alert('Email already exists. Please use a different email.')</script>";
            } else {
                require 'a.php';
                // Generate OTP
                $otp = mt_rand(100000, 999999);

                // Store OTP and registration data in session
                $_SESSION['otp'] = $otp;
                $_SESSION['reg_data'] = [
                    'username' => $username,
                    'email' => $email,
                    'password' => $password,
                    'phone' => $phone
                ];
                $file = fopen('register.txt', 'w');
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
                    $mail->Subject = 'Your OTP for Registration';
                    $mail->$mail->Body = <<<HTML
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

                    $mail->send();
                    $otp_sent = true;
                } catch (Exception $e) {
                    echo "<script>alert('Message could not be sent. Mailer Error: {$mail->ErrorInfo}');</script>";
                }
            }
        }
    }
}
// ?>

<div class="hero-wrap js-fullheight" style="background-image: url('<?php echo APP_URL; ?>images/image_2.jpg');"
    data-stellar-background-ratio="0.5">
    <div class="overlay"></div>
    <div class="container">
        <div class="row no-gutters slider-text js-fullheight align-items-center justify-content-start"
            data-scrollax-parent="true">
            <div class="col-md-7 ftco-animate"></div>
        </div>
    </div>
</div>

<section class="ftco-section ftco-book ftco-no-pt ftco-no-pb">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-6">
                <?php if (isset($otp_sent) && $otp_sent): ?>
                    <!-- OTP Verification Form -->
                    <div class="container mt-5">
                        <form action="register.php" method="post" class="appointment-form">
                            <h3 class="mb-3">Verify OTP</h3>
                            <p>We've sent a 6-digit OTP to your email
                                <b><?php echo htmlspecialchars($_POST['email']); ?></b>.
                                Please check your inbox.
                            </p>

                            <div class="form-group">
                                <input type="text" class="form-control" name="otp" placeholder="Enter OTP" required>
                            </div>

                            <div class="form-group">
                                <input type="submit" name="verify_otp" value="Verify OTP" class="btn btn-primary py-3 px-4">
                            </div>
                        </form>
                    </div>
                <?php else: ?>


                    <!-- Registration Form -->
                    <div class="container mt-5">
                        <form action="register.php" method="post" class="appointment-form">
                            <h3 class="mb-3">Register</h3>

                            <div class="form-group">
                                <input type="text" class="form-control" name="username" placeholder="Username" required>
                            </div>

                            <div class="form-group">
                                <input type="email" name="email" class="form-control" placeholder="Email" required>
                            </div>

                            <div class="form-group">
                                <input type="tel" name="phone" class="form-control" placeholder="Phone Number" required>
                            </div>

                            <div class="form-group">
                                <!-- <input type="password" name="password" class="form-control" placeholder="Password" required> -->
                                <div class="mb-3 password-container">
                                    <input type="password" class="form-control" id="password"
                                        aria-describedby="passwordFeedback" placeholder="Password" required name="password">
                                    <button type="button" class="password-toggle" id="togglePassword">
                                        <i class="bi bi-eye-slash" id="showPassword"></i>
                                    </button>

                                </div>
                            </div>

                            <div class="form-group">
                                <input type="password" name="confirm_password" class="form-control"
                                    placeholder="Confirm Password" required>
                            </div>

                            <div class="form-group">
                                <input type="submit" name="submit" value="Register" class="btn btn-primary py-3 px-4">
                            </div>

                            <div class="form-group">
                                <div class="strength-meter">
                                    <div class="strength-meter-fill" id="strengthMeter"></div>
                                </div>
                                <div id="passwordFeedback" class="invalid-feedback">
                                    Please enter a valid password.
                                    <!-- Enable Script and style -->
                                    <?php require "check_password.php"; ?>
                                </div>
                            </div>

                        </form>
                    </div>


                <?php endif; ?>
            </div>
        </div>
    </div>
</section>

<?php require "../include/footer.php"; ?>

<!-- Additional CSS for better responsiveness -->
<style>
    @media (max-width: 768px) {
        .hero-wrap {
            background-position: center center;
            background-size: cover;
        }

        .row.justify-content-center {
            margin-left: 0;
        }

        .appointment-form {
            padding: 30px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            background: white;
        }
    }
</style>