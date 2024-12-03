<?php
include '../includes/header.php';
include '../db/config.php'; 

// Start session if not started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Check if user is already logged in via session
if (isset($_SESSION['user_id'])) {
    header('Location: dashboard.php');
    exit;
}

// Check if user is logged in via cookies (Remember Me feature)
if (isset($_COOKIE['user_id']) && isset($_COOKIE['username'])) {
    $_SESSION['user_id'] = $_COOKIE['user_id'];
    $_SESSION['username'] = $_COOKIE['username'];
    header('Location: dashboard.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = htmlspecialchars(trim($_POST['username']));
    $password = htmlspecialchars(trim($_POST['password']));

    $stmt = $pdo->prepare("SELECT * FROM Users WHERE username = ?");
    $stmt->execute([$username]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['user_id'];
        $_SESSION['username'] = $user['username'];

        // Set a persistent session if "Remember Me" is checked
        if (isset($_POST['remember_me'])) {
            setcookie('user_id', $user['user_id'], time() + (86400 * 30), "/");
            setcookie('username', $user['username'], time() + (86400 * 30), "/");
        }

        header('Location: dashboard.php');
        exit;
    } else {
        echo "<div class='alert alert-danger text-center'>Invalid username or password.</div>";
    }
}
?>

<section class="bg-light py-5">
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-md-8 col-lg-6">
        <div class="card shadow-lg">
          <div class="card-body p-4">
            <h2 class="text-center mb-4">Login</h2>
            <form method="POST" action="">
              <div class="mb-3">
                <label for="username" class="form-label">Username</label>
                <input type="text" id="username" name="username" class="form-control" placeholder="Enter your username" required>
              </div>

              <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" id="password" name="password" class="form-control" placeholder="Enter your password" required>
              </div>

              <div class="mb-3 form-check">
                <input type="checkbox" class="form-check-input" id="remember_me" name="remember_me">
                <label class="form-check-label" for="remember_me">Remember Me</label>
              </div>

              <div class="d-grid">
                <button type="submit" class="btn btn-primary">Login</button>
              </div>
            </form>

            <p class="text-center mt-3">
              Don't have an account? <a href="register.php" class="text-decoration-none">Register here</a>.
            </p>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<?php include '../includes/footer.php'; ?>
