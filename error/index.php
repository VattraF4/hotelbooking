

 <?php
require "../include/header.php";
require "../config/config.php";
?>

<style>
    body {
        background-color:#f8f9fc;
        overflow: hidden;
    }

    .error-container {
        text-align: center;
        margin-top: -50px;
    }

    /* 404 Animation */
    .error-code {
        font-size: 120px;
        font-weight: bold;
        color: #fd7792;
        text-shadow: 4px 4px 10px rgba(0, 0, 0, 0.2);
        animation: float 3s ease-in-out infinite;
    }

    @keyframes float {
        0% { transform: translateY(0px); }
        50% { transform: translateY(-10px); }
        100% { transform: translateY(0px); }
    }

    /* Fade & Scale Animation */
    .error-text {
        font-size: 24px;
        color: #6c757d;
        opacity: 0;
        transform: scale(0.9);
        animation: fadeInScale 1.5s ease-in-out forwards 1s;
    }

    @keyframes fadeInScale {
        0% { opacity: 0; transform: scale(0.9); }
        100% { opacity: 1; transform: scale(1); }
    }

    /* Button Animation */
    .btn-home {
        background-color: #fd7792;
        color: #fff;
        padding: 10px 20px;
        border-radius: 5px;
        text-decoration: none;
        transition: 0.3s ease-in-out;
        display: inline-block;
        opacity: 0;
        transform: translateY(20px);
        animation: fadeInUp 1.5s ease-out forwards 1.5s;
    }

    @keyframes fadeInUp {
        0% { opacity: 0; transform: translateY(20px); }
        100% { opacity: 1; transform: translateY(0); }
    }

    .btn-home:hover {
        background-color: white;
        transform: scale(1.05);
    }
</style>

<div class="container d-flex align-items-center justify-content-center vh-100">
    <div class="error-container">
        <div class="error-code">404</div>
        <p class="error-text">Oops! The page you’re looking for doesn’t exist.</p>
        <p class="lead text-gray-800 mb-4">It might have been moved, deleted, or is temporarily unavailable.</p>
        <a href="<?php echo APP_URL ?> " class="btn-home">&larr; Back to Home</a>
    </div>
</div>

<?php require "../include/footer.php"; ?>
