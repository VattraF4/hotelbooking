<?php 
require '../include/header.php';

// Check referrer security
if (!isset($_SERVER['HTTP_REFERER'])) {
    echo "<script>window.location.href='" . APP_URL . "auth/logout.php';</script>";
    exit;
}

// Set timezone
if (isset($_COOKIE['user_timezone'])) {
    date_default_timezone_set($_COOKIE['user_timezone']);
} else {
    date_default_timezone_set('Asia/Phnom_Penh');
}

require '../config/config.php';

// Verify session
if (!isset($_SESSION['id'])) {
    header("Location: " . APP_URL . "auth/login.php");
    exit;
}

$user_id = $_SESSION['id'];

// 1. Generate a unique token (expires in 5 mins)
$token = bin2hex(random_bytes(32));
$expiresAt = (new DateTime())->add(new DateInterval('PT5M'))->format('Y-m-d H:i:s');

// 2. Store token in database - THIS IS THE CORRECT QUERY FOR QR-LOGIN.PHP
$stmt = $conn->prepare("INSERT INTO qr_tokens (token, user_id, expires_at) VALUES (?, ?, ?)");
if (!$stmt->execute([$token, $user_id, $expiresAt])) {
    error_log("Failed to store QR token: " . print_r($stmt->errorInfo(), true));
    die("Failed to generate login token. Please try again.");
}

// 3. Get user data
$user = $conn->prepare("SELECT * FROM user WHERE id = ?")->execute([$user_id])->fetch();
if (!$user) {
    die("User not found");
}

// 4. Create QR code URL
$loginUrl = rtrim(APP_URL, '/') . "/auth/qr-verify.php?" . http_build_query([
    'token' => $token,
    'id' => $user_id
]);

$qrCodeImg = "https://api.qrserver.com/v1/create-qr-code/?" . http_build_query([
    'size' => '300x300',
    'data' => $loginUrl,
    'format' => 'png',
    'margin' => 10
]);

// Debug output
error_log("Generated QR Token: $token for user $user_id, expires at $expiresAt");
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
                        <img src="<?= htmlspecialchars($qrCodeImg) ?>" alt="Scan to Log In" class="img-fluid">
                    </div>
                    <div class="col-md-6">
                        <br>
                        <p class="card-text">Scan this QR code to log in automatically.</p>
                        <p>This QR Code valid for 1 Device or once only!!</p>
                        <div class="input-group">
                            <input type="text" class="form-control" value="<?= htmlspecialchars($loginUrl) ?>" id="qr-login-url" readonly>
                            <div class="input-group-append">
                                <button class="btn btn-primary" type="button" onclick="copyToClipboard('#qr-login-url')">
                                    Copy
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <p class="card-text text-muted">Expires at: <?= htmlspecialchars($expiresAt) ?></p>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Set timezone cookie
    const userTimezone = Intl.DateTimeFormat().resolvedOptions().timeZone;
    document.cookie = `user_timezone=${encodeURIComponent(userTimezone)}; path=/; max-age=86400`;
    
    function copyToClipboard(element) {
        const $temp = $("<input>");
        $("body").append($temp);
        $temp.val($(element).val()).select();
        document.execCommand("copy");
        $temp.remove();
        alert("URL copied to clipboard!");
    }
</script>

<?php require '../include/footer.php'; ?>