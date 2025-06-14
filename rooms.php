<?php require "include/header.php";
require "config/config.php";

if (isset($_GET['id'])) {
	// Hotel_ID
	$id = $_GET['id'];
	$statment = "SELECT * FROM `rooms` WHERE hotel_id = '$id' and status =1";
} else {
	$statment = "SELECT * FROM `rooms` WHERE status =1";
}

//Query to get all rooms
$getRoom = $conn->query($statment); //connect to the database and query
$getRoom->execute(); //execute the query
$getAllRooms = $getRoom->fetchAll(PDO::FETCH_OBJ); //fetch all row from the database and store it in an array

if (!$getAllRooms || empty($getAllRooms)) {
	// echo 'Work with no results';
	echo "<script>window.location.href='" . APP_URL . "/error';</script>";
	exit;
}
?>
<!-- Banner -->
<section class="hero-wrap hero-wrap-2" style="background-image: url('images/image_2.jpg');"
	data-stellar-background-ratio="0.5">
	<div class="overlay"></div>
	<div class="container">
		<div class="row no-gutters slider-text align-items-center justify-content-center">
			<div class="col-md-9 ftco-animate text-center">
				<p class="breadcrumbs mb-2"><span class="mr-2"><a href="index.html">Home <i
								class="fa fa-chevron-right"></i></a></span> <span>Rooms <i
							class="fa fa-chevron-right"></i></span></p>
				<h1 class="mb-0 bread">Apartments</h1>
			</div>
		</div>
	</div>
</section>

<!-- <section class="ftco-section bg-light ftco-no-pt ftco-no-pb">
		<div class="container-fluid px-md-0">
			<div class="row no-gutters">
				<div class="col-lg-6">
					<div class="room-wrap d-md-flex">
						<a href="#" class="img" style="background-image: url(images/room-1.jpg);"></a>
						<div class="half left-arrow d-flex align-items-center">
							<div class="text p-4 p-xl-5 text-center">
								<p class="star mb-0"><span class="fa fa-star"></span><span
										class="fa fa-star"></span><span class="fa fa-star"></span><span
										class="fa fa-star"></span><span class="fa fa-star"></span></p>
								<p class="mb-0"><span class="price mr-1">$120.00</span> <span class="per">per
										night</span></p>
								<h3 class="mb-3"><a href="rooms.html">Suite Room</a></h3>
								<ul class="list-accomodation">
									<li><span>Max:</span> 3 Persons</li>
									<li><span>Size:</span> 45 m2</li>
									<li><span>View:</span> Sea View</li>
									<li><span>Bed:</span> 1</li>
								</ul>
								<p class="pt-1"><a href="room-single.html" class="btn-custom px-3 py-2">View Room
										Details <span class="icon-long-arrow-right"></span></a></p>
							</div>
						</div>
					</div>
				</div>
				<div class="col-lg-6">
					<div class="room-wrap d-md-flex">
						<a href="#" class="img" style="background-image: url(images/room-2.jpg);"></a>
						<div class="half left-arrow d-flex align-items-center">
							<div class="text p-4 p-xl-5 text-center">
								<p class="star mb-0"><span class="fa fa-star"></span><span
										class="fa fa-star"></span><span class="fa fa-star"></span><span
										class="fa fa-star"></span><span class="fa fa-star"></span></p>
								<p class="mb-0"><span class="price mr-1">$120.00</span> <span class="per">per
										night</span></p>
								<h3 class="mb-3"><a href="rooms.html">Standard Room</a></h3>
								<ul class="list-accomodation">
									<li><span>Max:</span> 3 Persons</li>
									<li><span>Size:</span> 45 m2</li>
									<li><span>View:</span> Sea View</li>
									<li><span>Bed:</span> 1</li>
								</ul>
								<p class="pt-1"><a href="room-single.php" class="btn-custom px-3 py-2">View Room Details
										<span class="icon-long-arrow-right"></span></a></p>
							</div>
						</div>
					</div>
				</div>

				<div class="col-lg-6">
					<div class="room-wrap d-md-flex">
						<a href="#" class="img order-md-last" style="background-image: url(images/room-3.jpg);"></a>
						<div class="half right-arrow d-flex align-items-center">
							<div class="text p-4 p-xl-5 text-center">
								<p class="star mb-0"><span class="fa fa-star"></span><span
										class="fa fa-star"></span><span class="fa fa-star"></span><span
										class="fa fa-star"></span><span class="fa fa-star"></span></p>
								<p class="mb-0"><span class="price mr-1">$120.00</span> <span class="per">per
										night</span></p>
								<h3 class="mb-3"><a href="rooms.html">Family Room</a></h3>
								<ul class="list-accomodation">
									<li><span>Max:</span> 3 Persons</li>
									<li><span>Size:</span> 45 m2</li>
									<li><span>View:</span> Sea View</li>
									<li><span>Bed:</span> 1</li>
								</ul>
								<p class="pt-1"><a href="room-single.html" class="btn-custom px-3 py-2">View Room
										Details <span class="icon-long-arrow-right"></span></a></p>
							</div>
						</div>
					</div>
				</div>
				<div class="col-lg-6">
					<div class="room-wrap d-md-flex">
						<a href="#" class="img order-md-last" style="background-image: url(images/room-4.jpg);"></a>
						<div class="half right-arrow d-flex align-items-center">
							<div class="text p-4 p-xl-5 text-center">
								<p class="star mb-0"><span class="fa fa-star"></span><span
										class="fa fa-star"></span><span class="fa fa-star"></span><span
										class="fa fa-star"></span><span class="fa fa-star"></span></p>
								<p class="mb-0"><span class="price mr-1">$120.00</span> <span class="per">per
										night</span></p>
								<h3 class="mb-3"><a href="rooms.html">Deluxe Room</a></h3>
								<ul class="list-accomodation">
									<li><span>Max:</span> 3 Persons</li>
									<li><span>Size:</span> 45 m2</li>
									<li><span>View:</span> Sea View</li>
									<li><span>Bed:</span> 1</li>
								</ul>
								<p class="pt-1"><a href="room-single.html" class="btn-custom px-3 py-2">View Room
										Details <span class="icon-long-arrow-right"></span></a></p>
							</div>
						</div>
					</div>
				</div>

				<div class="col-lg-6">
					<div class="room-wrap d-md-flex">
						<a href="#" class="img" style="background-image: url(images/room-5.jpg);"></a>
						<div class="half left-arrow d-flex align-items-center">
							<div class="text p-4 p-xl-5 text-center">
								<p class="star mb-0"><span class="fa fa-star"></span><span
										class="fa fa-star"></span><span class="fa fa-star"></span><span
										class="fa fa-star"></span><span class="fa fa-star"></span></p>
								<h3 class="mb-3"><a href="rooms.html">Luxury Room</a></h3>
								<ul class="list-accomodation">
									<li><span>Max:</span> 3 Persons</li>
									<li><span>Size:</span> 45 m2</li>
									<li><span>View:</span> Sea View</li>
									<li><span>Bed:</span> 1</li>
								</ul>
								<p class="pt-1"><a href="room-single.html" class="btn-custom px-3 py-2">View Room
										Details <span class="icon-long-arrow-right"></span></a></p>
							</div>
						</div>
					</div>
				</div>
				<div class="col-lg-6">
					<div class="room-wrap d-md-flex">
						<a href="#" class="img" style="background-image: url(images/room-6.jpg);"></a>
						<div class="half left-arrow d-flex align-items-center">
							<div class="text p-4 p-xl-5 text-center">
								<p class="star mb-0"><span class="fa fa-star"></span><span
										class="fa fa-star"></span><span class="fa fa-star"></span><span
										class="fa fa-star"></span><span class="fa fa-star"></span></p>
								<h3 class="mb-3"><a href="rooms.html">Superior Room</a></h3>
								<ul class="list-accomodation">
									<li><span>Max:</span> 3 Persons</li>
									<li><span>Size:</span> 45 m2</li>
									<li><span>View:</span> Sea View</li>
									<li><span>Bed:</span> 1</li>
								</ul>
								<p class="pt-1"><a href="room-single.html" class="btn-custom px-3 py-2">View Room
										Details <span class="icon-long-arrow-right"></span></a></p>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section> -->


<!-- Rooms -->
<?php if (!$getAllRooms): ?>
	<section class="ftco-section bg-light">
		<div class="container-fluid px-md-0">
			<div class="row no-gutters justify-content-center pb-5 mb-3">
				<div class="col-md-7 heading-section text-center ftco-animate">
					<?php echo "<h2>Room Not Found</h2>";
					echo '<a href="javascript:history.back()">Go Back</a>';
					?>
				</div>
			</div>
		</div>
	</section>

<?php else: ?>

	<section class="ftco-section bg-light">
		<div class="container-fluid px-md-0">
			<div class="row no-gutters justify-content-center pb-5 mb-3">
				<div class="col-md-7 heading-section text-center ftco-animate">
					<h2>Rooms</h2>
				</div>
			</div>

			<div class="row no-gutters">

				<?php foreach ($getAllRooms as $room): ?>
					<div class="col-lg-6">
						<div class="room-wrap d-md-flex">

							<a href="#" class="img" style="background-image: url(admin-panel/rooms-admins/room_images/<?php echo $room->images; ?>);"></a>
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
<?php endif; ?>

<!-- Footer -->
<footer class="footer">
	<div class="container">
		<div class="row">
			<div class="col-md-6 col-lg-3 mb-md-0 mb-4">
				<h2 class="footer-heading"><a href="#" class="logo">Vacation Rental</a></h2>
				<p>This hotel concept is built around the idea of personal comfort meets local charm, designed for modern travelers who want boutique experiences over generic hotel stays.</p>
				<a href="#">Read more <span class="fa fa-chevron-right" style="font-size: 11px;"></span></a>
			</div>
			<div class="col-md-6 col-lg-3 mb-md-0 mb-4">
				<h2 class="footer-heading">Services</h2>
				<ul class="list-unstyled">
					<li><a href="#" class="py-1 d-block">Map Direction</a></li>
					<li><a href="#" class="py-1 d-block">Accomodation Services</a></li>
					<li><a href="#" class="py-1 d-block">Great Experience</a></li>
					<li><a href="#" class="py-1 d-block">Perfect central location</a></li>
				</ul>
			</div>
			<div class="col-md-6 col-lg-3 mb-md-0 mb-4">
				<h2 class="footer-heading">Tag cloud</h2>
				<div class="tagcloud">
					<a href="#" class="tag-cloud-link">apartment</a>
					<a href="#" class="tag-cloud-link">home</a>
					<a href="#" class="tag-cloud-link">vacation</a>
					<a href="#" class="tag-cloud-link">rental</a>
					<a href="#" class="tag-cloud-link">rent</a>
					<a href="#" class="tag-cloud-link">house</a>
					<a href="#" class="tag-cloud-link">place</a>
					<a href="#" class="tag-cloud-link">drinks</a>
				</div>
			</div>
			<div class="col-md-6 col-lg-3 mb-md-0 mb-4">
				<h2 class="footer-heading">Subcribe</h2>
				<form action="#" class="subscribe-form">
					<div class="form-group d-flex">
						<input type="text" class="form-control rounded-left" placeholder="Enter email address">
						<button type="submit" class="form-control submit rounded-right"><span
								class="sr-only">Submit</span><i class="fa fa-paper-plane"></i></button>
					</div>
				</form>
				<h2 class="footer-heading mt-5">Follow us</h2>
				<ul class="ftco-footer-social p-0">
					<li class="ftco-animate"><a href="#" data-toggle="tooltip" data-placement="top"
							title="Twitter"><span class="fa fa-twitter"></span></a></li>
					<li class="ftco-animate"><a href="#" data-toggle="tooltip" data-placement="top"
							title="Facebook"><span class="fa fa-facebook"></span></a></li>
					<li class="ftco-animate"><a href="#" data-toggle="tooltip" data-placement="top"
							title="Instagram"><span class="fa fa-instagram"></span></a></li>
				</ul>
			</div>
		</div>
	</div>
	<div class="w-100 mt-5 border-top py-5">
		<div class="container">
			<div class="row">
				<div class="col-md-6 col-lg-8">

					<p class="copyright mb-0">
						<!-- Link back to ranavattra can't be removed. Template is licensed under CC BY 3.0. -->
						Copyright &copy;
						<script>document.write(new Date().getFullYear());</script> All rights reserved | This
						template is made with <i class="fa fa-heart" aria-hidden="true"></i> by <a
							href="https://ranavattra.com/hotelbooking/auth/login.php" target="_blank">ranavattra</a>
						<!-- Link back to ranavattra can't be removed. Template is licensed under CC BY 3.0. -->
					</p>
				</div>
				<div class="col-md-6 col-lg-4 text-md-right">
					<p class="mb-0 list-unstyled">
						<a class="mr-md-3" href="#">Terms</a>
						<a class="mr-md-3" href="#">Privacy</a>
						<a class="mr-md-3" href="#">Compliances</a>
					</p>
				</div>
			</div>
		</div>
	</div>
</footer>



<!-- loader -->
<div id="ftco-loader" class="show fullscreen"><svg class="circular" width="48px" height="48px">
		<circle class="path-bg" cx="24" cy="24" r="22" fill="none" stroke-width="4" stroke="#eeeeee" />
		<circle class="path" cx="24" cy="24" r="22" fill="none" stroke-width="4" stroke-miterlimit="10"
			stroke="#F96D00" />
	</svg></div>


<script src="js/jquery.min.js"></script>
<script src="js/jquery-migrate-3.0.1.min.js"></script>
<script src="js/popper.min.js"></script>
<script src="js/bootstrap.min.js"></script>
<script src="js/jquery.easing.1.3.js"></script>
<script src="js/jquery.waypoints.min.js"></script>
<script src="js/jquery.stellar.min.js"></script>
<script src="js/jquery.animateNumber.min.js"></script>
<script src="js/bootstrap-datepicker.js"></script>
<script src="js/jquery.timepicker.min.js"></script>
<script src="js/owl.carousel.min.js"></script>
<script src="js/jquery.magnific-popup.min.js"></script>
<script src="js/scrollax.min.js"></script>
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBVWaKrjvy3MaE7SQ74_uJiULgl1JY0H2s&sensor=false"></script>
<script src="js/google-map.js"></script>
<script src="js/main.js"></script>



</body>

</html>
