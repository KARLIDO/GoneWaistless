<?php
// This is a simplified version - you should implement proper validation
session_start();
require_once 'includes/config.php';

// Process PayFast ITN (Instant Transaction Notification)
// Refer to PayFast's documentation for complete implementation
// https://developers.payfast.co.za/documentation

// After verifying payment:
if (/* payment is successful */) {
    $order_id = $_POST['m_payment_id'];
    
    try {
        $stmt = $pdo->prepare("UPDATE orders SET status = 'completed' WHERE id = :order_id");
        $stmt->execute([':order_id' => $order_id]);
        
        // Send confirmation email, etc.
    } catch (PDOException $e) {
        // Log error
    }
}

// Always respond to PayFast
header("HTTP/1.0 200 OK");
?>