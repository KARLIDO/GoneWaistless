<?php
session_start();
require_once 'config.php';
require_once 'auth_functions.php';

header('Content-Type: application/json');

// Verify admin access
if (!isAdmin()) {
    http_response_code(403);
    die(json_encode(['success' => false, 'message' => 'Unauthorized access']));
}

if (!isset($_GET['product_id'])) {
    http_response_code(400);
    die(json_encode(['success' => false, 'message' => 'Product ID required']));
}

try {
    $productId = (int)$_GET['product_id'];
    
    // Get all variants for this product
    $stmt = $pdo->prepare("SELECT * FROM product_variants WHERE product_id = ?");
    $stmt->execute([$productId]);
    $variants = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo json_encode([
        'success' => true,
        'variants' => $variants
    ]);
    
} catch (Exception $e) {
    http_response_code(500);
    error_log("Error getting variants: " . $e->getMessage());
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}