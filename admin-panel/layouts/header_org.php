<?php session_start(); ?>
<?php require 'adminURL.php'; ?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <!-- This file has been downloaded from Bootsnipp.com. Enjoy! -->
    <title>Admin Panel</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- add eruda for testing -->
    <script src="https://cdn.jsdelivr.net/npm/eruda"></script>
    <script>eruda.init();</script>

    <link rel="icon" type="image/jpg" href="<?php echo ADMIN_URL; ?>img/vacation.png">
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" rel="stylesheet">
    <link href="<?php echo ADMIN_URL; ?>styles/style.css" rel="stylesheet">
    <link href="<?php echo ADMIN_URL; ?>styles/style_new.css" rel="stylesheet">
    <link href="<?php echo ADMIN_URL; ?>styles/dashboard.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-1.11.1.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">

</head>

<body>
    <div id="wrapper">
        <nav class="navbar header-top fixed-top navbar-expand-lg  navbar-dark bg-dark">
            <div class="container">
                <a class="navbar-brand" href="<?php echo ADMIN_URL; ?>index.php">LOGO</a>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarText"
                    aria-controls="navbarText" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarText">
                    <ul class="navbar-nav side-nav">

                        <?php if (isset($_SESSION['adminname'])): ?>
                            <li class="nav-item">
                                <a class="nav-link" style="margin-left: 20px;" href="<?php echo ADMIN_URL; ?>index.php"><i
                                        class="fa fa-home" aria-hidden="true"></i>
                                    Home
                                    <span class="sr-only">(current)</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="<?php echo ADMIN_URL; ?>admins/admins.php"
                                    style="margin-left: 20px;"><i class="fas fa-user-tie"></i> Admins</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="<?php echo ADMIN_URL; ?>hotels-admins/show-hotels.php"
                                    style="margin-left: 20px;"><i class="fa fa-building" aria-hidden="true"></i>
                                    Hotels</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="<?php echo ADMIN_URL; ?>rooms-admins/show-rooms.php"
                                    style="margin-left: 20px;"><i class="fa fa-bed" aria-hidden="true"></i>
                                    Rooms</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="<?php echo ADMIN_URL; ?>bookings-admins/show-bookings.php"
                                    style="margin-left: 20px;"><i class="fa fa-calendar" aria-hidden="true"></i>
                                    Bookings</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="<?php echo ADMIN_URL; ?>users-admins/user.php"
                                    style="margin-left: 20px;"><i class="fa fa-user-circle" aria-hidden="true"></i>
                                    View Users</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="<?php echo ADMIN_URL; ?>hotels-admins/showArchive.php"
                                    style="margin-left: 20px;"><i class="fa fa-recycle" aria-hidden="true"></i></i>
                                    RecycleBin</a>
                            </li>
                        <?php endif; ?>
                    </ul>
                    <?php if (!isset($_SESSION['adminname'])): ?>

                        <ul class="navbar-nav ml-md-auto d-md-flex">

                            <li class="nav-item">
                                <a class="nav-link" href="<?php echo ADMIN_URL; ?>admins/login-admins.php">login
                                </a>
                            </li>
                        </ul>

                    <?php else: ?>
                        <ul class="navbar-nav ml-md-auto d-md-flex">
                            <li class="nav-item">
                                <a class="nav-link" href="<?php echo ADMIN_URL; ?>index.php">Home
                                    <span class="sr-only">(current)</span>
                                </a>
                            </li>
                            <li class="nav-item dropdown">
                                <a class="nav-link  dropdown-toggle" href="#" id="navbarDropdown" role="button"
                                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <?php echo $_SESSION['adminname']; ?>
                                </a>
                                <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                                    <a class="dropdown-item" href="<?php echo ADMIN_URL; ?>admins/logout.php">Logout</a>
                                </div>
                            </li>
                        </ul>
                    <?php endif; ?>

                </div>
            </div>
        </nav>