<?php session_start(); ?> <!--  // Start the session-->

<?php
if(!isset($_SESSION['username'])) {
    $_SESSION['username'] = null;
    $_SESSION['id'] = null;
}else{
    $username= $_SESSION['username'];
    $user_id=$_SESSION['id'];
}

// echo $_SESSION['username'] ." This username is on header.php";

require 'domain.php';
define("PHONE", "+855 969 666 961");
define("EMAIL", "ravattrasmartboy@gmail.com");
$username = 'Error';
?>

<!-- Purpose: Header file for the website. -->
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- SEO Meta Tags -->
    <meta name="description"
        content="Vacation Rental is a top choice for your vacation accommodation. We offer high-quality apartment rentals in a prime location.">
    <meta name="keywords"
        content="vacation rental, apartment rental, vacation apartments, vacation homes, rent a place for vacation">
    <meta name="author" content="Vattra">

    <!-- Social Media Meta Tags for better sharing -->
    <meta property="og:title" content="Vacation Rental - Premium Apartment Rentals">
    <meta property="og:description"
        content="Looking for a great place to stay on vacation? Check out our apartment rentals for your perfect getaway!">
    <meta property="og:image" content="<?php echo APP_URL; ?>images/vacation-image.jpg">
    <meta property="og:url" content="<?php echo APP_URL; ?>">

    <meta name="twitter:title" content="Vacation Rental">
    <meta name="twitter:description" content="Enjoy your vacation with the best apartment rentals. Book now!">
    <meta name="twitter:image" content="<?php echo APP_URL; ?>images/vacation-image.jpg">
    <meta name="twitter:card" content="summary_large_image">

    <title>Vacation Rental </title>

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Poppins:200,300,400,500,600,700,800,900&display=swap"
        rel="stylesheet">
    <link
        href="https://fonts.googleapis.com/css?family=Playfair+Display:400,400i,500,500i,600,600i,700,700i&display=swap"
        rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">

    <!-- External CSS Files -->
    <link rel="stylesheet" href="<?php echo APP_URL; ?>css/animate.css">
    <link rel="stylesheet" href="<?php echo APP_URL; ?>css/owl.carousel.min.css">
    <link rel="stylesheet" href="<?php echo APP_URL; ?>css/owl.theme.default.min.css">
    <link rel="stylesheet" href="<?php echo APP_URL; ?>css/magnific-popup.css">
    <link rel="stylesheet" href="<?php echo APP_URL; ?>css/bootstrap-datepicker.css">
    <link rel="stylesheet" href="<?php echo APP_URL; ?>css/jquery.timepicker.css">
    <link rel="stylesheet" href="<?php echo APP_URL; ?>css/flaticon.css">
    <link rel="stylesheet" href="<?php echo APP_URL; ?>css/style.css">

    <!-- Custom CSS -->
    <link rel="stylesheet" href="<?php echo APP_URL; ?>css/custom.css">
</head>

<body>
    <div class="wrap">
        <div class="container">
            <div class="row justify-content-between">
                <div class="col d-flex align-items-center">
                    <p class="mb-0 phone">
                        <span class="mailus">Phone no:</span> <a href="tel://+855 969 666 961">+855 969 666 961</a> or
                        <span class="mailus">Email us:</span> <a
                            href="mailto:ravattrasmartboy@gmail">ravattrasmartboy@gmail.com</a>
                    </p>
                </div>
                <div class="col d-flex justify-content-end">
                    <div class="social-media">
                        <p class="mb-0 d-flex">
                            <a href="#" class="d-flex align-items-center justify-content-center"><span
                                    class="fa fa-facebook"><i class="sr-only">Facebook</i></span></a>
                            <a href="#" class="d-flex align-items-center justify-content-center"><span
                                    class="fa fa-twitter"><i class="sr-only">Twitter</i></span></a>
                            <a href="#" class="d-flex align-items-center justify-content-center"><span
                                    class="fa fa-instagram"><i class="sr-only">Instagram</i></span></a>
                            <a href="#" class="d-flex align-items-center justify-content-center"><span
                                    class="fa fa-dribbble"><i class="sr-only">Dribbble</i></span></a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <nav class="navbar navbar-expand-lg navbar-dark ftco_navbar bg-dark ftco-navbar-light" id="ftco-navbar">
        <div class="container">
            <a class="navbar-brand" href="<?php echo APP_URL; ?>index.php">Vacation<span>Rental</span></a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#ftco-nav"
                aria-controls="ftco-nav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="fa fa-bars"></span> Menu
            </button>
            <div class="collapse navbar-collapse" id="ftco-nav">
                <ul class="navbar-nav ml-auto">
                    <li class="nav-item active"><a href="<?php echo APP_URL; ?>" class="nav-link">Home</a></li>
                    <li class="nav-item"><a href="<?php echo APP_URL; ?>about.php" class="nav-link">About</a></li>
                    <li class="nav-item"><a href="<?php echo APP_URL; ?>services.php" class="nav-link">Services</a></li>
                    <li class="nav-item"><a href="<?php echo APP_URL; ?>rooms.php" class="nav-link">Apartment Room</a>
                    </li>
                    <li class="nav-item"><a href="<?php echo APP_URL; ?>contact.php" class="nav-link">Contact</a></li>
                    <?php if (!isset($_SESSION['username'])): ?>
                        <li class="nav-item"><a href="<?php echo APP_URL; ?>auth/login.php" class="nav-link">Login</a></li>
                        <li class="nav-item"><a href="<?php echo APP_URL; ?>auth/register.php" class="nav-link">Register</a>
                        </li>
                    <?php else: ?>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button"
                                data-toggle="dropdown" aria-expanded="false">
                                <!-- Dropdown -->
                                <?php echo $_SESSION['username']; ?>
                            </a>
                            <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                                <!-- Booking with user id -->
                                <li><a class="dropdown-item"
                                        href="<?php echo APP_URL; ?>users/booking.php?id=<?php echo $_SESSION['id'] ?>">Booking</a>
                                </li>
                                <li><a class="dropdown-item" href="<?php echo APP_URL; ?>auth/logout.php">Logout</a></li>
                                <li><a class="dropdown-item" href="<?php echo APP_URL; ?>users/qr_login.php">Login via QR
                                        Code</a></li>
                                <li><a class="dropdown-item" href="<?php echo APP_URL; ?>auth/changepassword.php"><i
                                            class="fa fa-cog"></i> Change Password</a></li>
                            </ul>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>
    <!-- END nav -->

    <!-- Scripts for Bootstrap 4 -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

</body>

</html>