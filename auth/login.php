<?php
require "../include/header.php";
require "../config/config.php";

// Start session securely
if (session_status() === PHP_SESSION_NONE) {
    session_start([
        'cookie_lifetime' => 86400,
        'cookie_secure' => isset($_SERVER['HTTPS']),
        'cookie_httponly' => true,
        'cookie_samesite' => 'Lax'
    ]);
}

// Declare error
$error = '';

if (isset($_POST['submit'])) {
    if (empty($_POST['email']) || empty($_POST['password'])) {
        $error = 'Please fill all fields';
    } else {
        $email = trim($_POST['email']);
        $password = $_POST['password'];

        try {
            // First check admin table
            $adminLogin = $conn->prepare("SELECT * FROM admin WHERE email = :email");
            $adminLogin->bindParam(':email', $email);
            $adminLogin->execute();
            
            if ($adminLogin->rowCount() > 0) {
                $admin = $adminLogin->fetch(PDO::FETCH_OBJ);
                
                if (password_verify($password, $admin->my_password)) {
                    // Set admin session
                    $_SESSION['admin'] = [
                        'id' => $admin->id,
                        'email' => $admin->email,
                        'name' => $admin->adminname,
                        'is_admin' => true
                    ];
                    
                    header("Location: " . APP_URL . "admin-panel/index.php");
                    exit();
                } else {
                    $error = 'Your password is incorrect';
                }
            } else {
                // Check user table if not admin
                $userLogin = $conn->prepare("SELECT * FROM user WHERE email = :email");
                $userLogin->bindParam(':email', $email);
                $userLogin->execute();
                
                if ($userLogin->rowCount() > 0) {
                    $user = $userLogin->fetch(PDO::FETCH_ASSOC);
                    
                    if (password_verify($password, $user['my_password'])) {
                        // Set user session
                        $_SESSION['user'] = [
                            'id' => $user['id'],
                            'email' => $user['email'],
                            'username' => $user['username'],
                            'is_admin' => false
                        ];
                        
                        header("Location: " . APP_URL . "auth/welcome.php");
                        exit();
                    } else {
                        $error = 'Your password is incorrect';
                    }
                } else {
                    $error = 'Email address not found';
                }
            }
        } catch (PDOException $e) {
            $error = 'Database error: ' . $e->getMessage();
        }
    }
}
?>

<!-- Rest of your HTML remains the same -->
<!-- I disable Image for better look -->

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
                    <p><a href="forgotpassword.php">Forgot password?</a></p>
                </form>
            </div>
        </div>
    </div>
</section>

<?php require "../include/footer.php"; ?>