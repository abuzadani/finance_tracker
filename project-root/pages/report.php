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

// Set default dates for filtering (if not selected)
$start_date = isset($_POST['start_date']) ? $_POST['start_date'] : date('Y-m-01'); // Default to the first of the current month
$end_date = isset($_POST['end_date']) ? $_POST['end_date'] : date('Y-m-d'); // Default to today

// Fetch total expenses and total budgets within the date range
$total_expenses_stmt = $pdo->prepare("SELECT SUM(amount) AS total_expenses FROM Expenses WHERE user_id = ? AND date BETWEEN ? AND ?");
$total_expenses_stmt->execute([$user_id, $start_date, $end_date]);
$total_expenses = $total_expenses_stmt->fetchColumn() ?: 0;

$total_budgets_stmt = $pdo->prepare("SELECT SUM(amount) AS total_budgets FROM Budgets WHERE user_id = ?");
$total_budgets_stmt->execute([$user_id]);
$total_budgets = $total_budgets_stmt->fetchColumn() ?: 0;

// Fetch expenses within the date range
$expenses_stmt = $pdo->prepare("SELECT e.*, c.name AS category_name FROM Expenses e JOIN Categories c ON e.category_id = c.category_id WHERE e.user_id = ? AND e.date BETWEEN ? AND ?");
$expenses_stmt->execute([$user_id, $start_date, $end_date]);
$expenses = $expenses_stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="container comp-card">

	<h2>Expense Report</h2>

	<!-- Date Range Filter Form -->
	<form class="row g-3 mb-4" method="POST" action="" id="expense-filter">
		<div class="col-md-6">
			<label for="start_date" class="form-label">Start Date:</label>
			<input type="date" class="form-control" id="start_date" name="start_date" value="<?php echo htmlspecialchars($start_date); ?>" required>
		</div>
		<div class="col-md-6">
			<label for="end_date" class="form-label">End Date:</label>
			<input type="date" class="form-control" id="end_date" name="end_date" value="<?php echo htmlspecialchars($end_date); ?>" required>
		</div>
		<div class="col-12">
			<button type="submit" class="btn btn-primary">Filter</button>
		</div>
	</form>
</div>

<div class="container comp-card">
	<!-- Summary of Total Expenses and Budgets -->
	<div class="row mb-4" id="total-budget">
		<div class="col-md-6">
			<div class="card text-white bg-primary mb-3">
				<div class="card-header">Total Expenses</div>
				<div class="card-body">
					<h5 class="card-title">$<?php echo number_format($total_expenses, 2); ?></h5>
					<p class="card-text">Your total expenses between <?php echo htmlspecialchars($start_date); ?> and <?php echo htmlspecialchars($end_date); ?>.</p>
				</div>
			</div>
		</div>
		<div class="col-md-6">
			<div class="card text-white bg-success mb-3">
				<div class="card-header">Total Budget</div>
				<div class="card-body">
					<h5 class="card-title">$<?php echo number_format($total_budgets, 2); ?></h5>
					<p class="card-text">Your total budget allocation.</p>
				</div>
			</div>
		</div>
	</div>
</div>

<div class="container comp-card">
	<!-- Expenses Table -->
	<h3 class="mb-4">Your Expenses</h3>
	<table class="table table-striped" id="expensesTable">
		<thead class="table-dark">
			<tr>
				<th>Date</th>
				<th>Amount</th>
				<th>Category</th>
				<th>Description</th>
			</tr>
		</thead>
		<tbody>
			<?php if (count($expenses) > 0): ?>
				<?php foreach ($expenses as $expense): ?>
					<tr>
						<td><?php echo htmlspecialchars($expense['date']); ?></td>
						<td>$<?php echo htmlspecialchars(number_format($expense['amount'], 2)); ?></td>
						<td><?php echo htmlspecialchars($expense['category_name']); ?></td>
						<td><?php echo htmlspecialchars($expense['description']); ?></td>
					</tr>
				<?php endforeach; ?>
			<?php else: ?>
				<tr>
					<td colspan="4" class="text-center">No expenses found for the selected date range.</td>
				</tr>
			<?php endif; ?>
		</tbody>
	</table>

	<!-- Print Button -->
	<button class="btn btn-outline-primary mt-3" onclick="window.print();">Print Report</button>
</div>
<?php include '../includes/footer.php'; ?>
