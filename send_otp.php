<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php'; // Make sure PHPMailer is installed via Composer

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['email'])) {
    $email = $_POST['email'];

    // Generate a 6-digit OTP
    $otp = mt_rand(100000, 999999);  // This generates a random 6-digit number

    // Store OTP in session (or a database for real-world apps)
    session_start();
    $_SESSION['otp'] = $otp;
    $_SESSION['email'] = $email;

    // PHPMailer setup
    $mail = new PHPMailer(true);
    try {
        // SMTP settings
        $mail->isSMTP();
        $mail->Host = 'mail.ranavattra.com';  // Your SMTP server
        $mail->SMTPAuth = true;
        $mail->Username = 'ra.vattra.official@ranavattra.com';  // Your email
        $mail->Password = 'v$Is$0f7s4aC';  // Your email password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        // Recipients
        $mail->setFrom('ra.vattra.official@ranavattra.com', 'Ra Vattra Official');
        $mail->addAddress($email);  // Send OTP to user’s email

        // Content
        $mail->isHTML(true);
        $mail->Subject = 'Your OTP for Registration';
        $mail->Body    = 'Your OTP for password  is: <b>' . $otp . '</b> Do not share this code with anyone'.'<br><b>Thanks!</b><br><b>Team Ra Vattra</b>';

        // Send the email
        $mail->send();
        echo 'OTP sent successfully to ' . $email . '. Please check your inbox.';
    } catch (Exception $e) {
        echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }
}
?>
<form method="POST" action="#">
    <label for="email">Enter your email for password recovery:</label>
    <input type="email" name="email" id="email" required>
    <button type="submit">Send OTP</button>
</form>
