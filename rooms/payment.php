<?php
require "../include/header.php";
require "../config/config.php";

// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    // If the user is not logged in, redirect to login page
    header("Location: login.php");
    exit();
}

$idRoom = $_GET['id'];
$booking = $conn->prepare("SELECT * FROM bookings WHERE room_id = '$idRoom' AND id=(SELECT MAX(id) FROM bookings WHERE room_id = '$idRoom')");
$booking->execute();   


$Book = $booking->fetch(PDO::FETCH_OBJ); //fetch all row from the database and store it in an array

echo "<pre>";
print_r($Book);
echo "</pre>";

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
                        <p>for booking <b><?php echo $Book->room_name; ?></b> of <b><?php echo $Book->hotel_name; ?></b> hotel</p>
                        <p>Check-in date: <b><?php echo $Book->check_in; ?></b></p>
                        <p>Check-out date: <b><?php echo $Book->check_out; ?></b></p>
                        <p>Payment: <b>$<?php echo $Book->payment; ?>/day</b></p>
                        <!-- Grapping Payment -->
                        <?php $_SESSION['payment'] = $Book->payment; ?>

                        <!-- Count Days -->
                         <?php 
                         $dateIn = new DateTime($Book->check_in);
                         $dateOut = new DateTime($Book->check_out);
                         $interval = $dateIn->diff($dateOut);
                         $dayCount = $interval->format('%d');
                         ?>

                        <p>Days: <b><?php echo $dayCount; ?></b></p>
                        <!-- Button to go to home page -->
                        <!-- <form action="pay.php?id=<?php echo $_SESSION['payment']; ?>" method="get">
                            <button type="submit" class="btn btn-primary py-3 px-4">Pay Now</button>
                        </form> -->

                        <?php require 'pay.php' ?>
                        
                    </div>
                </div>
            </div>
        </div>
    </section>
</body>

</html>