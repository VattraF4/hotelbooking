<?php
// auth/qr-verify.php
require '../include/header.php';
require '../config/config.php';
// require '../include/domain.php';
if (!isset($_GET['token']) || !isset($_GET['id'])) {
    echo "Missing token or user ID.";
    exit;
}
    // 1. Get the token from the URL
    $token = $_GET['token'];
    $user_id = $_GET['id'];
    // 2. Check if the token exists and is not expired
    // $stmt = $conn->prepare("SELECT user_id FROM qr_tokens WHERE token = ? AND expires_at > NOW()");
    $stmt = $conn->prepare("SELECT user_id FROM qr_tokens WHERE token = ? ");
    $stmt->execute([$token]);
    $result = $stmt->fetch();

    if ($result > 0 && $result['user_id'] == $user_id) {

        // 3. Log the user in (no password needed)
        $_SESSION['id'] = $user_id;

        $getUser = $conn->prepare("SELECT username FROM user WHERE id = ?");
        $getUser->execute([$user_id]);
        if ($getUser->rowCount() > 0) {
            $fetch = $getUser->fetch(PDO::FETCH_ASSOC);
            $_SESSION['username'] = $fetch['username'];
        } else {
            die("User not found. Please try again.");
        }
        // 4. Delete the used token
        $conn->prepare("DELETE FROM qr_tokens WHERE user_id = ?")->execute([$user_id]);
        echo "<a href='" . APP_URL . "'>Go to Dashboard</a>";
        // 5. Redirect to dashboard
        // header("Location: " . APP_URL);
        // exit();
    } else {
        echo "Result not found! check user id or token";
    }
// } else {
//     echo "This Token: <b><u>$_GET[token] </u></b>" . " is invalid now may it's expired or has been used!<br>";
// }

?>