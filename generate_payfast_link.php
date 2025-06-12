<?php
header('Content-Type: application/json');

try {
    require_once 'includes/config.php';
    
    // Validate required parameters
    $required = ['order_id', 'amount', 'email_address'];
    foreach ($required as $field) {
        if (empty($_POST[$field])) {
            throw new Exception("Missing required field: $field");
        }
    }

    // Basic parameter processing
    $order_id = $_POST['order_id'];
    $amount = number_format((float)$_POST['amount'], 2, '.', '');
    
    // Prepare parameters - only include what you need
    $payfast_params = [
        'merchant_id' => PAYFAST_MERCHANT_ID,
        'merchant_key' => PAYFAST_MERCHANT_KEY,
        'return_url' => 'https://distinctafrica.co.za/thank_you.php?order_id='.$order_id,
        'cancel_url' => 'https://distinctafrica.co.za/checkout.php',
        'notify_url' => 'https://distinctafrica.co.za/payfast_notify.php',
        'name_first' => substr(trim($_POST['name_first'] ?? ''), 0, 100),
        'name_last' => substr(trim($_POST['name_last'] ?? ''), 0, 100),
        'email_address' => substr(trim($_POST['email_address']), 0, 255),
        'cell_number' => trim($_POST['cell_number'] ?? ''),
        'm_payment_id' => $order_id,
        'amount' => $amount,
        'item_name' => 'Order #'.$order_id,
        'item_description' => 'Purchase from Distinct Africa'
    ];

    // Remove empty values (but keep zero values)
    $payfast_params = array_filter($payfast_params, function($value) {
        return $value !== '' && $value !== null;
    });

    // Sort parameters alphabetically (critical)
    ksort($payfast_params);

    // Build signature string
    $signature_parts = [];
    foreach ($payfast_params as $key => $val) {
        $signature_parts[] = $key.'='.rawurlencode(trim($val));
    }
    $signature_string = implode('&', $signature_parts);
    
    // Generate signature (no passphrase involved)
    $signature = md5($signature_string);
    $payfast_params['signature'] = $signature;

    // Generate payment URL
    $payment_url = 'https://www.payfast.co.za/eng/process?'.http_build_query($payfast_params);
    
    // Return response
    echo json_encode([
        'success' => true,
        'payment_url' => $payment_url
    ]);
    
} catch (Exception $e) {
    error_log("PayFast Error: " . $e->getMessage());
    echo json_encode([
        'success' => false,
        'message' => 'Payment processing error: ' . $e->getMessage()
    ]);
}
?>