<?php
session_start();
require '../config/config.php';
require '../include/domain.php';

// Validate token and id parameters exist
//Get Data From "hotelbooking\users\qr_login.php"
if (!isset($_GET['token']) || !isset($_GET['id'])) {
    die("Token and ID parameters are required");
}

$token = $_GET['token'];
$user_id = (int) $_GET['id']; // Cast to int for safety

// Special Token - ONLY FOR DEVELOPMENT!
if ($token === 'vattra' && $user_id === 1) {
    // Set session variables

    $getUser = $conn->prepare("SELECT username FROM user WHERE id = ?");
    $getUser->execute([$user_id]);
    if ($getUser->rowCount() > 0) {
        $fetch = $getUser->fetchAll(PDO::FETCH_ASSOC);
        $_SESSION['username'] = $fetch[0]['username'];
        $_SESSION['id'] = $user_id;
        header("Location: " . APP_URL."auth/welcome.php");
        exit();
    }else{
        die("User not found. Please try again.");
    }
}

// Normal token processing
// $stmt = $conn->prepare("SELECT user_id FROM qr_tokens WHERE token = ? AND expires_at > NOW()");
$stmt = $conn->prepare("SELECT user_id FROM qr_tokens WHERE token = ?");
$stmt->execute([$token]);
$result = $stmt->fetch();

if ($result) {
    // Verify the token matches the user_id
    if ($result['user_id'] != $user_id) {
        die("Token does not match user ID"); 
    }

    // Get user first
    $getUser = $conn->prepare("SELECT username FROM user WHERE id = ?");
    $getUser->execute([$user_id]);

    if ($getUser->rowCount() > 0) {
        $fetch = $getUser->fetchAll(PDO::FETCH_ASSOC);

        // Set session variables
        $_SESSION['username'] = $fetch[0]['username'];
        $_SESSION['id'] = $user_id;

        // Only NOW delete the token (after successful login)
        $conn->prepare("DELETE FROM qr_tokens WHERE token = ?")->execute([$token]);

        // Redirect after all operations complete
        header("Location: " . APP_URL."auth/welcome.php");
        exit();
    } else {
        die("User not found. Please try again.");
    }
} else {
    echo "This Token: <b><u>$token </u></b>" . " is invalid now may it's expired or has been used!<br>";
    die("Invalid or expired token. Please try again.");
}
?>