<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user_id']) && isset($_COOKIE['user_id'])) {
    $_SESSION['user_id'] = $_COOKIE['user_id'];
    $_SESSION['username'] = $_COOKIE['username'];
}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Personal Finance Tracker</title>
    <link rel="stylesheet" href="../assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="../assets/css/print.css" media="print">

</head>
<body>

<div class="container" id="main-header">
    <header class="custom-header d-flex flex-wrap align-items-center justify-content-center justify-content-md-between py-3 mb-4 border-bottom">
        <!-- Logo and Brand Name -->
        <a href="home.php" class="d-flex align-items-center col-md-3 mb-2 mb-md-0 text-dark text-decoration-none">
            <img src="../assets/img/logo.png" alt="Logo" width="40" height="40" class="me-2">
            <span class="fs-4 fw-bold">Finance Tracker</span>
        </a>

        <!-- Navigation -->
        <ul class="nav col-12 col-md-auto mb-2 justify-content-center mb-md-0">
            <!-- Finance Tracker Dropdown -->
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" id="financeDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                    Finance Tracker
                </a>
                <ul class="dropdown-menu" aria-labelledby="financeDropdown">
                    <li><a href="dashboard.php" class="dropdown-item">Dashboard</a></li>
                    <li><a href="expenses.php" class="dropdown-item">Expenses</a></li>
                    <li><a href="categories.php" class="dropdown-item">Categories</a></li>
                    <li><a href="budget.php" class="dropdown-item">Budget</a></li>
                    <li><a href="report.php" class="dropdown-item">Report</a></li>
                </ul>
            </li>

            <!-- Other Pages -->
            <li><a href="gallery.php" class="nav-link px-3 custom-nav-link">Gallery</a></li>
            <li><a href="feedback.php" class="nav-link px-3 custom-nav-link">Feedback</a></li>
            <li><a href="contact.php" class="nav-link px-3 custom-nav-link">Contact Us</a></li>

            <!-- Additional Links (Resume, Video) -->
            <li><a href="resume.php" class="nav-link px-3 custom-nav-link">Resume</a></li>
            <li><a href="video.php" class="nav-link px-3 custom-nav-link">Video</a></li>
        </ul>

        <!-- Login/Logout Buttons -->
        <div class="col-md-3 text-end">
            <?php if (isset($_SESSION['user_id'])): ?>
                <a href="logout.php" class="btn btn-outline-danger custom-btn">Logout (<?php echo htmlspecialchars($_SESSION['username']); ?>)</a>
                <a href="profile.php" class="btn btn-primary custom-btn">profile</a>
            <?php else: ?>
                <a href="login.php" class="btn btn-outline-primary me-2 custom-btn">Login</a>
                <a href="register.php" class="btn btn-primary custom-btn">Sign-up</a>
            <?php endif; ?>
        </div>
    </header>
</div>

<main class="container py-4 px-4 custom-header mb-4 border-bottom border-top">
