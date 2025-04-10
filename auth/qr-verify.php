<?php
// auth/qr-verify.php
require '../include/header.php';
require '../config/config.php';

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Set timezone (consistent with qr-login.php)
if (isset($_COOKIE['user_timezone'])) {
    date_default_timezone_set($_COOKIE['user_timezone']);
} else {
    date_default_timezone_set('Asia/Phnom_Penh');
}

// 1. Validate required parameters
if (!isset($_GET['token']) || !isset($_GET['id'])) {
    die(json_encode([
        'status' => 'error',
        'message' => 'Missing token or user ID',
        'redirect' => APP_URL . 'auth/login.php'
    ]));
}

$token = trim($_GET['token']);
$user_id = (int)$_GET['id'];

// Debug output
error_log("QR Verification Attempt - Token: $token, User ID: $user_id");

// 2. Validate token format
if (!preg_match('/^[a-f0-9]{64}$/i', $token)) {
    die(json_encode([
        'status' => 'error',
        'message' => 'Invalid token format',
        'redirect' => APP_URL . 'auth/login.php'
    ]));
}

try {
    // 3. Check if the token exists and is not expired
    $stmt = $conn->prepare("SELECT user_id, expires_at FROM qr_tokens WHERE token = ?");
    $stmt->execute([$token]);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$result) {
        die(json_encode([
            'status' => 'error',
            'message' => 'Invalid or expired token',
            'redirect' => APP_URL . 'auth/login.php'
        ]));
    }

    // 4. Verify token belongs to this user and is not expired
    $current_time = (new DateTime())->format('Y-m-d H:i:s');
    
    if ($result['user_id'] != $user_id) {
        error_log("Token user mismatch: Token belongs to {$result['user_id']} but accessed by $user_id");
        die(json_encode([
            'status' => 'error',
            'message' => 'Token does not match user',
            'redirect' => APP_URL . 'auth/login.php'
        ]));
    }

    if ($result['expires_at'] < $current_time) {
        error_log("Expired token attempt: Token expired at {$result['expires_at']}, current time is $current_time");
        die(json_encode([
            'status' => 'error',
            'message' => 'This token has expired',
            'redirect' => APP_URL . 'auth/login.php'
        ]));
    }

    // 5. Get user details
    $user_stmt = $conn->prepare("SELECT id, username, email FROM user WHERE id = ?");
    $user_stmt->execute([$user_id]);
    $user = $user_stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user) {
        die(json_encode([
            'status' => 'error',
            'message' => 'User not found',
            'redirect' => APP_URL . 'auth/login.php'
        ]));
    }

    // 6. Log the user in
    $_SESSION['id'] = $user['id'];
    $_SESSION['username'] = $user['username'];
    $_SESSION['email'] = $user['email'];
    $_SESSION['qr_verified'] = true; // Additional verification flag

    // 7. Delete the used token
    $conn->prepare("DELETE FROM qr_tokens WHERE token = ?")->execute([$token]);
    
    // 8. Delete all expired tokens for cleanup
    $conn->prepare("DELETE FROM qr_tokens WHERE expires_at < NOW()")->execute();

    // 9. Return success response
    echo json_encode([
        'status' => 'success',
        'message' => 'Login successful',
        'redirect' => APP_URL
    ]);

    // Alternative for browser redirect if not using AJAX
    // header("Location: " . APP_URL);
    exit();

} catch (PDOException $e) {
    error_log("Database error during QR verification: " . $e->getMessage());
    die(json_encode([
        'status' => 'error',
        'message' => 'Database error occurred',
        'redirect' => APP_URL . 'auth/login.php'
    ]));
}

require '../include/footer.php';
?>