<?php
require '../include/header.php';
require '../config/config.php';

// Debugging setup
error_reporting(E_ALL);
ini_set('display_errors', 1);
date_default_timezone_set('Asia/Phnom_Penh');

// Validate input
if (!isset($_GET['token']) || !isset($_GET['id'])) {
    die(json_encode([
        'status' => 'error',
        'message' => 'Missing parameters',
        'redirect' => APP_URL . 'auth/login.php'
    ]));
}

$token = trim($_GET['token']);
$user_id = (int)$_GET['id'];

// Verify token format
if (!preg_match('/^[a-f0-9]{64}$/i', $token)) {
    die(json_encode([
        'status' => 'error',
        'message' => 'Invalid token format',
        'redirect' => APP_URL . 'auth/login.php'
    ]));
}

try {
    // Check token with 1-minute grace period
    $stmt = $conn->prepare("
        SELECT user_id, expires_at 
        FROM qr_tokens 
        WHERE token = ? 
        AND user_id = ?
        AND expires_at > DATE_SUB(NOW(), INTERVAL 1 MINUTE)
    ");
    $stmt->execute([$token, $user_id]);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$result) {
        // Additional debug for missing token
        $check_stmt = $conn->prepare("SELECT COUNT(*) FROM qr_tokens WHERE token = ?");
        $check_stmt->execute([$token]);
        $exists = $check_stmt->fetchColumn();
        
        error_log("Token exists: " . ($exists ? 'Yes' : 'No'));
        die(json_encode([
            'status' => 'error',
            'message' => 'Invalid or expired token',
            'redirect' => APP_URL . 'auth/login.php'
        ]));
    }

    // Get user data
    $user_stmt = $conn->prepare("SELECT * FROM user WHERE id = ?");
    $user_stmt->execute([$user_id]);
    $user = $user_stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user) {
        die(json_encode([
            'status' => 'error',
            'message' => 'User not found',
            'redirect' => APP_URL . 'auth/login.php'
        ]));
    }

    // Create session
    $_SESSION = [
        'id' => $user['id'],
        'username' => $user['username'],
        'email' => $user['email'],
        'qr_verified' => true,
        'last_login' => time()
    ];

    // Cleanup tokens
    $conn->prepare("DELETE FROM qr_tokens WHERE token = ?")->execute([$token]);
    $conn->prepare("DELETE FROM qr_tokens WHERE expires_at < NOW()")->execute();

    // Success
    echo json_encode([
        'status' => 'success',
        'message' => 'Login successful',
        'redirect' => APP_URL
    ]);
    exit;

} catch (PDOException $e) {
    error_log("Database Error: " . $e->getMessage());
    die(json_encode([
        'status' => 'error',
        'message' => 'System error occurred',
        'redirect' => APP_URL . 'auth/login.php'
    ]));
}

require '../include/footer.php';
?>