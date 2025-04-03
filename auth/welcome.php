<?php
require "../include/header.php";
require "../config/config.php";
ini_set('display_errors', 1);
error_reporting(E_ALL);
// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    // If the user is not logged in, redirect to login page
    echo "<script>window.location.href = 'login.php';</script>";
    exit();
}else{
    echo "<script>alert('Welcome');</script>";
}
echo "Welcome to the welcome page!";
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome</title>
    <link rel="stylesheet" href="styles.css"> <!-- Add your own stylesheet -->
</head>

<body>

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
                    <div class="welcome-container">
                        <h1>Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?>!</h1>
                        <p>Congratulations, you have successfully logged in.</p>

                        <!-- Button to go to home page -->
                        <form action="../index.php" method="get">
                            <button type="submit" class="btn btn-primary py-3 px-4">Go to Home</button>
                        </form>

                        <!-- Button to logout -->
                        <!-- <form action="logout.php" method="post">
                            <button type="submit" class="btn btn-primary py-3 px-4">Logout</button>
                        </form> -->
                    </div>
                </div>
            </div>
        </div>
    </section>
</body>

</html>