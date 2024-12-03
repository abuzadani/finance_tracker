<?php 
include '../includes/header.php'; 
include '../db/config.php'; 
if (session_status() === PHP_SESSION_NONE) {
	session_start();
}
if (!isset($_SESSION['user_id'])) {
	header('Location: login.php');
	exit;
}

$user_id = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	$feedback_text = htmlspecialchars(trim($_POST['feedback_text']));
	$rating = intval($_POST['rating']);

	if (empty($feedback_text)) {
		echo "<div class='alert alert-warning'>Please provide your feedback.</div>";
	} elseif ($rating < 1 || $rating > 5) {
		echo "<div class='alert alert-warning'>Please select a valid rating between 1 and 5.</div>";
	} else {
		$stmt = $pdo->prepare("INSERT INTO Feedback (user_id, feedback_text, rating) VALUES (?, ?, ?)");
		if ($stmt->execute([$user_id, $feedback_text, $rating])) {
			echo "<div class='alert alert-success'>Thank you for your feedback!</div>";
		} else {
			echo "<div class='alert alert-danger'>Something went wrong. Please try again later.</div>";
		}
	}
}
?>

<div class="container comp-card">
	<h2>Submit Your Feedback</h2>

	<!-- Feedback Form -->
	<form class="row g-3" id="feedbackForm" method="POST" action="">
		<div class="col-md-12">
			<label for="feedback_text" class="form-label">Feedback:</label>
			<textarea class="form-control" id="feedback_text" name="feedback_text" required></textarea>
		</div>
		<div class="col-md-6">
			<label for="rating" class="form-label">Rating:</label>
			<select class="form-select" id="rating" name="rating" required>
				<option value="">Select a rating</option>
				<option value="1">1 - Poor</option>
				<option value="2">2 - Fair</option>
				<option value="3">3 - Good</option>
				<option value="4">4 - Very Good</option>
				<option value="5">5 - Excellent</option>
			</select>
		</div>
		<div class="col-12">
			<button type="submit" class="btn btn-primary">Submit Feedback</button>
		</div>
	</form>
</div>

<script>
// Client-side validation (Optional)
	document.getElementById('feedbackForm').addEventListener('submit', function(event) {
		const feedbackText = document.getElementById('feedback_text').value.trim();
		const rating = document.getElementById('rating').value;

		if (feedbackText.length === 0) {
			alert('Feedback cannot be empty.');
			event.preventDefault();
		}

		if (rating === '') {
			alert('Please select a rating.');
			event.preventDefault();
		}
	});
</script>

<?php include '../includes/footer.php'; ?>
