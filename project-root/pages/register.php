<?php 
include '../includes/header.php'; 
include '../db/config.php'; 

// Check if user is already logged in via session
if (isset($_SESSION['user_id'])) {
    header('Location: dashboard.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = htmlspecialchars(trim($_POST['username']));
    $email = htmlspecialchars(trim($_POST['email']));
    $password = htmlspecialchars(trim($_POST['password']));
    $confirm_password = htmlspecialchars(trim($_POST['confirm_password']));

    if ($password !== $confirm_password) {
        echo "<div class='alert alert-danger text-center'>Passwords do not match.</div>";
    } else {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $pdo->prepare("SELECT email FROM Users WHERE email = ?");
        $stmt->execute([$email]);

        if ($stmt->rowCount() > 0) {
            echo "<div class='alert alert-danger text-center'>Email is already registered. Please use a different email.</div>";
        } else {
            $stmt = $pdo->prepare("INSERT INTO Users (username, email, password) VALUES (?, ?, ?)");
            if ($stmt->execute([$username, $email, $hashed_password])) {
                echo "<div class='alert alert-success text-center'>Registration successful! <a href='login.php'>Login here</a>.</div>";
            } else {
                echo "<div class='alert alert-danger text-center'>Something went wrong. Please try again later.</div>";
            }
        }
    }
}
?>

<section class="bg-light py-5">
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-md-8 col-lg-6">
        <div class="card shadow-lg">
          <div class="card-body p-4">
            <h2 class="text-center mb-4">Register</h2>
            <form method="POST" action="">
              <div class="mb-3">
                <label for="username" class="form-label">Username</label>
                <input type="text" id="username" name="username" class="form-control" placeholder="Enter your username" required>
              </div>

              <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" id="email" name="email" class="form-control" placeholder="Enter your email" required>
              </div>

              <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" id="password" name="password" class="form-control" placeholder="Enter your password" required>
              </div>

              <div class="mb-3">
                <label for="confirm_password" class="form-label">Confirm Password</label>
                <input type="password" id="confirm_password" name="confirm_password" class="form-control" placeholder="Confirm your password" required>
              </div>

              <div class="d-grid">
                <button type="submit" class="btn btn-primary">Register</button>
              </div>
            </form>

            <p class="text-center mt-3">
              Already have an account? <a href="login.php" class="text-decoration-none">Login here</a>.
            </p>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<?php include '../includes/footer.php'; ?>
