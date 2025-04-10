<?php
// auth/qr-verify.php
session_start();
require '../config/config.php';
require '../include/domain.php';

// 1. Get the token from the URL
$token = $_GET['token'] ?? '';
$user_id = $_GET['id'];
echo $token;
// 2. Check if the token exists and is not expired
$stmt = $conn->prepare("SELECT user_id FROM qr_tokens WHERE token = ? AND expires_at > NOW()");
$stmt->execute([$token]);
$result = $stmt->fetch();

if ($result) {    
    
    // 3. Log the user in (no password needed)
    $_SESSION['id'] = $user_id;
    
    // 4. Delete the used token
    $conn->prepare("DELETE FROM qr_tokens WHERE user_id = ?")->execute([$user_id]);
    $getUser = $conn->prepare("SELECT username FROM user WHERE id = ?");
    $getUser->execute([$user_id]);
    if ($getUser->rowCount() > 0) {
        $fetch = $getUser->fetch(PDO::FETCH_ASSOC);
        $_SESSION['username'] = $fetch['username'];
    } else {
        die("User not found. Please try again.");
    }
    // 5. Redirect to dashboard
    header("Location: ".APP_URL);
    exit();
} else {
    die("Invalid or expired token. Please try again.");
}

?>