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

// Fetch user information
$stmt = $pdo->prepare("SELECT username, email FROM Users WHERE user_id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch();

// Handle profile update (username, email, and password)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = htmlspecialchars(trim($_POST['username']));
    $email = htmlspecialchars(trim($_POST['email']));
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    
    // Validate password fields
    if (!empty($password) || !empty($confirm_password)) {
        if ($password !== $confirm_password) {
            echo "<div class='alert alert-warning'>Passwords do not match. Please try again.</div>";
        } else {
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        }
    } else {
        $hashed_password = null; // No password change
    }

    // Update user information
    $stmt = $pdo->prepare("UPDATE Users SET username = ?, email = ?, password = COALESCE(?, password) WHERE user_id = ?");
    if ($stmt->execute([$username, $email, $hashed_password, $user_id])) {
        echo "<div class='alert alert-success'>Profile updated successfully!</div>";
        header("Refresh:2"); // Refresh the page to show updated data
    } else {
        echo "<div class='alert alert-danger'>Failed to update profile. Please try again.</div>";
    }
}
?>

<h2>Your Profile</h2>

<!-- Profile Update Form -->
<form class="row g-3" method="POST" action="">
    <div class="col-md-6">
        <label for="username" class="form-label">Username:</label>
        <input type="text" class="form-control" id="username" name="username" value="<?php echo htmlspecialchars($user['username']); ?>" required>
    </div>
    <div class="col-md-6">
        <label for="email" class="form-label">Email:</label>
        <input type="email" class="form-control" id="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>
    </div>

    <!-- Password Update Fields -->
    <div class="col-md-6">
        <label for="password" class="form-label">New Password (Leave empty to keep current):</label>
        <input type="password" class="form-control" id="password" name="password">
    </div>
    <div class="col-md-6">
        <label for="confirm_password" class="form-label">Confirm New Password:</label>
        <input type="password" class="form-control" id="confirm_password" name="confirm_password">
    </div>

    <div class="col-12">
        <button type="submit" class="btn btn-primary">Update Profile</button>
    </div>
</form>

<?php include '../includes/footer.php'; ?>
