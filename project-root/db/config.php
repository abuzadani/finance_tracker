<?php
$host = 'autorack.proxy.rlwy.net:55526/railway';
$dbname = 'finance_tracker';
$username = 'root';
$password = 'NefMhvUCYXtnYzVdbGnPdbUiPRgsYFZG';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}
?>
