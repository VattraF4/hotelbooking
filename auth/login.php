
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

        // Admin login check
        $adminLogin = $conn->prepare("SELECT * FROM admin WHERE email = :email");
        $adminLogin->execute([':email' => $email]);
        $adminFetch = $adminLogin->fetch(PDO::FETCH_OBJ);

        if ($adminLogin->rowCount() > 0) {
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
            $login->execute([':email' => $email]);
            $fetch = $login->fetch(PDO::FETCH_ASSOC);

            if ($login->rowCount() > 0) {
                if (password_verify($password, $fetch['my_password'])) {
                    $_SESSION['email'] = $fetch['email'];
                    $_SESSION['id'] = $fetch['id'];
                    $_SESSION['username'] = $fetch['username'];
                    $_SESSION['my_password'] = $fetch['my_password'];

                    echo "<script>window.location.href = '" . APP_URL . "auth/welcome.php';</script>";
                    exit();
                } else {
                    $error = 'Your password is incorrect';
                }
            } else {
                $error = 'Cannot find this email address';
            }
        }
    }
}
?>
<!-- I disable Image for better look -->
 
<div class="hero-wrap js-fullheight" style="background-image: url('<?php echo APP_URL; ?>images/image_2.jpg');" data-stellar-background-ratio="0.5">
    <div class="overlay"></div>
    <div class="container">
        <div class="row no-gutters slider-text js-fullheight align-items-center justify-content-start" data-scrollax-parent="true">
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
                    <p><a href="forgotpassword.php">Forgot password?</a></p>
                </form>
            </div>
        </div>
    </div>
</section>

<?php require "../include/footer.php"; ?>
