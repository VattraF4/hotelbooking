<?php
// login.php
session_start();
require '../config/config.php'; // Your database connection
require '../include/domain.php';

// Generate a unique token (expires in 2 minutes)
$token = bin2hex(random_bytes(32));
$expiresAt = date('Y-m-d H:i:s', time() + 5*60); // 5 minutes expiry
$user_id = 1;

// Store in the database (linked to the user who will log in)
$stmt = $conn->prepare("INSERT INTO qr_tokens (token, user_id, expires_at) VALUES (:token, :user_id, :expires_at)");
$stmt->execute([
    ':token' => $token,
    ':user_id' => $user_id,
    ':expires_at' => $expiresAt
]);

// Generate QR code URL
$qrUrl = "https://APP_URL/auth/confirm-login?token=$token";
$qrCodeImage = "https://api.qrserver.com/v1/create-qr-code/?size=200x200&data=" . urlencode($qrUrl);

// Display QR code to the user
echo "<img src='$qrCodeImage' alt='Scan to Login'>";
echo urlencode($qrUrl);

?>