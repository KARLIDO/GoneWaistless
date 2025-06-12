<?php
require_once 'includes/config.php';
require_once 'includes/auth_functions.php';

if (!isAdmin()) {
    die(json_encode(['success' => false, 'message' => 'Unauthorized access']));
}

$imagePath = $_GET['image_path'] ?? '';
$productId = $_GET['product_id'] ?? 0;

try {
    // Delete from database
    $stmt = $pdo->prepare("DELETE FROM product_images WHERE product_id = ? AND image_path = ? AND is_primary = 0");
    $stmt->execute([$productId, $imagePath]);
    
    // Delete the actual file
    if (file_exists('../' . $imagePath)) {
        unlink('../' . $imagePath);
    }
    
    // Get remaining images
    $stmt = $pdo->prepare("SELECT image_path FROM product_images WHERE product_id = ? AND is_primary = 0");
    $stmt->execute([$productId]);
    $remainingImages = $stmt->fetchAll(PDO::FETCH_COLUMN);
    
    echo json_encode([
        'success' => true,
        'remainingImages' => $remainingImages
    ]);
} catch (PDOException $e) {
    echo json_encode([
        'success' => false,
        'message' => 'Database error: ' . $e->getMessage()
    ]);
}