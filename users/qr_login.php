<?php require '../include/header.php'; ?>
<?php
// require '../include/domain.php';
if (!isset($_SERVER['HTTP_REFERER'])) {
    // redirect them to your desired location
    echo "<script>window.location.href='" . APP_URL . "auth/logout.php';</script>";
    exit;
}
?>
<?php

// In your PHP:
if (isset($_COOKIE['user_timezone'])) {
    date_default_timezone_set($_COOKIE['user_timezone']);
} else {
    // Fallback to server timezone
    date_default_timezone_set('Asia/Phnom_Penh');
}
?>

<?php
// qr-login.php
require '../config/config.php';
$user_id = $_SESSION['id'];
echo $user_id;
// 1. Generate a unique token (expires in 5 mins)
$token = bin2hex(random_bytes(32));

$expiresAt = (new DateTime())->add(new DateInterval('PT5M'))->format('Y-m-d H:i:s'); // 5-minute expiry

// 2. Store token + email in the database
$stmt = $conn->prepare("INSERT INTO qr_tokens (token, user_id, expires_at) VALUES (?, ?, ?)");
$stmt->execute([$token, $user_id, $expiresAt]); // Hardcoded email for demo

$getUser = $conn->prepare("SELECT * FROM user WHERE id = ?");
$getUser->execute([$user_id]);
$user = $getUser->fetch();

if (!$user) {
    echo "User not found";
    exit;
} else {
    $_SESSION['email'] = $user['email'];
    $_SESSION['username'] = $user['username'];
    $_SESSION['id'] = $user['id'];

}


// 3. Create a QR code that links to a login URL with the token
$loginUrl = APP_URL . "auth/qr-verify.php?token=$token&id=$user_id";
$qrCodeImg = "https://api.qrserver.com/v1/create-qr-code/?size=200x200&data=" . urlencode($loginUrl);

// 4. Display the QR code
// echo "<img src='$qrCodeImg' alt='Scan to Log In'>";
// echo "<p>Scan this QR code to log in automatically.</p>";

// 5. Display a link to the login page
// echo "<a href='$loginUrl'>Log In</a>";
// echo $expiresAt;
?>
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Scan to Log In</h3>
                </div>
                <div class="card-body row">
                    <div class="col-md-6">
                        <img src="<?php echo $qrCodeImg; ?>" alt="Scan to Log In" class="img-fluid">
                    </div>
                    <div class="col-md-6">
                        <br>
                        <p class="card-text">Scan this QR code to log in automatically.</p>
                        <p>This QR Code valid for 1 Device or once only!!</p>
                        <!-- <a href="<?php echo $loginUrl; ?>" class="btn btn-primary">Log In</a> -->
                        <div class="input-group ">
                            <input type="text" class="form-control" value="<?php echo $loginUrl; ?>" id="qr-login-url"
                                readonly>
                            <div class="input-group-append">
                                <button class="btn btn-primary" type="button"
                                    onclick="copyToClipboard('#qr-login-url')">
                                    Copy
                                </button>
                            </div>
                        </div>
                        <!-- Use script to copy -->
                        <script>
                            function copyToClipboard(element) {
                                var $temp = $("<input>");
                                $("body").append($temp);
                                $temp.val($(element).val()).select();
                                document.execCommand("copy");
                                $temp.remove();
                            }
                        </script>

                </div>
            </div>
            <div class="card-footer">
                <p class="card-text text-muted">Expires at: <?php echo $expiresAt; ?></p>
            </div>
        </div>
    </div>
</div>
</div>

<script>
    // Get user's timezone from browser
    const userTimezone = Intl.DateTimeFormat().resolvedOptions().timeZone;
    // Send to PHP via cookie or AJAX
    document.cookie = `user_timezone= ${userTimezone}; path=/`;
</script>
<?php require '../include/footer.php'; ?>