<?php
require "../include/header.php";
require "../config/config.php";
?>

<?php
// Declare error
$error = '';
if (isset($_POST['submit'])) {
    if (empty($_POST['email']) || empty($_POST['password'])) {
        $error = 'Please fill all fields';
    } else {
        $email = $_POST['email'];
        $password = $_POST['password'];

        // Initialize attempts in session if not set
        if (!isset($_SESSION['attempts'])) {
            $_SESSION['attempts'] = 3;
        }

        // Check if account is locked
        if (isset($_SESSION['locked']) && $_SESSION['locked'] > time()) {
            $remaining = $_SESSION['locked'] - time();
            $error = 'Device locked. Please try again in ' . ceil($remaining / 60) . ' minutes.';
        } else {
            // Clear lock if time has passed
            unset($_SESSION['locked']);

            // Admin login check
            $adminLogin = $conn->prepare("SELECT * FROM admin WHERE email = :email");
            $adminLogin->bindParam(':email', $email);
            $adminLogin->execute();
            $adminFetch = $adminLogin->fetch(PDO::FETCH_OBJ);

            if ($adminFetch) {
                if (password_verify($password, $adminFetch->my_password)) {
                    $_SESSION['email'] = $adminFetch->email;
                    $_SESSION['id'] = $adminFetch->id;
                    $_SESSION['adminname'] = $adminFetch->adminname;
                    $_SESSION['my_password'] = $adminFetch->my_password;

                    echo "<script>window.location.href = '" . APP_URL . "admin-panel/index.php';</script>";
                    exit();
                } else {
                    $error = 'Your password is incorrect';
                }
            } else {
                // User login check
                $login = $conn->prepare("SELECT * FROM user WHERE email = :email");
                $login->bindParam(':email', $email);
                $login->execute();
                $fetch = $login->fetch(PDO::FETCH_ASSOC);

                if ($fetch) {
                    if (password_verify($password, $fetch['my_password'])) {
                        // Successful login - reset attempts
                        $_SESSION['attempts'] = 3;
                        $_SESSION['email'] = $fetch['email'];
                        $_SESSION['user_id'] = $fetch['id'];
                        $_SESSION['username'] = $fetch['username'];
                        $_SESSION['my_password'] = $fetch['my_password'];

                        echo "<script>window.location.href = '" . APP_URL . "auth/welcome.php';</script>";
                        exit();
                    } else {
                        // Wrong password - decrement attempts
                        $_SESSION['attempts']--;

                        if ($_SESSION['attempts'] <= 0) {
                            // Lock the account for 30 minutes
                            $_SESSION['locked'] = time() + (30 * 60);
                            $error = 'Too many failed attempts. Your device locked for 30 minutes.';
                        } else {
                            $error = 'Invalid password. You have ' . $_SESSION['attempts'] . ' attempts remaining.';
                        }
                    }
                } else {
                    $error = 'Cannot find this email address';
                }
            }
        }
    }
}
?>

<!-- Rest of your HTML remains the same -->

<div class="hero-wrap js-fullheight" style="background-image: url('<?php echo APP_URL; ?>images/image_2.jpg');"
    data-stellar-background-ratio="0.5">
    <div class="overlay"></div>
    <div class="container">
        <div class="row no-gutters slider-text js-fullheight align-items-center justify-content-start"
            data-scrollax-parent="true">
            <div class="col-md-7 ftco-animate">
                <!-- Optional: Add heading or subheading here -->
            </div>
        </div>
    </div>
</div>

<section class="ftco-section ftco-book ftco-no-pt ftco-no-pb">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6 mt-5">
                <form action="login.php" method="post" class="appointment-form">
                    <h3 class="mb-3">Login</h3>
                    <div class="form-group">
                        <input type="email" name="email" class="form-control" placeholder="Email" required>
                    </div>
                    <div class="form-group">
                        <input type="password" name="password" class="form-control" placeholder="Password" required>
                    </div>
                    <div class="form-group">
                        <input type="submit" name="submit" value="Login" class="btn btn-primary py-3 px-4">
                    </div>
                    <label style="color: red; text-align: center;"><?php echo $error; ?></label>
                    <div class="dropdown-divider"></div>
                    <p>New around here? <a href="register.php">Sign up</a></p>
                    <p><a href="change_password.php">Forgot password?</a></p>
                </form>
            </div>
        </div>
    </div>
</section>

<?php require "../include/footer.php"; ?>