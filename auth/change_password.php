<?php
require "../include/header.php";
require "../config/config.php";
require "Mailer.php";

$error = "";
$email = "";
$step = isset($_POST['step']) ? $_POST['step'] : 1;

//Check DATA for debug
if(isset($_SESSION['username'])){
    echo "<script>console.log('".$user_id."')</script>";
    echo "<script>console.log('".$_SESSION['username']."')</script>";
}
// Handle logged-in users
if (isset($user_id)) {
    // echo "<script>console.log('".$user_id."')</script>";
    $stmt = $conn->prepare("SELECT email FROM user WHERE id = :user_id");
    $stmt->execute([':user_id' => $_SESSION['user_id']]);
    $user = $stmt->fetchAll(PDO::FETCH_OBJ);
    
    if ($user) {
        $_SESSION['reset_email'] = $user[0]->email;
        $step = 2; // Skip email step for logged-in users
    }
    // $_SESSION['reset_email'] = $_SESSION['email'];
}

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    switch ($step) {
        case 1:
            // Email submission
            if (empty($_POST['email'])) {
                $error = "Please enter your email";
            } else {
                $email = $_POST['email'];
                // Verify email exists
                $check = $conn->prepare("SELECT * FROM user WHERE email = :email");
                $check->execute([':email' => $email]);
                if ($check->rowCount() > 0) {
                    $_SESSION['reset_email'] = $email;
                    $step = 2; // Move to OTP verification form
                } else {
                    $error = "Email not found";
                }
            }
            break;

        case 2:
            // Handle OTP request
           
            // Handle OTP verification
            if (isset($_POST['verify_otp'])) {
                if (empty($_POST['otp'])) {
                    $error = "Please enter the OTP";
                } elseif (isset($_SESSION['reset_otp']) && $_POST['otp'] == $_SESSION['reset_otp']) {
                    $step = 3; // Move to password reset
                } else {
                    $error = "Invalid OTP";
                }
            } elseif (isset($_POST['request_otp'])) {
                if (sendPasswordResetOTP( $_SESSION['reset_email'])) {
                    $otp_sent = true;
                } else {
                    $error = "Error sending OTP";
                }
            }
            break;

        case 3:
            // Password reset
            if (empty($_POST['new_password']) || empty($_POST['confirm_password'])) {
                $error = "Please fill all fields";
            } elseif ($_POST['new_password'] != $_POST['confirm_password']) {
                $error = "Passwords don't match";
            } else {
                // Update password in database
                $hashed_password = password_hash($_POST['new_password'], PASSWORD_DEFAULT);
                $update = $conn->prepare("UPDATE user SET my_password = :password WHERE email = :email");
                $update->execute([
                    ':password' => $hashed_password,
                    ':email' => $_SESSION['reset_email']
                ]);

                // Clear session and show success
                unset($_SESSION['reset_otp']);
                unset($_SESSION['reset_email']);
                $success = "Password updated successfully!";
                echo "<script>console.log('".$success."')</script>";
            }
            break;
    }
}
?>

    <div class="hero-wrap js-fullheight" style="background-image: url('<?php echo APP_URL; ?>images/image_2.jpg');"
        data-stellar-background-ratio="0.5">
        <div class="overlay"></div>
        <div class="container">
            <div class="row no-gutters slider-text js-fullheight align-items-center justify-content-start"
                data-scrollax-parent="true">
                <div class="col-md-7 ftco-animate">
                    <!-- Optional content -->
                </div>
            </div>
        </div>
    </div>

    <?php if (isset($success)): ?>
        <section class="ftco-section ftco-book ftco-no-pt ftco-no-pb">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-md-6 mt-5">
                        <form action="" class="appointment-form">
                        <div class="alert alert-success">
                            <?php echo $success; ?>
                        </div>
                        <a href="login.php" class="btn btn-primary py-3 px-4">Login Now</a>
                        </form>
                    </div>
                </div>
            </div>
        </section>
    <?php else: ?>
        <section class="ftco-section ftco-book ftco-no-pt ftco-no-pb">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-md-6 mt-5">
                        <form action="change_password.php" method="post" class="appointment-form">
                            <input type="hidden" name="step" value="<?php echo $step; ?>">
                            
                            <?php switch ($step):
                                case 1: ?>
                                    <h3 class="mb-3">Reset Password</h3>
                                    <div class="form-group">
                                        <input type="email" name="email" class="form-control" placeholder="Email" required
                                               value="<?php echo htmlspecialchars($email); ?>">
                                    </div>
                                    <div class="form-group">
                                        <input type="submit" value="Next" class="btn btn-primary py-3 px-4">
                                    </div>
                                    <?php break;

                                case 2: ?>
                                    <h3 class="mb-3">Verify Your Email</h3>
                                    <p>We'll send an OTP to <b><?php echo htmlspecialchars($_SESSION['reset_email']); ?></b></p>
                                    
                                    <?php if (!isset($otp_sent)): ?>
                                        <div class="form-group">
                                            <button type="submit" name="request_otp" class="btn btn-primary py-3 px-4">
                                                Send OTP
                                            </button>
                                        </div>
                                    <?php else: ?>
                                        <div class="form-group">
                                            <input type="text" name="otp" class="form-control" placeholder="Enter OTP" required>
                                        </div>
                                        <div class="form-group">
                                            <button type="submit" name="verify_otp" class="btn btn-primary py-3 px-4">
                                                Verify OTP
                                            </button>
                                        </div>
                                    <?php endif; ?>
                                    <?php break;

                                case 3: ?>
                                    <h3 class="mb-3">Set New Password</h3>
                                    <div class="form-group">
                                        <input type="password" name="new_password" class="form-control" 
                                               placeholder="New Password" required>
                                    </div>
                                    <div class="form-group">
                                        <input type="password" name="confirm_password" class="form-control"
                                               placeholder="Confirm Password" required>
                                    </div>
                                    <div class="form-group">
                                        <input type="submit" value="Reset Password" class="btn btn-primary py-3 px-4">
                                    </div>
                                    <?php break;
                            endswitch; ?>
                            <label style="color: red; text-align: center;"><?php echo $error; ?></label>
                        </form>
                    </div>
                </div>
            </div>
        </section>
    <?php endif; ?>

    <?php require "../include/footer.php"; ?>
