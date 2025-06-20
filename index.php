<?php
require "include/header.php";
require "config/config.php";
?>
<?php
if (!isset($_SESSION['username'])) {
    echo "<script>window.location.href='" . APP_URL . "auth/login.php';</script>";
	// header('Location: ' . APP_URL . 'auth/login.php');
    exit;
}
	// // 2. Delete the token after login
	// try {
    // $conn->prepare("DELETE FROM qr_tokens WHERE user_id = ?")->execute([$user_id]);
	// }catch(PDOException $e){
	// 	error_log('Archive failed:'. $e->getMessage());
	// }
?>

<?php // Query to get all hotels
$hotel = $conn->query("SELECT * FROM `hotels` WHERE  status =1"); //connect to the database and query
$hotel->execute(); //execute the query
$allHotels = $hotel->fetchAll(PDO::FETCH_OBJ); //fetch all row from the database and store it in an array

//Query to get all rooms
$room = $conn->query("SELECT * FROM `rooms` WHERE  status =1"); //connect to the database and query
$room->execute(); //execute the query
$allRooms = $room->fetchAll(PDO::FETCH_OBJ); //fetch all row from the database and store it in an array

?>
<div class="hero-wrap js-fullheight" style="background-image: url('admin-panel/hotels-admins/hotel_images/image_2.jpg');"
	data-stellar-background-ratio="0.5">
	<div class="overlay"></div>
	<div class="container">
		<div class="row no-gutters slider-text js-fullheight align-items-center justify-content-start"
			data-scrollax-parent="true">
			<div class="col-md-7 ftco-animate">
				<h2 class="subheading">Welcome to Vacation Rental</h2>
				<h1 class="mb-4">Rent an appartment for your vacation</h1>
				<p><a href="#" class="btn btn-primary">Learn more</a> <a href="#" class="btn btn-white">Contact us</a>
				</p>
			</div>
		</div>
	</div>
</div>

<!-- Hotels Section -->
<section class="ftco-section ftco-services">
	<div class="container">
		<div class="row">
			<?php foreach ($allHotels as $hotel): ?>
				<!-- Sheraton -->
				<div class="col-md-4 d-flex services align-self-stretch px-4 ftco-animate">
					<div class="d-block services-wrap text-center">
						<div class="img" style="background-image: url(admin-panel/hotels-admins/hotel_images/<?php echo $hotel->image; ?>);"></div>
						<div class="media-body py-4 px-3">
							<h3 class="heading"><?php echo $hotel->name; ?></h3>
							<p><?php echo $hotel->description; ?></p>
							<p>Location: <?php echo $hotel->location; ?>.</p>
							<p><a href="rooms.php?id=<?php echo $hotel->id; ?>" class="btn btn-primary">View rooms</a></p>
						</div>
					</div>
				</div>
			<?php endforeach; ?>

		</div>
	</div>
</section>

<!-- Rooms -->
<section class="ftco-section bg-light">
	<div class="container-fluid px-md-0">
		<div class="row no-gutters justify-content-center pb-5 mb-3">
			<div class="col-md-7 heading-section text-center ftco-animate">
				<h2>Apartment Room</h2>
			</div>
		</div>

		<div class="row no-gutters">

			<?php foreach ($allRooms as $room): ?>
				<div class="col-lg-6">
					<div class="room-wrap d-md-flex">
						<a href="#" class="img" style="background-image: url('admin-panel/rooms-admins/room_images/<?php echo htmlspecialchars($room->images); ?>');"></a>
						<div class="half left-arrow d-flex align-items-center">
							<div class="text p-4 p-xl-5 text-center">
								<p class="star mb-0"><span class="fa fa-star"></span><span class="fa fa-star"></span><span
										class="fa fa-star"></span><span class="fa fa-star"></span><span
										class="fa fa-star"></span></p>
								<!-- <p class="mb-0"><span class="price mr-1">$120.00</span> <span class="per">per night</span></p> -->
								<h3 class="mb-3"><a
										href="<?php echo APP_URL; ?>rooms/single-room.php?id=<?php echo $room->id; ?>"></a>
									<?php echo $room->name; ?></a>
								</h3>
								<ul class="list-accomodation">
									<li><span>Max:</span> <?php echo $room->num_person; ?> Persons</li>
									<li><span>Size:</span> <?php echo $room->size; ?> m&sup2</li>
									<li><span>View:</span> <?php echo $room->view; ?></li>
									<li><span>Bed:</span> <?php echo $room->num_bed; ?></li>
									<li><span>Price per night:</span> <?php echo "$";
									echo $room->price; ?></li>
								</ul>
								<p class="pt-1"><a
										href="<?php echo APP_URL; ?>rooms/room-single.php?id=<?php echo $room->id; ?>"
										class="btn-custom px-3 py-2">View Room Details
										<span class="icon-long-arrow-right"></span></a>
								</p>
							</div>
						</div>
					</div>
				</div>
			<?php endforeach; ?>


		</div>
	</div>
</section>


<section class="ftco-section bg-light">
	<div class="container">
		<div class="row no-gutters">

			<div class="col-md-6 wrap-about">
				<div class="img img-2 mb-4" style="background-image: url(images/image_2.jpg);">
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
					<p>A small river named Duden flows by their place and supplies it with the necessary regelialia. It
						is a paradisematic country, in which roasted parts of sentences fly into your mouth.</p>
					<div class="row">

						<div class="services-2 col-lg-6 d-flex w-100">
							<div class="icon d-flex justify-content-center align-items-center">
								<span class="flaticon-diet"></span>
							</div>
							<div class="media-body pl-3">
								<h3 class="heading">Tea Coffee</h3>
								<p>Start your day right — every room includes a self-service tea and coffee corner.</p>
							</div>
						</div>

						<div class="services-2 col-lg-6 d-flex w-100">
							<div class="icon d-flex justify-content-center align-items-center">
								<span class="flaticon-workout"></span>
							</div>
							<div class="media-body pl-3">
								<h3 class="heading">Hot Showers</h3>
								<p>After a long day of travel or sightseeing, nothing feels better than a relaxing hot shower.</p>
							</div>
						</div>

						<div class="services-2 col-lg-6 d-flex w-100">
							<div class="icon d-flex justify-content-center align-items-center">
								<span class="flaticon-diet-1"></span>
							</div>
							<div class="media-body pl-3">
								<h3 class="heading">Laundry</h3>
								<p>All rooms are thoroughly cleaned and sanitized before every check-in. Fresh linens, spotless surfaces, and hotel-grade hygiene are our standards — because your health and comfort matter.</p>
							</div>
						</div>

						<div class="services-2 col-lg-6 d-flex w-100">
							<div class="icon d-flex justify-content-center align-items-center">
								<span class="flaticon-first"></span>
							</div>
							<div class="media-body pl-3">
								<h3 class="heading">Air Conditioning</h3>
								<p>A small river named Duden flows by their place and supplies it with the necessary</p>
							</div>
						</div>

						<div class="services-2 col-lg-6 d-flex w-100">
							<div class="icon d-flex justify-content-center align-items-center">
								<span class="flaticon-first"></span>
							</div>
							<div class="media-body pl-3">
								<h3 class="heading">Free Wifi</h3>
								<p>Whether you're working remotely, streaming your favorite shows, or just staying connected, our fast and reliable internet ensures you're never offline when you need to be online.</p>
							</div>
						</div>

						<div class="services-2 col-lg-6 d-flex w-100">
							<div class="icon d-flex justify-content-center align-items-center">
								<span class="flaticon-first"></span>
							</div>
							<div class="media-body pl-3">
								<h3 class="heading">Kitchen</h3>
								<p>Cook your own meals with ease. Our kitchens come with cookware, cutlery, a microwave, fridge, kettle, and basic cooking ingredients — perfect for both short and extended stays.</p>
							</div>
						</div>

						<div class="services-2 col-lg-6 d-flex w-100">
							<div class="icon d-flex justify-content-center align-items-center">
								<span class="flaticon-first"></span>
							</div>
							<div class="media-body pl-3">
								<h3 class="heading">Ironing</h3>
								<p>A small river named Duden flows by their place and supplies it with the necessary</p>
							</div>
						</div>

						<div class="services-2 col-lg-6 d-flex w-100">
							<div class="icon d-flex justify-content-center align-items-center">
								<span class="flaticon-first"></span>
							</div>
							<div class="media-body pl-3">
								<h3 class="heading">Lovkers</h3>
								<p>We know how important it is to feel safe That’s why we offer personal lockers or in-room secure storage for all our guestsWe know how important it is to feel safe That’s why we offer personal lockers or in-room secure storage for all our guests</p>
							</div>
						</div>

					</div>
				</div>
			</div>
		</div>
	</div>
</section>

<section class="ftco-intro" style="background-image: url(images/image_2.jpg);" data-stellar-background-ratio="0.5">
	<div class="overlay"></div>
	<div class="container">
		<div class="row justify-content-center">
			<div class="col-md-9 text-center">
				<h2>Ready to get started</h2>
				<p class="mb-4">It's safe to book online with us! Get your dream stay in clicks or drop us a line with
					your questions.</p>
				<p class="mb-0"><a href="<?php echo APP_URL; ?>about.php" class="btn btn-primary px-4 py-3">Learn
						More</a> <a href="<?php echo APP_URL; ?>contact.php" class="btn btn-white px-4 py-3">Contact
						us</a></p>
			</div>
		</div>
	</div>
</section>

<?php require "include/footer.php"; ?>
<!-- loader -->
