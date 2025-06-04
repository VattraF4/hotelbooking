<?php require '../include/header.php'; ?>
<?php
if (!isset($_SERVER['HTTP_REFERER'])) {
    echo "<script>window.location.href='" . APP_URL . "auth/logout.php';</script>";
    exit;
}
?>

<?php
// Set timezone from cookie or default
if (isset($_COOKIE['user_timezone'])) {
    date_default_timezone_set($_COOKIE['user_timezone']);
} else {
    date_default_timezone_set('Asia/Phnom_Penh');
}
?>

<?php
require '../config/config.php';
$user_id = $_SESSION['user_id'];

// Generate a unique token (expires in 5 mins)
$token = bin2hex(random_bytes(32));
$expiresAt = (new DateTime())->add(new DateInterval('PT5M'))->format('Y-m-d H:i:s');

// Store token in database
$stmt = $conn->prepare("INSERT INTO qr_tokens (token, user_id, expires_at) VALUES (?, ?, ?)");
$result = $stmt->execute([$token, $user_id, $expiresAt]);

if (!$result) {
    die("Error: " . $stmt->errorInfo()[2]);
}

// Get user data
$getUser = $conn->prepare("SELECT * FROM user WHERE id = ?");
$getUser->execute([$user_id]);
$user = $getUser->fetch();

if (!$user) {
    die("User not found");
}

// Store user data in session
$_SESSION['email'] = $user['email'];
$_SESSION['username'] = $user['username'];
$_SESSION['id'] = $user['id'];

// Create QR code
$loginUrl = APP_URL . "auth/qr-verify.php?token=$token&id=$user_id";
$qrCodeImg = "https://api.qrserver.com/v1/create-qr-code/?size=300x300&data=" . urlencode($loginUrl);
?>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-primary text-white">
                    <h3 class="card-title mb-0 text-center">
                        <i class="fa fa-qrcode mr-2"></i>Secure QR Login
                    </h3>
                </div>
                
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-md-6 text-center mb-4 mb-md-0">
                            <div class="qr-container p-3 bg-light rounded">
                                <img src="<?php echo $qrCodeImg; ?>" alt="Scan to Log In" class="img-fluid">
                                <div class="expiry-timer mt-3">
                                    <div class="progress" style="height: 6px;">
                                        <div class="progress-bar bg-danger" id="countdown-bar" role="progressbar" style="width: 100%"></div>
                                    </div>
                                    <small class="text-muted" id="countdown-text">Expires in 5:00</small>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="qr-instructions">
                                <h5 class="text-primary mb-3">How to use:</h5>
                                <ol class="pl-3">
                                    <li class="mb-2">Open your mobile device</li>
                                    <li class="mb-2">Launch your QR scanner app</li>
                                    <li class="mb-2">Point your camera at this code</li>
                                    <li>You'll be logged in automatically</li>
                                </ol>
                                
                                <div class="alert alert-warning mt-4">
                                    <i class="fa fa-exclamation-triangle mr-2"></i>
                                    <strong>Important:</strong> This QR code is valid for one device only and will expire in 5 minutes.
                                </div>
                                
                                <div class="alternative-login mt-4">
                                    <p class="text-muted mb-2">Prefer to login manually?</p>
                                    <div class="input-group">
                                        <input type="text" class="form-control" value="<?php echo $loginUrl; ?>" id="qr-login-url" readonly>
                                        <div class="input-group-append">
                                            <button class="btn btn-outline-primary" type="button" id="copy-btn" data-toggle="tooltip" title="Copy to clipboard">
                                                <i class="far fa-copy"></i>
                                            </button>
                                        </div>
                                    </div>
                                    <small class="text-muted">Copy this link and open it on your mobile device</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="card-footer bg-light">
                    <div class="d-flex justify-content-between align-items-center">
                        <small class="text-muted">
                            <i class="far fa-clock mr-1"></i>
                            Expires at: <span id="expiry-time"><?php echo date('h:i A', strtotime($expiresAt)); ?></span>
                        </small>
                        <a href="<?php echo APP_URL; ?>auth/logout.php" class="btn btn-sm btn-outline-danger">
                            <i class="fa fa-sign-out-alt mr-1"></i> Cancel
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Set user timezone
const userTimezone = Intl.DateTimeFormat().resolvedOptions().timeZone;
document.cookie = `user_timezone=${userTimezone}; path=/`;

// Copy to clipboard function
$(document).ready(function() {
    $('#copy-btn').click(function() {
        const urlInput = document.getElementById('qr-login-url');
        urlInput.select();
        document.execCommand('copy');
        
        // Change button appearance temporarily
        $(this).html('<i class="fa fa-check"></i>');
        $(this).removeClass('btn-outline-primary').addClass('btn-success');
        
        setTimeout(() => {
            $(this).html('<i class="fa fa-copy"></i>');
            $(this).removeClass('btn-success').addClass('btn-outline-primary');
        }, 2000);
    });
    
    // Initialize tooltips
    $('[data-toggle="tooltip"]').tooltip();
    
    // Countdown timer
    let timeLeft = 300; // 5 minutes in seconds
    const countdownBar = document.getElementById('countdown-bar');
    const countdownText = document.getElementById('countdown-text');
    
    const countdown = setInterval(() => {
        timeLeft--;
        
        // Update progress bar
        const percentage = (timeLeft / 300) * 100;
        countdownBar.style.width = `${percentage}%`;
        
        // Update text
        const minutes = Math.floor(timeLeft / 60);
        const seconds = timeLeft % 60;
        countdownText.textContent = `Expires in ${minutes}:${seconds.toString().padStart(2, '0')}`;
        
        // Change color when under 1 minute
        if (timeLeft < 60) {
            countdownBar.classList.remove('bg-warning');
            countdownBar.classList.add('bg-danger');
        } else if (timeLeft < 120) {
            countdownBar.classList.remove('bg-primary');
            countdownBar.classList.add('bg-warning');
        }
        
        if (timeLeft <= 0) {
            clearInterval(countdown);
            countdownText.textContent = 'QR Code expired';
            countdownBar.style.width = '0%';
        }
    }, 1000);
});
</script>

<style>
.qr-container {
    border: 1px solid #dee2e6;
    max-width: 300px;
    margin: 0 auto;
    background: white;
}

.card {
    border-radius: 10px;
    overflow: hidden;
}

.card-header {
    padding: 1.25rem;
}

.card-body {
    padding: 2rem;
}

.progress {
    border-radius: 3px;
}

#copy-btn {
    transition: all 0.3s ease;
}

.qr-instructions li {
    padding-left: 0.5rem;
    line-height: 1.6;
}

@media (max-width: 767.98px) {
    .card-body {
        padding: 1.5rem;
    }
}
</style>

<?php require '../include/footer.php'; ?>