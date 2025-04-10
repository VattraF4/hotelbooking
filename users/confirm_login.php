<?php
// confirm-login.php
session_start();
require '../config/config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    $token = $data['token'] ?? '';
    $userId = $data['user_id'] ?? '';

    // Check if token is valid and not expired
    $stmt = $conne->prepare("SELECT * FROM qr_tokens WHERE token = ? AND user_id = ? AND expires_at > NOW()");
    $stmt->execute([$token, $userId]);
    $qrToken = $stmt->fetch();

    if ($qrToken) {
        // Token is valid! Log the user in
        $_SESSION['user_id'] = $userId;
        
        // Delete the used token
        $db->prepare("DELETE FROM qr_tokens WHERE token = ?")->execute([$token]);
        
        echo json_encode(["success" => true]);
    } else {
        echo json_encode(["error" => "Invalid or expired token"]);
    }
}
?>