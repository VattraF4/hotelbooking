<?php session_start();
require 'adminURL.php'; ?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>Hotel Admin</title>
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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
    <style>
        /* Modern Header Styles */
        :root {
            --primary-color: #3498db;
            --secondary-color: #2c3e50;
            --accent-color: #e74c3c;
            --light-color: #ecf0f1;
            --dark-color: #2c3e50;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        .navbar-brand {
            font-weight: 700;
            font-size: 1.5rem;
            color: var(--light-color) !important;
            display: flex;
            align-items: center;
            padding: 0.5rem 1rem;
        }
        
        .navbar-brand i {
            margin-right: 10px;
            font-size: 1.8rem;
            color: var(--primary-color);
        }
        
        .header-top {
            background-color: var(--dark-color);
            box-shadow: 0 2px 15px rgba(0,0,0,0.1);
            padding: 0;
        }
        
        .side-nav {
            width: 550px;
            background: var(--dark-color);
            position: fixed;
            height: calc(100vh - 60px);
            top: 60px;
            left: 0;
            overflow-y: auto;
            transition: all 0.3s ease;
            z-index: 1000;
            border-right: 1px solid rgba(255,255,255,0.1);
        }
        
        .side-nav .nav-item {
            border-bottom: 1px solid rgba(255,255,255,0.05);
        }
        
        .side-nav .nav-link {
            color: var(--light-color);
            padding: 12px 25px;
            transition: all 0.3s;
            font-size: 0.95rem;
            font-weight: 500;
        }
        
        .side-nav .nav-link:hover {
            color: var(--primary-color);
            background: rgba(255,255,255,0.05);
            padding-left: 10px;
        }
        
        .side-nav .nav-link i {
            margin-right: 12px;
            width: 20px;
            text-align: center;
            font-size: 1.1rem;
            color: var(--primary-color);
        }
        
        .navbar-nav.ml-auto .nav-link {
            color: var(--light-color);
            padding: 8px 15px;
        }
        
        .dropdown-menu {
            background: var(--dark-color);
            border: none;
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
            min-width: 200px;
        }
        
        .dropdown-item {
            color: var(--light-color);
            padding: 8px 20px;
        }
        
        .dropdown-item:hover {
            background: var(--primary-color);
            color: white;
        }
        
        .welcome-message {
            font-size: 0.9rem;
            color: var(--light-color);
            background: rgba(255,255,255,0.1);
            padding: 6px 15px;
            border-radius: 20px;
            margin-right: 15px;
        }
        
        .login-btn {
            border-radius: 20px;
            padding: 6px 20px;
            background: var(--primary-color);
            color: white !important;
            font-weight: 500;
            transition: all 0.3s;
        }
        
        .login-btn:hover {
            background: #2980b9;
            transform: translateY(-2px);
        }
        
        /* Main content adjustment */
        #wrapper {
            padding-left: 20px;
            transition: all 0.3s;
        }
        
        @media (max-width: 991px) {
            #wrapper {
                padding-left: 0;
            }
            
            .side-nav {
                left: -250px;
            }
            
            .side-nav.active {
                left: 0;
            }
            
            .welcome-message {
                display: none;
            }
        }
        
        /* Active menu item highlight */
        .side-nav .nav-link.active {
            color: var(--primary-color);
            background: rgba(255,255,255,0.1);
            border-left: 1px solid var(--primary-color);
        }
    </style>
</head>

<body>
    <div id="wrapper">
        <nav class="navbar header-top fixed-top navbar-expand-lg navbar-dark">
            <div class="container-fluid">
                <button class="navbar-toggler d-lg-none mr-2" type="button" id="sidebarToggle">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <a class="navbar-brand" href="<?php echo ADMIN_URL; ?>index.php">
                    <i class="fas fa-hotel"></i>Hotel Admin
                </a>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarText"
                    aria-controls="navbarText" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarText">
                    <ul class="navbar-nav side-nav">
                        <?php if (isset($_SESSION['adminname'])): ?>
                            <li class="nav-item">
                                <a class="nav-link" href="<?php echo ADMIN_URL; ?>index.php">
                                    <i class="fas fa-tachometer-alt"></i> Dashboard
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="<?php echo ADMIN_URL; ?>admins/admins.php">
                                    <i class="fas fa-user-tie"></i> Admins
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="<?php echo ADMIN_URL; ?>hotels-admins/show-hotels.php">
                                    <i class="fas fa-hotel"></i> Hotels
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="<?php echo ADMIN_URL; ?>rooms-admins/show-rooms.php">
                                    <i class="fas fa-bed"></i> Rooms
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="<?php echo ADMIN_URL; ?>bookings-admins/show-bookings.php">
                                    <i class="fas fa-calendar-check"></i> Bookings
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="<?php echo ADMIN_URL; ?>users-admins/user.php">
                                    <i class="fas fa-users"></i> Users
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="<?php echo ADMIN_URL; ?>hotels-admins/showArchive.php">
                                    <i class="fas fa-trash-restore"></i> Recycle Bin
                                </a>
                            </li>
                        <?php endif; ?>
                    </ul>
                    
                    <ul class="navbar-nav ml-auto">
                        <?php if (!isset($_SESSION['adminname'])): ?>
                            <li class="nav-item">
                                <a class="nav-link login-btn" href="<?php echo ADMIN_URL; ?>admins/login-admins.php">
                                    <i class="fas fa-sign-in-alt mr-1"></i> Login
                                </a>
                            </li>
                        <?php else: ?>
                            <li class="nav-item d-none d-md-block">
                                <span class="welcome-message">Welcome, <?php echo $_SESSION['adminname']; ?></span>
                            </li>
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button"
                                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i class="fas fa-user-cog"></i>
                                </a>
                                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                                    <a class="dropdown-item" href="<?php echo ADMIN_URL; ?>admins/logout.php">
                                        <i class="fas fa-sign-out-alt mr-2"></i> Logout
                                    </a>
                                </div>
                            </li>
                        <?php endif; ?>
                    </ul>
                </div>
            </div>
        </nav>

        <script>
            // Toggle sidebar on mobile
            document.getElementById('sidebarToggle').addEventListener('click', function() {
                document.querySelector('.side-nav').classList.toggle('active');
            });
            
            // Close sidebar when clicking outside on mobile
            document.addEventListener('click', function(event) {
                const sidebar = document.querySelector('.side-nav');
                const toggleBtn = document.getElementById('sidebarToggle');
                
                if (window.innerWidth <= 991 && 
                    !sidebar.contains(event.target) && 
                    event.target !== toggleBtn && 
                    !toggleBtn.contains(event.target)) {
                    sidebar.classList.remove('active');
                }
            });
            
            // Highlight active menu item
            document.addEventListener('DOMContentLoaded', function() {
                const currentPage = location.pathname.split('/').pop();
                const navLinks = document.querySelectorAll('.side-nav .nav-link');
                
                navLinks.forEach(link => {
                    const linkPage = link.getAttribute('href').split('/').pop();
                    if (currentPage === linkPage || 
                        (currentPage === '' && linkPage === 'index.php')) {
                        link.classList.add('active');
                    }
                });
            });
        </script>