<?php
header('Content-Type: application/json');
session_start();
require_once 'includes/config.php';

// Enable error reporting for debugging (remove in production)
error_reporting(E_ALL);
ini_set('display_errors', 1);

try {
    // Check if request is POST
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        throw new Exception('Invalid request method. Only POST requests are accepted.');
    }
    
    // Verify CSRF token
    if (!isset($_POST['csrf_token'])) {
        throw new Exception('CSRF token missing');
    }
    
    if (!isset($_SESSION['csrf_token'])) {
        throw new Exception('Session CSRF token missing');
    }
    
    if (!hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
        throw new Exception('Invalid CSRF token');
    }
    
    // Check required fields
    $required = ['name', 'email', 'phone', 'address', 'province', 'shipping_fee', 'total_amount'];
    foreach ($required as $field) {
        if (!isset($_POST[$field])) {
            throw new Exception("Missing required field: $field");
        }
        if (empty(trim($_POST[$field]))) {
            throw new Exception("Field cannot be empty: $field");
        }
    }
    
    // Validate email
    if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
        throw new Exception("Invalid email format");
    }
    
    // Validate phone number (basic check)
    $phone = preg_replace('/[^0-9+]/', '', $_POST['phone']);
    if (strlen($phone) < 10) {
        throw new Exception("Phone number must be at least 10 digits");
    }
    
    // Validate total amount
    if (!is_numeric($_POST['total_amount']) || $_POST['total_amount'] <= 0) {
        throw new Exception("Invalid total amount");
    }
    
    // Validate shipping fee
    if (!is_numeric($_POST['shipping_fee']) || $_POST['shipping_fee'] < 0) {
        throw new Exception("Invalid shipping fee");
    }
    
    // Get cart items from session
    if (empty($_SESSION['cart'])) {
        throw new Exception('Cart is empty');
    }
    
    // Calculate total from cart items to verify against posted total
    $calculatedTotal = 0;
    foreach ($_SESSION['cart'] as $item) {
        if (!isset($item['price'], $item['quantity'])) {
            throw new Exception("Invalid cart item data");
        }
        $calculatedTotal += $item['price'] * $item['quantity'];
    }
    $calculatedTotal += $_POST['shipping_fee'];
    
    // Verify calculated total matches posted total (with tolerance for floating point)
    if (abs($calculatedTotal - $_POST['total_amount']) > 0.01) {
        throw new Exception("Order total verification failed");
    }
    
    // Begin transaction
    $pdo->beginTransaction();
    
    // Insert order
    $stmt = $pdo->prepare("
    INSERT INTO orders (
        customer_name, 
        customer_email, 
        customer_phone, 
        customer_address, 
        province, 
        city, 
        shipping_fee, 
        payment_method, 
        total_amount, 
        status,
        created_at
    ) VALUES (?, ?, ?, ?, ?, ?, ?, 'payfast', ?, 'pending', NOW())
");
    
    $stmt->execute([
    htmlspecialchars(trim($_POST['name'])),
    filter_var($_POST['email'], FILTER_SANITIZE_EMAIL),
    htmlspecialchars($phone),
    htmlspecialchars(trim($_POST['address'])),
    htmlspecialchars(trim($_POST['province'])),
    isset($_POST['city']) ? htmlspecialchars(trim($_POST['city'])) : null,
    (float)$_POST['shipping_fee'],
    (float)$_POST['total_amount']
    ]);
    
    $orderId = $pdo->lastInsertId();
    
    // Insert order items
    $stmt = $pdo->prepare("
        INSERT INTO order_items (
            order_id, 
            product_id, 
            variant_id, 
            product_name, 
            price, 
            color, 
            size, 
            quantity
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?)
    ");
    
    foreach ($_SESSION['cart'] as $item) {
        // Validate cart item data
        if (!isset($item['name'], $item['price'], $item['quantity'])) {
            throw new Exception("Invalid cart item data");
        }
        
        $variantId = $item['variant_id'] ?? null;
        $productId = $item['product_id'] ?? null;
        $color = $item['color'] ?? null;
        $size = $item['size'] ?? null;
        
        $stmt->execute([
            $orderId,
            $productId,
            $variantId,
            htmlspecialchars($item['name']),
            (float)$item['price'],
            $color,
            $size,
            (int)$item['quantity']
        ]);
        
        // Update product variant quantity if variant exists
        if ($variantId) {
            $updateStmt = $pdo->prepare("
                UPDATE product_variants 
                SET quantity = quantity - ? 
                WHERE id = ? AND quantity >= ?
            ");
            $updateStmt->execute([(int)$item['quantity'], $variantId, (int)$item['quantity']]);
            
            if ($updateStmt->rowCount() === 0) {
                throw new Exception("Insufficient stock for variant ID: $variantId");
            }
        }
    }
    
    // Commit transaction
    $pdo->commit();
    
    // Return success response with order ID
    echo json_encode([
        'success' => true,
        'order_id' => $orderId,
        'message' => 'Order created successfully'
    ]);
    
} catch (Exception $e) {
    // Roll back transaction if there was an error
    if (isset($pdo) && $pdo->inTransaction()) {
        $pdo->rollBack();
    }
    
    // Log error
    error_log("Order creation failed: " . $e->getMessage());
    
    // Return error response
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}