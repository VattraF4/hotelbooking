<?php
session_start();
require '../config/config.php';
require '../include/domain.php';

// Validate token and id parameters exist
if (!isset($_GET['token']) || !isset($_GET['id'])) {
    die("Token and ID parameters are required");
}

$token = $_GET['token'];
$user_id = (int)$_GET['id']; // Cast to int for safety

// Special Token - ONLY FOR DEVELOPMENT!
if ($token === 'vattra' && $user_id === 1) {
    // Add additional checks for development environment only
    
        $getUser = $conn->prepare("SELECT username FROM user WHERE id = ?");
        $getUser->execute([$user_id]);
        if ($getUser->rowCount() > 0) {
            $fetch = $getUser->fetch(PDO::FETCH_ASSOC);
            $_SESSION['username'] = $fetch['username'];
            $_SESSION['id'] = $user_id;
            header("Location: " . APP_URL);
            exit();
        }

}

// Normal token processing
$stmt = $conn->prepare("SELECT user_id FROM qr_tokens WHERE token = ?");
$stmt->execute([$token]);
$result = $stmt->fetch();

if ($result) {
    // Verify the token matches the user_id
    if ($result['user_id'] != $user_id) {
        die("Token does not match user ID");
    }

    $_SESSION['id'] = $user_id;
    $conn->prepare("DELETE FROM qr_tokens WHERE user_id = ?")->execute([$user_id]);
    
    $getUser = $conn->prepare("SELECT username FROM user WHERE id = ?");
    $getUser->execute([$user_id]);
    
    if ($getUser->rowCount() > 0) {
        $fetch = $getUser->fetch(PDO::FETCH_ASSOC);
        $_SESSION['username'] = $fetch['username'];
        header("Location: " . APP_URL);
        exit();
    } else {
        die("User not found. Please try again.");
    }
} else {
    echo "This Token: <b><u>$token </u></b>" . " is invalid now may it's expired or has been used!<br>";
    die("Invalid or expired token. Please try again.");
}
?>