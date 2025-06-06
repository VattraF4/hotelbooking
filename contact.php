<?php
// Include the main header
require 'include/header.php';

//-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-
// IMPORTANT: Include the Composer autoloader to load PHPMailer
//-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-
require 'vendor/autoload.php';

// Import PHPMailer classes into the global namespace
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

// Developer and Project Information
$developer_info = [
	"name" => "Ra Vattra",
	"email" => "ravattrasmartboy@gmail.com", // The email that will receive the message
	"phone" => "+855 969 666 961",
	"address" => "Russian Federation Blvd (110), Phnom Penh 120404",
	"portfolio" => "https://vattraf4.github.io/My-Portfolio",
	"website_url" => "https://ranavattra.com/hotelbooking/contact.php",
];

// SMTP Credentials from your setup
$smtp_credentials = [
	'host' => 'mail.ranavattra.com',
	'username' => 'ra.vattra.official@ranavattra.com', // This will be the "From" email
	'password' => 'v$Is$0f7s4aC', // 🔒 Best practice: Use environment variables for this!
	'port' => 587
];


// Form submission handling
$form_message = '';
$form_error = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
	// Sanitize user input
	$user_name = htmlspecialchars(strip_tags($_POST['name']));
	$user_email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
	$user_subject = htmlspecialchars(strip_tags($_POST['subject']));
	$user_message = htmlspecialchars(strip_tags($_POST['message']));

	if (!empty($user_name) && filter_var($user_email, FILTER_VALIDATE_EMAIL) && !empty($user_subject) && !empty($user_message)) {

		$mail = new PHPMailer(true);

		try {
			// -- Server settings (using your provided SMTP details) --
			// $mail->SMTPDebug = SMTP::DEBUG_SERVER;                  // Enable verbose debug output for testing
			$mail->isSMTP();
			$mail->Host = $smtp_credentials['host'];
			$mail->SMTPAuth = true;
			$mail->Username = $smtp_credentials['username'];
			$mail->Password = $smtp_credentials['password'];
			$mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
			$mail->Port = $smtp_credentials['port'];

			// -- Recipients --
			$mail->setFrom($smtp_credentials['username'], $user_name); // Sender's email and name
			$mail->addAddress($developer_info['email']);              // Add a recipient (your email)
			$mail->addReplyTo($user_email, $user_name);               // So you can reply directly to the user

			// -- Content --
			$mail->isHTML(true);
			$mail->Subject = 'Contact Form: ' . $user_subject;

			// Professional HTML Body
			// -- Content --
			$mail->isHTML(true);
			$mail->Subject = 'Contact Form Submission: ' . $user_subject;

			// Create a timestamp
			$timestamp = date("F j, Y, g:i a");

			// Using nl2br() on the user message preserves line breaks they may have entered.
			$formatted_message = nl2br($user_message);

			// Improved HTML email body
			$mail->Body = <<<HTML
				<!DOCTYPE html>
				<html lang="en">
				<head>
					<meta charset="UTF-8">
					<meta name="viewport" content="width=device-width, initial-scale=1.0">
					<title>New Contact Form Submission</title>
					<style>
						body {
							font-family: Arial, sans-serif;
							background-color: #f4f4f4;
							margin: 0;
							padding: 0;
						}
						.container {
							max-width: 600px;
							margin: 20px auto;
							background-color: #ffffff;
							border: 1px solid #dddddd;
							border-radius: 5px;
							overflow: hidden;
						}
						.header {
							background-color: #0d6efd;
							color: #ffffff;
							padding: 20px;
							text-align: center;
						}
						.header h2 {
							margin: 0;
						}
						.content {
							padding: 30px;
							line-height: 1.6;
							color: #333333;
						}
						.content table {
							width: 100%;
							border-collapse: collapse;
						}
						.content td {
							padding: 10px 0;
							border-bottom: 1px solid #eeeeee;
						}
						.content td.label {
							font-weight: bold;
							width: 100px;
							color: #555555;
						}
						.message-box {
							background-color: #f9f9f9;
							border: 1px solid #eeeeee;
							padding: 15px;
							margin-top: 20px;
							border-radius: 4px;
						}
						.footer {
							background-color: #f4f4f4;
							color: #888888;
							font-size: 12px;
							text-align: center;
							padding: 20px;
						}
					</style>
				</head>
				<body>
					<div class='container'>
						<div class='header'>
							<h2>New Website Message</h2>
						</div>
						<div class='content'>
							<p>You have received a new message from your website's contact form.</p>
							<table>
								<tr>
									<td class='label'>From:</td>
									<td>{$user_name}</td>
								</tr>
								<tr>
									<td class='label'>Email:</td>
									<td>{$user_email}</td>
								</tr>
								<tr>
									<td class='label'>Subject:</td>
									<td>{$user_subject}</td>
								</tr>
							</table>
							<div class='message-box'>
								<strong>Message:</strong><br>
								{$formatted_message}
							</div>
						</div>
						<div class='footer'>
							<p>This email was sent from the Vacation Rental contact form on {$timestamp}.</p>
						</div>
					</div>
				</body>
				</html>
HTML;

			// Create a plain text version for non-HTML mail clients
			$mail->AltBody = "You have received a new message.\n\n" .
				"Name: {$user_name}\n" .
				"Email: {$user_email}\n" .
				"Subject: {$user_subject}\n\n" .
				"Message:\n{$user_message}\n\n" .
				"Sent on: {$timestamp}";

			// Plain text version for non-HTML mail clients
			$mail->AltBody = "Name: {$user_name}\nEmail: {$user_email}\nSubject: {$user_subject}\n\nMessage:\n{$user_message}";

			$mail->send();
			$form_message = 'Your message has been sent. Thank you!';

		} catch (Exception $e) {
			$form_error = "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
		}
	} else {
		$form_error = "Please fill out all fields with valid information.";
	}
}
?>

<section class="hero-wrap hero-wrap-2" style="background-image: url('images/image_2.jpg');"
	data-stellar-background-ratio="0.5">
	<div class="overlay"></div>
	<div class="container">
		<div class="row no-gutters slider-text align-items-center justify-content-center">
			<div class="col-md-9 ftco-animate text-center">
				<p class="breadcrumbs mb-2"><span class="mr-2"><a href="index.php">Home <i
								class="fa fa-chevron-right"></i></a></span> <span>Contact <i
							class="fa fa-chevron-right"></i></span></p>
				<h1 class="mb-0 bread">Contact Us</h1>
			</div>
		</div>
	</div>
</section>

<section class="ftco-section bg-light">
	<div class="container">
		<div class="row no-gutters">
			<div class="col-md-8">
				<iframe
					src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3908.771248043235!2d104.88833431526012!3d11.568341447225883!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3109519fe4077d69%3A0x81c1063261394043!2sRoyal%20University%20of%20Phnom%20Penh!5e0!3m2!1sen!2skh!4v1678886789012!5m2!1sen!2skh"
					width="100%" height="550" style="border:0;" allowfullscreen="" loading="lazy"
					referrerpolicy="no-referrer-when-downgrade"></iframe>
			</div>
			<div class="col-md-4 p-4 p-md-5 bg-white">
				<h2 class="font-weight-bold mb-4">About The Developer</h2>
				<p><strong><?php echo $developer_info['name']; ?></strong> is a dedicated and skilled developer
					currently studying Computer Science & Engineering at RUPP. He has experience in building web systems
					using PHP, MySQL, and Bootstrap.</p>
				<p><a href="<?php echo htmlspecialchars($developer_info['portfolio']); ?>" target="_blank"
						class="btn btn-primary">View Portfolio</a></p>
			</div>

			<div class="col-md-12">
				<div class="wrapper">
					<div class="row no-gutters">
						<div class="col-lg-8 col-md-7 d-flex align-items-stretch">
							<div class="contact-wrap w-100 p-md-5 p-4">
								<h3 class="mb-4">Get in touch</h3>

								<?php if ($form_message): ?>
									<div class="alert alert-success" role="alert">
										<?php echo $form_message; ?>
									</div>
								<?php endif; ?>
								<?php if ($form_error): ?>
									<div class="alert alert-danger" role="alert">
										<?php echo $form_error; ?>
									</div>
								<?php endif; ?>

								<form method="POST" id="contactForm" class="contactForm"
									action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
									<div class="row">
										<div class="col-md-6">
											<div class="form-group">
												<label class="label" for="name">Full Name</label>
												<input type="text" class="form-control" name="name" id="name"
													placeholder="Name" required>
											</div>
										</div>
										<div class="col-md-6">
											<div class="form-group">
												<label class="label" for="email">Email Address</label>
												<input type="email" class="form-control" name="email" id="email"
													placeholder="Email" required>
											</div>
										</div>
										<div class="col-md-12">
											<div class="form-group">
												<label class="label" for="subject">Subject</label>
												<input type="text" class="form-control" name="subject" id="subject"
													placeholder="Subject" required>
											</div>
										</div>
										<div class="col-md-12">
											<div class="form-group">
												<label class="label" for="message">Message</label>
												<textarea name="message" class="form-control" id="message" cols="30"
													rows="4" placeholder="Message" required></textarea>
											</div>
										</div>
										<div class="col-md-12">
											<div class="form-group">
												<input type="submit" value="Send Message" class="btn btn-primary">
											</div>
										</div>
									</div>
								</form>
							</div>
						</div>
						<div class="col-lg-4 col-md-5 d-flex align-items-stretch">
							<div class="info-wrap bg-primary w-100 p-md-5 p-4">
								<h3>Contact Information</h3>
								<p class="mb-4">We're open for any suggestion or just to have a chat.</p>
								<div class="dbox w-100 d-flex align-items-start">
									<div class="icon d-flex align-items-center justify-content-center"><span
											class="fa fa-map-marker"></span></div>
									<div class="text pl-3">
										<p><span>Address:</span> <?php echo $developer_info['address']; ?></p>
									</div>
								</div>
								<div class="dbox w-100 d-flex align-items-center">
									<div class="icon d-flex align-items-center justify-content-center"><span
											class="fa fa-phone"></span></div>
									<div class="text pl-3">
										<p><span>Phone:</span> <a
												href="tel://<?php echo str_replace(' ', '', $developer_info['phone']); ?>"><?php echo $developer_info['phone']; ?></a>
										</p>
									</div>
								</div>
								<div class="dbox w-100 d-flex align-items-center">
									<div class="icon d-flex align-items-center justify-content-center"><span
											class="fa fa-paper-plane"></span></div>
									<div class="text pl-3">
										<p><span>Email:</span> <a
												href="mailto:<?php echo $developer_info['email']; ?>"><?php echo $developer_info['email']; ?></a>
										</p>
									</div>
								</div>
								<div class="dbox w-100 d-flex align-items-center">
									<div class="icon d-flex align-items-center justify-content-center"><span
											class="fa fa-globe"></span></div>
									<div class="text pl-3">
										<p><span>Website:</span> <a href="<?php echo $developer_info['website_url']; ?>"
												target="_blank">View Project</a></p>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</section>

<?php
// Include the footer
require 'include/footer.php';
?>