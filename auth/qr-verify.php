<?php
require '../config/config.php';
require '../include/domain.php';

// Enhanced error reporting
error_reporting(E_ALL);
ini_set('display_errors', 0); // Disable public display, log instead
ini_set('log_errors', 1);

// Set timezone consistency
date_default_timezone_set('Asia/Phnom_Penh');

// CORS and security headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");
header("X-Content-Type-Options: nosniff");
header("Cache-Control: no-store");

// Detailed debugging
error_log("\n==== QR VERIFICATION START ====");
error_log("Request Time: " . date('Y-m-d H:i:s'));
error_log("User Agent: " . ($_SERVER['HTTP_USER_AGENT'] ?? 'Unknown'));
error_log("IP Address: " . ($_SERVER['REMOTE_ADDR'] ?? 'Unknown'));

// Validate input parameters
if (empty($_GET['token']) || empty($_GET['id'])) {
    error_log("Missing parameters - Token: " . ($_GET['token'] ?? 'null') . ", ID: " . ($_GET['id'] ?? 'null'));
    http_response_code(400);
    die(json_encode([
        'status' => 'error',
        'message' => 'Missing token or user ID',
        'debug' => ['received_params' => $_GET]
    ]));
}

$token = trim($_GET['token']);
$user_id = (int)$_GET['id'];

// Validate token format
if (!preg_match('/^[a-f0-9]{64}$/i', $token)) {
    error_log("Invalid token format: $token");
    http_response_code(400);
    die(json_encode([
        'status' => 'error',
        'message' => 'Invalid token format',
        'debug' => ['token_length' => strlen($token)]
    ]));
}

try {
    // 1. First verify the token exists and is valid
    $stmt = $conn->prepare("
        SELECT user_id, expires_at, created_at 
        FROM qr_tokens 
        WHERE token = ? 
        AND user_id = ?
        AND used_at IS NULL
    ");
    $stmt->execute([$token, $user_id]);
    $token_data = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$token_data) {
        // Check if token exists at all (for debugging)
        $exists = $conn->prepare("SELECT 1 FROM qr_tokens WHERE token = ?")->execute([$token])->fetchColumn();
        
        error_log("Token verification failed. Exists: " . ($exists ? 'Yes' : 'No'));
        http_response_code(401);
        die(json_encode([
            'status' => 'error',
            'message' => 'Invalid or expired token',
            'debug' => [
                'token_exists' => (bool)$exists,
                'db_time' => $conn->query("SELECT NOW()")->fetchColumn()
            ]
        ]));
    }

    // 2. Check expiration (with 5 minute grace period)
    $current_time = new DateTime();
    $expires_at = new DateTime($token_data['expires_at']);
    
    if ($current_time > $expires_at) {
        $minutes_expired = round(($current_time->getTimestamp() - $expires_at->getTimestamp()) / 60, 2);
        
        error_log("Token expired. Created: {$token_data['created_at']}, Expired: {$token_data['expires_at']}, Current: " . $current_time->format('Y-m-d H:i:s'));
        http_response_code(401);
        die(json_encode([
            'status' => 'error',
            'message' => 'Token expired',
            'debug' => [
                'created_at' => $token_data['created_at'],
                'expires_at' => $token_data['expires_at'],
                'minutes_expired' => $minutes_expired,
                'current_time' => $current_time->format('Y-m-d H:i:s')
            ]
        ]));
    }

    // 3. Mark token as used
    $update = $conn->prepare("UPDATE qr_tokens SET used_at = NOW() WHERE token = ?");
    if (!$update->execute([$token])) {
        throw new PDOException("Failed to mark token as used");
    }

    // 4. Configure session for cross-device access
    $domain = parse_url(APP_URL, PHP_URL_HOST);
    session_set_cookie_params([
        'lifetime' => 86400, // 1 day
        'path' => '/',
        'domain' => $domain,
        'secure' => true,
        'httponly' => true,
        'samesite' => 'None'
    ]);
    
    session_start();
    
    // Regenerate session ID for security
    session_regenerate_id(true);

    // 5. Set session data
    $_SESSION = [
        'id' => $user_id,
        'username' => $conn->query("SELECT username FROM user WHERE id = $user_id")->fetchColumn(),
        'email' => $conn->query("SELECT email FROM user WHERE id = $user_id")->fetchColumn(),
        'qr_verified' => true,
        'login_time' => time(),
        'user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? 'Unknown'
    ];

    // 6. Return success response
    error_log("QR login successful for user $user_id");
    echo json_encode([
        'status' => 'success',
        'message' => 'Login successful',
        'redirect' => APP_URL,
        'session_info' => [
            'id' => session_id(),
            'expires' => date('Y-m-d H:i:s', time() + 86400)
        ]
    ]);

} catch (PDOException $e) {
    error_log("Database Error: " . $e->getMessage() . "\n" . $e->getTraceAsString());
    http_response_code(500);
    die(json_encode([
        'status' => 'error',
        'message' => 'Database error occurred',
        'debug' => ['error' => $e->getMessage()]
    ]));
} catch (Exception $e) {
    error_log("General Error: " . $e->getMessage());
    http_response_code(500);
    die(json_encode([
        'status' => 'error',
        'message' => 'Unexpected error occurred',
        'debug' => ['error' => $e->getMessage()]
    ]));
}

error_log("==== QR VERIFICATION END ====\n");
?>