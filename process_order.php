<?php
// process_payment.php (New file)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();
require_once 'includes/config.php'; // For database connection if needed

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method.']);
    exit();
}

$input = json_decode(file_get_contents('php://input'), true);

if (!isset($input['total_amount'], $input['order_id'], $input['name'], $input['email'])) {
    echo json_encode(['status' => 'error', 'message' => 'Missing required payment data.']);
    exit();
}

$total_amount = $input['total_amount'];
$order_id = $input['order_id'];
$name = $input['name'];
$email = $input['email'];
$phone = $input['phone'];
$province = $input['province'];
$city = $input['city'];
$address = $input['address'];
$shipping_fee = $input['shipping_fee'];
$cart_items = $input['cart_items'];

// --- IMPORTANT: Store order details in your database here BEFORE redirecting to PayFast ---
// This is crucial for reconciling payments and order fulfillment.
// Example:
/*
$stmt = $pdo->prepare("INSERT INTO orders (order_id, customer_name, customer_email, total_amount, status) VALUES (?, ?, ?, ?, 'pending')");
$stmt->execute([$order_id, $name, $email, $total_amount]);
*/


// PayFast parameters (These would typically be stored in a config file and NOT in client-side JS)
$merchant_id = '********'; // REPLACE WITH YOUR ACTUAL MERCHANT ID
$merchant_key = '*************'; // REPLACE WITH YOUR ACTUAL MERCHANT KEY

$payfast_params = [
    'merchant_id' => $merchant_id,
    'merchant_key' => $merchant_key,
    'return_url' => 'https://distinctafrica.co.za/thank_you.php?order_id=' . $order_id,
    'cancel_url' => 'https://distinctafrica.co.za/checkout.php',
    'notify_url' => 'https://distinctafrica.co.za/payfast_notify.php',
    'name_first' => substr(explode(' ', $name)[0], 0, 100),
    'name_last' => substr(implode(' ', array_slice(explode(' ', $name), 1)), 0, 100),
    'email_address' => substr($email, 0, 255),
    'cell_number' => substr(preg_replace('/[^0-9]/', '', $phone), 0, 20),
    'm_payment_id' => $order_id,
    'amount' => number_format($total_amount, 2, '.', ''),
    'item_name' => 'Order #' . $order_id,
    'item_description' => 'Purchase from Distinct Africa',
    // Add custom fields if needed, e.g., to pass shipping details back in notify
    'custom_int1' => $shipping_fee,
    'custom_str1' => $province,
    'custom_str2' => $city,
    'custom_str3' => $address,
];

// Remove empty values and sort alphabetically
$filtered_params = [];
ksort($payfast_params); // Sort by key
foreach ($payfast_params as $key => $val) {
    if ($val !== '' && $val !== null) {
        $filtered_params[$key] = $val;
    }
}

// Generate signature string (PHP's http_build_query handles rawurlencode correctly)
$signature_string = http_build_query($filtered_params);

// Generate signature
$signature = md5($signature_string);
$filtered_params['signature'] = $signature;

// For PayFast, you typically redirect the user.
// You can either return the parameters for the client to build a form and submit,
// or you can build a form and submit it directly from here if you were to use cURL
// to get a redirect URL from a more complex gateway API.

// For PayFast, the most common approach is to return the parameters and let the client
// redirect by submitting a hidden form.
echo json_encode([
    'status' => 'success',
    'redirect_type' => 'post_to_payfast', // Custom indicator for client-side
    'payfast_params' => $filtered_params
]);

exit();
?>
