<?php 
include '../includes/header.php'; 
?>

<div class="container comp-card">
	<h2>Contact Us</h2>
	<p class="mb-4">We would love to hear from you. Please fill out the form below to get in touch.</p>

	<!-- Contact Form -->
	<form class="row g-3" method="POST" action="process_contact.php">
		<div class="col-md-6">
			<label for="name" class="form-label">Name:</label>
			<input type="text" class="form-control" id="name" name="name" required>
		</div>
		<div class="col-md-6">
			<label for="email" class="form-label">Email:</label>
			<input type="email" class="form-control" id="email" name="email" required>
		</div>
		<div class="col-md-12">
			<label for="subject" class="form-label">Subject:</label>
			<input type="text" class="form-control" id="subject" name="subject" required>
		</div>
		<div class="col-md-12">
			<label for="message" class="form-label">Message:</label>
			<textarea class="form-control" id="message" name="message" rows="5" required></textarea>
		</div>
		<div class="col-12">
			<button type="submit" class="btn btn-primary">Send Message</button>
		</div>
	</form>
</div>

<?php include '../includes/footer.php'; ?>
