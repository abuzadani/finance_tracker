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

// Fetch total expenses and budgets
$total_expenses_stmt = $pdo->prepare("SELECT SUM(amount) AS total_expenses FROM Expenses WHERE user_id = ?");
$total_expenses_stmt->execute([$user_id]);
$total_expenses = $total_expenses_stmt->fetchColumn() ?: 0;

$total_budgets_stmt = $pdo->prepare("SELECT SUM(amount) AS total_budgets FROM Budgets WHERE user_id = ?");
$total_budgets_stmt->execute([$user_id]);
$total_budgets = $total_budgets_stmt->fetchColumn() ?: 0;

// Fetch expenses by category for chart
$expenses_by_category_stmt = $pdo->prepare("
	SELECT c.name AS category, SUM(e.amount) AS total
	FROM Expenses e
	JOIN Categories c ON e.category_id = c.category_id
	WHERE e.user_id = ?
	GROUP BY c.name
	");
$expenses_by_category_stmt->execute([$user_id]);
$expenses_by_category = $expenses_by_category_stmt->fetchAll(PDO::FETCH_ASSOC);

// Fetch recent expenses for the table
$recent_expenses_stmt = $pdo->prepare("SELECT e.*, c.name AS category_name FROM Expenses e JOIN Categories c ON e.category_id = c.category_id WHERE e.user_id = ? ORDER BY e.date DESC LIMIT 5");
$recent_expenses_stmt->execute([$user_id]);
$recent_expenses = $recent_expenses_stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<div class="container comp-card">
	<h2 class="mb-4">Dashboard</h2>

	<!-- Summary Cards -->
	<div class="row">
		<div class="col-md-6">
			<div class="card text-white bg-primary mb-3">
				<div class="card-header">Total Expenses</div>
				<div class="card-body">
					<h5 class="card-title">$<?php echo number_format($total_expenses, 2); ?></h5>
					<p class="card-text">Your total spending to date.</p>
					<a href="expenses.php" class="btn btn-light">Manage Expenses</a>
				</div>
			</div>
		</div>
		<div class="col-md-6">
			<div class="card text-white bg-success mb-3">
				<div class="card-header">Total Budgets</div>
				<div class="card-body">
					<h5 class="card-title">$<?php echo number_format($total_budgets, 2); ?></h5>
					<p class="card-text">Your total budget allocation.</p>
					<a href="budget.php" class="btn btn-light">Manage Budgets</a>
				</div>
			</div>
		</div>
	</div>
</div>
<!-- Chart and Recent Expenses Table in the same row -->
<div class="container comp-card">
	<div class="row mb-4">
		<!-- Expense by Category Chart -->
		<div class="col-md-6">
			<div class="card">
				<div class="card-header">
					Expenses by Category
				</div>
				<div class="card-body">
					<canvas id="expenseChart"></canvas>
				</div>
			</div>
		</div>

		<!-- Recent Expenses Table -->
		<div class="col-md-6">
			<div class="card">
				<div class="card-header">
					Recent Expenses
				</div>
				<div class="card-body">
					<table class="table table-striped">
						<thead class="table-dark">
							<tr>
								<th>Date</th>
								<th>Amount</th>
								<th>Category</th>
								<th>Description</th>
							</tr>
						</thead>
						<tbody>
							<?php if (count($recent_expenses) > 0): ?>
								<?php foreach ($recent_expenses as $expense): ?>
									<tr>
										<td><?php echo htmlspecialchars($expense['date']); ?></td>
										<td>$<?php echo number_format($expense['amount'], 2); ?></td>
										<td><?php echo htmlspecialchars($expense['category_name']); ?></td>
										<td><?php echo htmlspecialchars($expense['description']); ?></td>
									</tr>
								<?php endforeach; ?>
							<?php else: ?>
								<tr>
									<td colspan="4" class="text-center">No recent expenses to show.</td>
								</tr>
							<?php endif; ?>
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
</div>
<script src="../assets/js/chart.js"></script>
<script>
// Prepare data for chart
	const categories = <?php echo json_encode(array_column($expenses_by_category, 'category')); ?>;
	const expenses = <?php echo json_encode(array_column($expenses_by_category, 'total')); ?>;

// Render chart
	const ctx = document.getElementById('expenseChart').getContext('2d');
	new Chart(ctx, {
		type: 'pie',
		data: {
			labels: categories,
			datasets: [{
				data: expenses,
				backgroundColor: ['#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0', '#9966FF'],
			}]
		},
		options: {
			responsive: true,
			plugins: {
				legend: {
					position: 'top',
				},
			},
		}
	});
</script>

<?php include '../includes/footer.php'; ?>
