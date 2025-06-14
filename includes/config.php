<?php
// Database Configuration
$host = 'localhost';
$dbname = '************';
$username = '*************';
$password = '***********';

// PayFast Configuration
define('PAYFAST_MERCHANT_ID', '********');
define('PAYFAST_MERCHANT_KEY', '*************');

// Enable error reporting for development
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    $pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
} catch (PDOException $e) {
    error_log("Database connection error: " . $e->getMessage());
    die("Connection failed: Please try again later.");
}
?>
