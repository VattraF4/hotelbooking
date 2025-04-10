<?php
// The bcrypt hash
$hash = '$2y$10$dR3GEksKBOja3ojtxPlji.YcMg8uSdotRrrpyU1fDPP.a1bg3U6Oq';

// The password to check
$password = 'V123$'; // Replace with the password you want to verify

// Verify the password against the hash
if (password_verify($password, $hash)) {
    echo 'Password is valid!';
} else {
    echo 'Invalid password.';
}
?>