<?php
$loginUrl = "https://ranavattra.com/hotelbooking";
$qrCodeImg = "https://api.qrserver.com/v1/create-qr-code/?size=200x200&data=" . urlencode($loginUrl);
echo "<img src='$qrCodeImg' alt='Scan to Log In'>";
echo "<p>Scan this QR code to log in automatically.</p>";
echo "<a href='$loginUrl'>Log In</a>";
// echo $expiresAt;
?>