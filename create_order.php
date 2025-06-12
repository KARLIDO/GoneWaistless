<?php
// Enable detailed error reporting
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Start output buffering
ob_start();

// Set JSON header FIRST
header('Content-Type: application/json');

// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Simple debug logging
file_put_contents('order_debug.log', date('Y-m-d H:i:s')." - Starting order processing\n", FILE_APPEND);

// Verify required files exist
if (!file_exists('includes/config.php')) {
    echo json_encode(['success' => false, 'message' => 'System configuration missing']);
    exit();
}

require_once 'includes/config.php';

// Verify database connection
if (!isset($pdo) || !($pdo instanceof PDO)) {
    echo json_encode(['success' => false, 'message' => 'Database connection failed']);
    exit();
}

// Verify cart exists
if (empty($_SESSION['cart'])) {
    echo json_encode(['success' => false, 'message' => 'Your cart is empty']);
    exit();
}

// Validate CSRF token
if (!isset($_POST['csrf_token']) || !isset($_SESSION['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
    echo json_encode(['success' => false, 'message' => 'Security token mismatch']);
    exit();
}

// Process input data
$required = ['name', 'email', 'phone', 'province', 'address', 'shipping_fee', 'total_amount'];
foreach ($required as $field) {
    if (!isset($_POST[$field]) || empty($_POST[$field])) {
        echo json_encode(['success' => false, 'message' => "Missing required field: $field"]);
        exit();
    }
}

try {
    $pdo->beginTransaction();
    
    // Insert order
    $stmt = $pdo->prepare("INSERT INTO orders (
        customer_name, customer_email, customer_phone, customer_address,
        province, city, shipping_fee, payment_method, total_amount, status, created_at
    ) VALUES (?, ?, ?, ?, ?, ?, ?, 'payfast', ?, 'pending', NOW())");
    
    $stmt->execute([
        $_POST['name'],
        $_POST['email'],
        $_POST['phone'],
        $_POST['address'],
        $_POST['province'],
        $_POST['city'] ?? null,
        $_POST['shipping_fee'],
        $_POST['total_amount']
    ]);
    
    $order_id = $pdo->lastInsertId();
    
    // Insert items
    $item_stmt = $pdo->prepare("INSERT INTO order_items (
        order_id, product_id, variant_id, product_name, price, color, size, quantity
    ) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    
    foreach ($_SESSION['cart'] as $item) {
        $item_stmt->execute([
            $order_id,
            $item['product_id'] ?? null,
            $item['variant_id'] ?? null,
            $item['name'],
            $item['price'],
            $item['color'] ?? null,
            $item['size'] ?? null,
            $item['quantity']
        ]);
    }
    
    $pdo->commit();
    
    // Clear the cart
    unset($_SESSION['cart']);
    
    // Return success
    echo json_encode([
        'success' => true,
        'order_id' => $order_id,
        'amount' => $_POST['total_amount']
    ]);
    
} catch (PDOException $e) {
    $pdo->rollBack();
    file_put_contents('order_debug.log', "Database Error: ".$e->getMessage()."\n", FILE_APPEND);
    echo json_encode(['success' => false, 'message' => 'Database error: '.$e->getMessage()]);
} catch (Exception $e) {
    file_put_contents('order_debug.log', "General Error: ".$e->getMessage()."\n", FILE_APPEND);
    echo json_encode(['success' => false, 'message' => 'Processing error: '.$e->getMessage()]);
}

// Flush output buffer
ob_end_flush();
?>