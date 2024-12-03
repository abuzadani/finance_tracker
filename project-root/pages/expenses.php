<?php 
include '../includes/header.php'; 
include '../db/config.php'; 
if (session_status() === PHP_SESSION_NONE) {
	session_start();
}

// Redirect if not logged in
if (!isset($_SESSION['user_id'])) {
	header('Location: login.php');
	exit;
}

$user_id = $_SESSION['user_id'];

// Handle form submission for adding an expense
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_expense'])) {
	$amount = floatval($_POST['amount']);
	$date = $_POST['date'];
	$category_id = intval($_POST['category_id']);
	$description = htmlspecialchars(trim($_POST['description']));

	if ($amount > 0 && !empty($date) && $category_id > 0) {
		$stmt = $pdo->prepare("INSERT INTO Expenses (user_id, category_id, amount, date, description) VALUES (?, ?, ?, ?, ?)");
		if ($stmt->execute([$user_id, $category_id, $amount, $date, $description])) {
			echo "<p>Expense added successfully!</p>";
		} else {
			echo "<p>Failed to add expense. Please try again.</p>";
		}
	} else {
		echo "<p>Please provide valid expense details.</p>";
	}
}

// Handle expense deletion
if (isset($_GET['delete']) && is_numeric($_GET['delete'])) {
	$expense_id = $_GET['delete'];
	$stmt = $pdo->prepare("DELETE FROM Expenses WHERE expense_id = ? AND user_id = ?");
	$stmt->execute([$expense_id, $user_id]);
	echo "<p>Expense deleted successfully!</p>";
}

// Fetch categories for the dropdown
$categories_stmt = $pdo->prepare("SELECT * FROM Categories WHERE user_id = ?");
$categories_stmt->execute([$user_id]);
$categories = $categories_stmt->fetchAll();

// Fetch expenses for the logged-in user
$expenses_stmt = $pdo->prepare("SELECT e.*, c.name AS category_name FROM Expenses e JOIN Categories c ON e.category_id = c.category_id WHERE e.user_id = ?");
$expenses_stmt->execute([$user_id]);
$expenses = $expenses_stmt->fetchAll();
?>

<div class="container comp-card">
	<h2>Manage Expenses</h2>

	<!-- Form to add a new expense -->
	<form class="row g-3" method="POST" action="">
		<div class="col-md-6">
			<label for="amount" class="form-label">Amount:</label>
			<input type="number" step="0.01" class="form-control" id="amount" name="amount" required>
		</div>
		<div class="col-md-6">
			<label for="date" class="form-label">Date:</label>
			<input type="date" class="form-control" id="date" name="date" required>
		</div>
		<div class="col-md-6">
			<label for="category_id" class="form-label">Category:</label>
			<select class="form-select" id="category_id" name="category_id" required>
				<option value="">Select a category</option>
				<?php foreach ($categories as $category): ?>
					<option value="<?php echo $category['category_id']; ?>"><?php echo htmlspecialchars($category['name']); ?></option>
				<?php endforeach; ?>
			</select>
		</div>
		<div class="col-md-12">
			<label for="description" class="form-label">Description (optional):</label>
			<textarea class="form-control" id="description" name="description"></textarea>
		</div>
		<div class="col-12">
			<button type="submit" class="btn btn-primary" name="add_expense">Add Expense</button>
		</div>
	</form>
</div>

<div class="container comp-card">
	<!-- Display existing expenses -->
	<h3>Your Expenses</h3>
	<table class="table table-striped">
		<thead class="table-dark">
			<tr>
				<th>Date</th>
				<th>Amount</th>
				<th>Category</th>
				<th>Description</th>
				<th>Actions</th>
			</tr>
		</thead>
		<tbody>
			<?php if (count($expenses) > 0): ?>
				<?php foreach ($expenses as $expense): ?>
					<tr>
						<td><?php echo htmlspecialchars($expense['date']); ?></td>
						<td><?php echo htmlspecialchars(number_format($expense['amount'], 2)); ?></td>
						<td><?php echo htmlspecialchars($expense['category_name']); ?></td>
						<td><?php echo htmlspecialchars($expense['description']); ?></td>
						<td>
							<a href="expenses.php?delete=<?php echo $expense['expense_id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this expense?');">Delete</a>
						</td>
					</tr>
				<?php endforeach; ?>
			<?php else: ?>
				<tr>
					<td colspan="5" class="text-center">No expenses found.</td>
				</tr>
			<?php endif; ?>
		</tbody>
	</table>
</div>

<?php include '../includes/footer.php'; ?>
