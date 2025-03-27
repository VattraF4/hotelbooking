<?php
session_start(); //start the session
$Domain = $_SERVER['HTTP_HOST'];
if (strpos($Domain, "localhost") !== false) {
    define("APP_URL", "/hotelbooking/"); //localhost/hotelbooking
} else {
    define("APP_URL", "https://$Domain/"); //www.example.com
}
define("PHONE", "+855 969 666 961");
define("EMAIL", "ravattrasmartboy@gmail.com");
?>
<!--  Purpose: Header file for the website. -->
<!DOCTYPE html>
<html lang="en">

<head>
	<title>Vacation Rental - Free Bootstrap 4 Template by Colorlib</title>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

	<link href="https://fonts.googleapis.com/css?family=Poppins:200,300,400,500,600,700,800,900&display=swap"
		rel="stylesheet">
	<link
		href="https://fonts.googleapis.com/css?family=Playfair+Display:400,400i,500,500i,600,600i,700,700i&display=swap"
		rel="stylesheet">

	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">

	<link rel="stylesheet" href="<?php echo APP_URL; ?>css/animate.css">

	<link rel="stylesheet" href="<?php echo APP_URL; ?>css/owl.carousel.min.css">
	<link rel="stylesheet" href="<?php echo APP_URL; ?>css/owl.theme.default.min.css">
	<link rel="stylesheet" href="<?php echo APP_URL; ?>css/magnific-popup.css">

	<link rel="stylesheet" href="<?php echo APP_URL; ?>css/bootstrap-datepicker.css">
	<link rel="stylesheet" href="<?php echo APP_URL; ?>css/jquery.timepicker.css">

	<link rel="stylesheet" href="<?php echo APP_URL; ?>css/flaticon.css">
	<link rel="stylesheet" href="<?php echo APP_URL; ?>css/style.css">
</head>

<body>
	<div class="wrap">
		<div class="container">
			<div class="row justify-content-between">
				<div class="col d-flex align-items-center">
					<p class="mb-0 phone"><span class="mailus">Phone no:</span> <a href="tel://+855 969 666 961">+855 969 666 961</a> or <span
							class="mailus">email us:</span> <a href="mailto:ravattrasmartboy@gmail">ravattrasmartboy@gmail.com</a></p>
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
			<a class="navbar-brand" href="index.php">Vacation<span>Rental</span></a>
			<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#ftco-nav"
				aria-controls="ftco-nav" aria-expanded="false" aria-label="Toggle navigation">
				<span class="fa fa-bars"></span> Menu
			</button>
			<div class="collapse navbar-collapse" id="ftco-nav">
				<ul class="navbar-nav ml-auto">
					<li class="nav-item active"><a href="<?php echo APP_URL; ?>index.php" class="nav-link">Home</a></li>
					<li class="nav-item"><a href="<?php echo APP_URL; ?>about.php" class="nav-link">About</a></li>
					<li class="nav-item"><a href="<?php echo APP_URL; ?>services.html" class="nav-link">Services</a>
					</li>
					<li class="nav-item"><a href="<?php echo APP_URL; ?>rooms.html" class="nav-link">Apartment Room</a>
					</li>
					<li class="nav-item"><a href="<?php echo APP_URL; ?>contact.php" class="nav-link">Contact</a></li>
					<?php
					if (!isset($_SESSION['username'])):
						?>
						<li class="nav-item"><a href="<?php echo APP_URL; ?>auth/login.php" class="nav-link">Login</a></li>
						<li class="nav-item"><a href="<?php echo APP_URL; ?>auth/register.php" class="nav-link">Register</a></li>
					<?php
					else:
						?>
						<li class="nav-item dropdown">
							<a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button"
								data-bs-toggle="dropdown" aria-expanded="false">
								<!-- Dropdown -->
								<?php
								echo $_SESSION['username'];
								?>
							</a>
							<ul class="dropdown-menu" aria-labelledby="navbarDropdown">
								<li><a class="dropdown-item" href="<?php echo APP_URL; ?>auth/logout.php">Logout</a></li>
								<li><a class="dropdown-item" href="<?php echo APP_URL; ?>auth/changepassword.php"><i class="fa fa-cog"></i> Change Password</a></li>
							</ul>
						</li>

					<?php
					endif;
					?>
				</ul>

			</div>
		</div>
	</nav>
	<!-- END nav -->