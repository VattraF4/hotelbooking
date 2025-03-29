<?php
require "../include/header.php";
require "../config/config.php";

// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    // If the user is not logged in, redirect to login page
    header("Location: login.php");
    exit();
}

$id = $_GET['id'];
$booking = $conn->prepare("SELECT * FROM bookings WHERE id = '$id'");
$booking->execute();   


$Book = $booking->fetch(PDO::FETCH_OBJ); //fetch all row from the database and store it in an array

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
    <section class="ftco-section ftco-book ftco-no-pt ftco-no-pb">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-6 mt-5">
                    <div class="welcome-container">
                        <h1>Thanks, <?php echo htmlspecialchars($_SESSION['username']); ?>!</h1>
                        <p>for booking <b><?php echo $Book->room_name; ?></b></p>

                        <!-- Button to go to home page -->
                        <form action="../index.php" method="get">
                            <button type="submit" class="btn btn-primary py-3 px-4">Go to Home</button>
                        </form>

                        
                    </div>
                </div>
            </div>
        </div>
    </section>
</body>

</html>