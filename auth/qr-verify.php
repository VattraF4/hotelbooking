<?php
require '../config/config.php';
require '../include/domain.php';

// Enable CORS and secure headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");
header("X-Content-Type-Options: nosniff");

// Debugging
error_log("QR Verification Request from: " . $_SERVER['HTTP_USER_AGENT']);

// Validate input
if (empty($_GET['token']) || empty($_GET['id'])) {
    http_response_code(400);
    die(json_encode(['status' => 'error', 'message' => 'Missing parameters']));
}

$token = trim($_GET['token']);
$user_id = (int)$_GET['id'];

try {
    // Verify token (with 2 minute grace period)
    $stmt = $conn->prepare("
        SELECT user_id FROM qr_tokens 
        WHERE token = ? 
        AND user_id = ?
        AND expires_at > DATE_SUB(NOW(), INTERVAL 2 MINUTE)
        AND used_at IS NULL
    ");
    $stmt->execute([$token, $user_id]);
    
    if (!$stmt->fetch()) {
        error_log("Invalid token attempt: $token");
        http_response_code(401);
        die(json_encode(['status' => 'error', 'message' => 'Invalid or expired token']));
    }

    // Mark token as used
    $conn->prepare("UPDATE qr_tokens SET used_at = NOW() WHERE token = ?")->execute([$token]);

    // Start session with cross-device support
    session_start([
        'cookie_lifetime' => 86400,
        'cookie_secure' => true,
        'cookie_httponly' => true,
        'cookie_samesite' => 'None',
        'cookie_domain' => '.' . parse_url(APP_URL, PHP_URL_HOST),
    ]);

    // Set session data
    $_SESSION = [
        'id' => $user_id,
        'qr_verified' => true,
        'user_agent' => $_SERVER['HTTP_USER_AGENT'],
        'ip_address' => $_SERVER['REMOTE_ADDR']
    ];

    // Return success
    echo json_encode([
        'status' => 'success',
        'message' => 'Login successful',
        'session_id' => session_id(),
        'redirect' => APP_URL
    ]);

} catch (PDOException $e) {
    error_log("Database error: " . $e->getMessage());
    http_response_code(500);
    die(json_encode(['status' => 'error', 'message' => 'System error']));
}