<?php
require_once 'includes/config.php';

if (!isset($_GET['product_id'])) {
    die(json_encode(['success' => false, 'message' => 'Product ID required']));
}

$productId = (int)$_GET['product_id'];
$is_primary = isset($_GET['is_primary']) ? (int)$_GET['is_primary'] : 0;

try {
    $stmt = $pdo->prepare("SELECT id, image_path FROM product_images 
                          WHERE product_id = ? AND is_primary = ?");
    $stmt->execute([$productId, $is_primary]);
    $images = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo json_encode([
        'success' => true,
        'images' => $images
    ]);
} catch (PDOException $e) {
    echo json_encode([
        'success' => false,
        'message' => 'Database error: ' . $e->getMessage()
    ]);
}