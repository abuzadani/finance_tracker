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

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_budget'])) {
	$category_id = intval($_POST['category_id']);
	$amount = floatval($_POST['amount']);
	$start_date = $_POST['start_date'];
	$end_date = $_POST['end_date'];

	if ($amount > 0 && !empty($start_date) && !empty($end_date) && $category_id > 0) {
		$stmt = $pdo->prepare("INSERT INTO Budgets (user_id, category_id, amount, start_date, end_date) VALUES (?, ?, ?, ?, ?)");
		if ($stmt->execute([$user_id, $category_id, $amount, $start_date, $end_date])) {
			echo "<div class='alert alert-success'>Budget added successfully!</div>";
		} else {
			echo "<div class='alert alert-danger'>Failed to add budget. Please try again.</div>";
		}
	} else {
		echo "<div class='alert alert-warning'>Please provide valid budget details.</div>";
	}
}

if (isset($_GET['delete']) && is_numeric($_GET['delete'])) {
	$budget_id = $_GET['delete'];
	$stmt = $pdo->prepare("DELETE FROM Budgets WHERE budget_id = ? AND user_id = ?");
	$stmt->execute([$budget_id, $user_id]);
	echo "<div class='alert alert-success'>Budget deleted successfully!</div>";
}

$categories_stmt = $pdo->prepare("SELECT * FROM Categories WHERE user_id = ?");
$categories_stmt->execute([$user_id]);
$categories = $categories_stmt->fetchAll();

$budgets_stmt = $pdo->prepare("SELECT b.*, c.name AS category_name FROM Budgets b JOIN Categories c ON b.category_id = c.category_id WHERE b.user_id = ?");
$budgets_stmt->execute([$user_id]);
$budgets = $budgets_stmt->fetchAll();
?>

<div class="container comp-card">
	<h2>Manage Budgets</h2>

	<!-- Form to add a new budget -->
	<form class="row g-3" method="POST" action="">
		<div class="col-md-6">
			<label for="category_id" class="form-label">Category:</label>
			<select class="form-select" id="category_id" name="category_id" required>
				<option value="">Select a category</option>
				<?php foreach ($categories as $category): ?>
					<option value="<?php echo $category['category_id']; ?>"><?php echo htmlspecialchars($category['name']); ?></option>
				<?php endforeach; ?>
			</select>
		</div>
		<div class="col-md-6">
			<label for="amount" class="form-label">Amount:</label>
			<input type="number" step="0.01" class="form-control" id="amount" name="amount" required>
		</div>
		<div class="col-md-6">
			<label for="start_date" class="form-label">Start Date:</label>
			<input type="date" class="form-control" id="start_date" name="start_date" required>
		</div>
		<div class="col-md-6">
			<label for="end_date" class="form-label">End Date:</label>
			<input type="date" class="form-control" id="end_date" name="end_date" required>
		</div>
		<div class="col-12">
			<button type="submit" class="btn btn-primary" name="add_budget">Add Budget</button>
		</div>
	</form>
</div>

<div class="container comp-card">
	<!-- Display existing budgets -->
	<h3 class="mt-4">Your Budgets</h3>
	<table class="table table-striped">
		<thead class="table-dark">
			<tr>
				<th>Category</th>
				<th>Amount</th>
				<th>Start Date</th>
				<th>End Date</th>
				<th>Actions</th>
			</tr>
		</thead>
		<tbody>
			<?php if (count($budgets) > 0): ?>
				<?php foreach ($budgets as $budget): ?>
					<tr>
						<td><?php echo htmlspecialchars($budget['category_name']); ?></td>
						<td><?php echo htmlspecialchars(number_format($budget['amount'], 2)); ?></td>
						<td><?php echo htmlspecialchars($budget['start_date']); ?></td>
						<td><?php echo htmlspecialchars($budget['end_date']); ?></td>
						<td>
							<a href="budget.php?delete=<?php echo $budget['budget_id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this budget?');">Delete</a>
						</td>
					</tr>
				<?php endforeach; ?>
			<?php else: ?>
				<tr>
					<td colspan="5" class="text-center">No budgets found.</td>
				</tr>
			<?php endif; ?>
		</tbody>
	</table>
</div>

<?php include '../includes/footer.php'; ?>
