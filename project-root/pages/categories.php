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

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_category'])) {
    $name = htmlspecialchars(trim($_POST['name']));
    $description = htmlspecialchars(trim($_POST['description']));

    if (!empty($name)) {
        $stmt = $pdo->prepare("INSERT INTO Categories (user_id, name, description) VALUES (?, ?, ?)");
        if ($stmt->execute([$user_id, $name, $description])) {
            echo "<div class='alert alert-success'>Category added successfully!</div>";
        } else {
            echo "<div class='alert alert-danger'>Failed to add category. Please try again.</div>";
        }
    } else {
        echo "<div class='alert alert-warning'>Category name is required.</div>";
    }
}

if (isset($_GET['delete']) && is_numeric($_GET['delete'])) {
    $category_id = $_GET['delete'];
    $stmt = $pdo->prepare("DELETE FROM Categories WHERE category_id = ? AND user_id = ?");
    $stmt->execute([$category_id, $user_id]);
    echo "<div class='alert alert-success'>Category deleted successfully!</div>";
}

$stmt = $pdo->prepare("SELECT * FROM Categories WHERE user_id = ?");
$stmt->execute([$user_id]);
$categories = $stmt->fetchAll();
?>

<!-- Form to add new category -->
<div class="container comp-card">
 <h2>Manage Categories</h2>


 <form class="row g-3" method="POST" action="">
     <div class="col-md-6">
         <label for="name" class="form-label">Category Name:</label>
         <input type="text" class="form-control" id="name" name="name" required>
     </div>
     <div class="col-md-12">
         <label for="description" class="form-label">Description (optional):</label>
         <textarea class="form-control" id="description" name="description"></textarea>
     </div>
     <div class="col-12">
         <button type="submit" class="btn btn-primary" name="add_category">Add Category</button>
     </div>
 </form>
</div>

<!-- Display existing categories -->
<div class="container comp-card">
	<h3 class="mt-4">Your Categories</h3>
	<table class="table table-striped">
		<thead class="table-dark">
			<tr>
				<th>Name</th>
				<th>Description</th>
				<th>Actions</th>
			</tr>
		</thead>
		<tbody>
			<?php if (count($categories) > 0): ?>
				<?php foreach ($categories as $category): ?>
					<tr>
						<td><?php echo htmlspecialchars($category['name']); ?></td>
						<td><?php echo htmlspecialchars($category['description']); ?></td>
						<td>
							<a href="categories.php?delete=<?php echo $category['category_id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this category?');">Delete</a>
						</td>
					</tr>
				<?php endforeach; ?>
			<?php else: ?>
				<tr>
					<td colspan="3" class="text-center">No categories found.</td>
				</tr>
			<?php endif; ?>
		</tbody>
	</table>
</div>
<?php include '../includes/footer.php'; ?>
