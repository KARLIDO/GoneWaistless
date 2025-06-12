<?php
session_start();
require_once 'config.php';
require_once 'auth_functions.php';

header('Content-Type: application/json');

// Verify CSRF token
if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== ($_SESSION['csrf_token'] ?? '')) {
    http_response_code(403);
    die(json_encode(['success' => false, 'message' => 'Invalid CSRF token']));
}

// Verify admin access
if (!isAdmin()) {
    http_response_code(403);
    die(json_encode(['success' => false, 'message' => 'Unauthorized access']));
}

// Verify required parameters
if ($_SERVER['REQUEST_METHOD'] !== 'POST' || 
    ($_POST['action'] ?? '') !== 'delete_image' || 
    !isset($_POST['product_id']) || 
    !isset($_POST['image_path'])) {
    http_response_code(400);
    die(json_encode(['success' => false, 'message' => 'Invalid request']));
}

try {
    $productId = (int)$_POST['product_id'];
    $imagePath = $_POST['image_path'];
    
    // Security check: Verify image path is valid
    if (strpos($imagePath, '../') !== false || !preg_match('/^assets\/products\//', $imagePath)) {
        throw new Exception('Invalid image path');
    }

    // Verify the image belongs to the product
    $stmt = $pdo->prepare("SELECT id FROM product_images WHERE product_id = ? AND image_path = ?");
    $stmt->execute([$productId, $imagePath]);
    
    if (!$stmt->fetch()) {
        throw new Exception('Image not found for this product');
    }
    
    // Begin transaction
    $pdo->beginTransaction();
    
    // Delete from database
    $stmt = $pdo->prepare("DELETE FROM product_images WHERE product_id = ? AND image_path = ?");
    $stmt->execute([$productId, $imagePath]);
    
    // Delete file from server
    $fullPath = realpath(__DIR__ . '/../' . $imagePath);
    if (file_exists($fullPath)) {
        if (!unlink($fullPath)) {
            throw new Exception('Failed to delete image file');
        }
    }
    
    $pdo->commit();
    
    echo json_encode(['success' => true, 'message' => 'Image deleted successfully']);
    
} catch (Exception $e) {
    $pdo->rollBack();
    http_response_code(500);
    error_log("Image deletion error: " . $e->getMessage());
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}