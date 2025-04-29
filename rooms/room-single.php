<?php require "../include/header.php"; ?>
<?php require "../config/config.php"; ?>

<?php
if (!isset($_SESSION['username'])) {
	echo "<script>window.location.href='" . APP_URL . "auth/login.php';</script>";
	exit;
}
if (isset($_GET['id'])) {
	//room_id
	$id = $_GET['id'];

	$room = $conn->query("SELECT * FROM rooms WHERE status =1 and id = '$id'"); //connect to the database and query
	$room->execute(); //execute the query

	$singleRoom = $room->fetch(PDO::FETCH_OBJ); //fetch all row from the database and store it in an array
	if (!$singleRoom) {
		echo "<script>window.location.href='" . APP_URL . "error';</script>";
		exit;
	}
	//grapping utilities
	$utilities = $conn->query("SELECT * FROM utilities WHERE room_id = '$id' "); //connect to the database and query
	$utilities->execute(); //execute the query
	$allUtilities = $utilities->fetchAll(PDO::FETCH_OBJ); //fetch all row from the database and store it in an array

	if (isset($_POST['submit'])) {

		if (empty($_POST['email']) || empty($_POST['full_name']) || empty($_POST['full_name']) || empty($_POST['phone_number']) || empty($_POST['phone_number']) || empty($_POST['check_in']) || empty($_POST['check_in']) || empty($_POST['check_out']) || empty($_POST['check_out'])) {
			echo "<script>alert('One or more inputs are empty')</script>";
		} else {

			$check_in = $_POST['check_in'];
			$check_out = $_POST['check_out'];
			$email = $_POST['email'];
			$phone_number = $_POST['phone_number'];
			$full_name = $_POST['full_name'];
			$user_id = $_SESSION['id'];
			$room_name = $singleRoom->name;
	
			$status = 'pending';
			$payment = $singleRoom->price;

			//grapping price through session
			$_SESSION['price'] = $singleRoom->price;

			$price = $singleRoom->price;

			$dateIn = new DateTime($check_in);
			$dateOut = new DateTime($check_out);
			$interval = $dateIn->diff($dateOut);
			$dayCount = $interval->format('%d');

			$grandTotal = $dayCount * $payment;


			if (date("Y-m-d") > $check_in or date("Y-m-d") > $check_out) {
				echo "<script>alert('Please select a valid date start from tomorrow')</script>";
			} else if ($check_in > $check_out or $check_in == date("Y-m-d")) {
				echo "<script>alert('Please select a valid date, Wrong with check-in date')</script>";
			} else if ($check_out <= $check_in) {
				echo "<script>alert('Please select a valid date, Wrong with check-out date')</script>";
			} else {
				$booking = $conn->prepare("INSERT INTO bookings (email, full_name, phone_number,
					room_name, status, payment, room_id, user_id, check_in, check_out) 
					VALUES(:email,:full_name, :phone_number, :room_name, :status, :payment, :room_id, :user_id, :check_in, :check_out)");

				$booking->execute([
					':email' => $email,
					':full_name' => $full_name,
					':phone_number' => $phone_number,
					':room_name' => $room_name,
					':status' => $status,
					':payment' => $grandTotal,
					':room_id' => $id,
					':user_id' => $user_id,
					':check_in' => $check_in,
					':check_out' => $check_out
				]);

				echo "<script>window.locatioin.href='pay.php';</script>";

				if ($booking) {
					echo "<script>window.location.href='" . APP_URL . "rooms/payment.php?id=$id';</script>";
				}
			}
		}
	}
} else {
	echo "<script>window.location.href='" . APP_URL . "error';</script>";
	exit;
}
?>

<div class="hero-wrap js-fullheight"
	style="background-image: linear-gradient(rgba(0, 0, 0, 0.5), rgba(0, 0, 0, 0.5)), url('<?php echo APP_URL; ?>admin-panel/rooms-admins/room_images/<?php echo $singleRoom->images; ?>');"
	data-stellar-background-ratio="0.5">
	<div class="overlay"></div>
	<div class="container">
		<div class="row no-gutters slider-text js-fullheight align-items-center justify-content-start"
			data-scrollax-parent="true">
			<div class="col-md-7 ftco-animate">
				<h2 class="subheading">Welcome to Vacation Rental</h2>
				<h1 class="mb-4"><?php echo $singleRoom->name; ?></h1>
				<!-- <p><a href="#" class="btn btn-primary">Learn more</a> <a href="#" class="btn btn-white">Contact us</a></p> -->
			</div>
		</div>
	</div>
</div>

<section class="ftco-section ftco-book ftco-no-pt ftco-no-pb">
	<div class="container">
		<div class="row justify-content-end">
			<div class="col-lg-4">

				<!-- Form Booking Room -->
				<form action="room-single.php?id=<?php echo $id ?>" method="post" class="appointment-form"
					style="margin-top: -568px;">
					<h3 class="mb-3">Book this room</h3>
					<!-- Email -->
					<div class="row">
						<div class="col-md-12">
							<div class="form-group">
								<input type="email" name="email" class="form-control" placeholder="Email">
							</div>
						</div>

						<!-- Full Name -->
						<div class="col-md-12">
							<div class="form-group">
								<input type="text" name="full_name" class="form-control" placeholder="Full Name">
							</div>
						</div>

						<!-- Phone Number -->
						<div class="col-md-12">
							<div class="form-group">
								<input type="phone" name="phone_number" class="form-control" placeholder="Phone Number">
							</div>
						</div>

						<!-- Check In -->
						<div class="col-md-6">
							<div class="form-group">
								<div class="input-wrap">
									<div class="icon"><span class="ion-md-calendar"></span></div>
									<input type="text" id="check_in" name="check_in"
										class="form-control appointment_date-check-in" placeholder="Check-In"
										onblur="formatDate(this)">
								</div>
							</div>
						</div>

						<!-- Check Out -->
						<div class="col-md-6">
							<div class="form-group">
								<div class="icon"><span class="ion-md-calendar"></span></div>
								<input type="text" name="check_out" class="form-control appointment_date-check-out"
									placeholder="Check-Out">
							</div>
						</div>

						<!-- Submit -->
						<div class="col-md-12">
							<div class="form-group">
								<input type="submit" name="submit" value="Book and Pay Now"
									class="btn btn-primary py-3 px-4">
							</div>
						</div>

					</div>
				</form>
			</div>
		</div>
	</div>
</section>

<section class="ftco-section bg-light">
	<div class="container">

		<div class="row no-gutters">
			<div class="col-md-6 wrap-about">
				<div class="img img-2 mb-4" style="background-image: url(<?php echo APP_URL; ?>admin-panel/rooms-admins/room_images/image_2.jpg);">
				</div>
				<h2>The most recommended vacation rental</h2>
				<p>A small river named Duden flows by their place and supplies it with the necessary regelialia. It is a
					paradisematic country, in which roasted parts of sentences fly into your mouth. Even the
					all-powerful Pointing has no control about the blind texts it is an almost unorthographic life One
					day however a small line of blind text by the name of Lorem Ipsum decided to leave for the far World
					of Grammar.</p>
			</div>

			<div class="col-md-6 wrap-about ftco-animate">
				<div class="heading-section">
					<div class="pl-md-5">
						<h2 class="mb-2">What we offer</h2>
					</div>
				</div>

				<div class="pl-md-5">
					<p>A small river named Duden flows by their place and supplies it with the necessary regelialia.
						It
						is a paradisematic country, in which roasted parts of sentences fly into your mouth.</p>
					<div class="row">

						<?php foreach ($allUtilities as $utility): ?>
							<div class="services-2 col-lg-6 d-flex w-100">
								<div class="icon d-flex justify-content-center align-items-center">
									<span class="<?php echo $utility->icon; ?>"></span>
								</div>
								<div class="media-body pl-3">
									<h3 class="heading"><?php echo $utility->name; ?></h3>
									<p><?php echo $utility->description; ?></p>
								</div>
							</div>
						<?php endforeach ?>

					</div>

				</div>
			</div>
		</div>
	</div>
</section>

<section class="ftco-intro" style="background-image: url(<?php echo APP_URL; ?>images/image_2.jpg);"
	data-stellar-background-ratio="0.5">
	<div class="overlay"></div>
	<div class="container">
		<div class="row justify-content-center">
			<div class="col-md-9 text-center">
				<h2>Ready to get started</h2>
				<p class="mb-4">It’s safe to book online with us! Get your dream stay in clicks or drop us a line with
					your questions.</p>
				<p class="mb-0"><a href="#" class="btn btn-primary px-4 py-3">Learn More</a> <a href="#"
						class="btn btn-white px-4 py-3">Contact us</a></p>
			</div>
		</div>
	</div>
</section>

<?php require "../include/footer.php"; ?>