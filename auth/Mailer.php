<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require '../vendor/autoload.php';
// require "../config/config.php";
function sendPasswordResetOTP( $email): bool
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
        $mail->Body = '<p>Your OTP for password reset is: <b style="font-size:18px;">' . $otp . '</b></p>
                       <p>Do not share this code with anyone</p>
                       <p><b>Thanks!</b><br><b>Team Ra Vattra</b></p>';

        return $mail->send();
    } catch (Exception $e) {
        error_log("Mailer Error: " . $e->getMessage());
        return false;
    }
}
function sendEmail( $email , $subject, $message): bool
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