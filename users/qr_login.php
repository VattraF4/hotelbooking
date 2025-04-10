// In your HTML/JavaScript:
<script>
    // Get user's timezone from browser
    const userTimezone = Intl.DateTimeFormat().resolvedOptions().timeZone;
    // Send to PHP via cookie or AJAX
    document.cookie = `user_timezone= ${userTimezone}; path=/`;
</script>

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
session_start();
require '../include/domain.php';
require '../config/config.php';

// 1. Generate a unique token (expires in 5 mins)
$token = bin2hex(random_bytes(32));

$expiresAt = (new DateTime())->add(new DateInterval('PT5M'))->format('Y-m-d H:i:s'); // 5-minute expiry

// 2. Store token + email in the database
$stmt = $conn->prepare("INSERT INTO qr_tokens (token, user_id, expires_at) VALUES (?, ?, ?)");
$stmt->execute([$token, 1, $expiresAt]); // Hardcoded email for demo


// 3. Create a QR code that links to a login URL with the token
$loginUrl = "ranavattra.com/auth/qr-verify.php?token=$token";
$qrCodeImg = "https://api.qrserver.com/v1/create-qr-code/?size=200x200&data=" . urlencode($loginUrl);

// 4. Display the QR code
echo "<img src='$qrCodeImg' alt='Scan to Log In'>";
echo "<p>Scan this QR code to log in automatically.</p>";

// 5. Display a link to the login page
echo "<a href='$loginUrl'>Log In</a>";
echo $expiresAt;
?>