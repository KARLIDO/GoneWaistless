<?php
session_start();
require_once 'includes/config.php';

// Check if user is logged in and is admin
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$_SESSION['user_id']]);
$user = $stmt->fetch();

if (!$user || !$user['is_admin']) {
    header('Location: index.php');
    exit;
}

// Check if order ID is provided
if (!isset($_GET['order_id'])) {
    header('Location: admin.php?tab=orders');
    exit;
}

$order_id = $_GET['order_id'];

// Get order details
$stmt = $pdo->prepare("SELECT * FROM orders WHERE id = ?");
$stmt->execute([$order_id]);
$order = $stmt->fetch();

if (!$order) {
    header('Location: admin.php?tab=orders');
    exit;
}

// Get order items
$stmt = $pdo->prepare("SELECT * FROM order_items WHERE order_id = ?");
$stmt->execute([$order_id]);
$items = $stmt->fetchAll();

// Include TCPDF library
require_once('tcpdf/tcpdf.php');

// Create new PDF document
$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// Set document information
$pdf->SetCreator('Gone Waistless');
$pdf->SetAuthor('Gone Waistless');
$pdf->SetTitle('Order #' . $order_id);
$pdf->SetSubject('Order Details');

// Set margins
$pdf->SetMargins(15, 15, 15);
$pdf->SetHeaderMargin(5);
$pdf->SetFooterMargin(10);

// Set auto page breaks
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

// Add a page
$pdf->AddPage();

// Set font
$pdf->SetFont('helvetica', '', 10);

// Logo
$pdf->Image('assets/logogw.png', 15, 10, 20, '', 'PNG', '', 'T', false, 300, '', false, false, 0, false, false, false);

// Title
$pdf->SetY(15);
$pdf->SetFont('helvetica', 'B', 16);
$pdf->Cell(0, 10, 'Order Invoice #' . $order_id, 0, 1, 'R');

// Order details
$pdf->SetFont('helvetica', '', 10);
$pdf->Ln(20);

$pdf->Cell(0, 0, 'Customer: ' . $order['customer_name'], 0, 1);
$pdf->Cell(0, 0, 'Email: ' . $order['customer_email'], 0, 1);
$pdf->Cell(0, 0, 'Phone: ' . $order['customer_phone'], 0, 1);
$pdf->Cell(0, 0, 'Date: ' . date('d M Y H:i', strtotime($order['created_at'])), 0, 1);
$pdf->Ln(10);

// Address
$pdf->SetFont('helvetica', 'B', 12);
$pdf->Cell(0, 0, 'Shipping Address', 0, 1);
$pdf->SetFont('helvetica', '', 10);
$pdf->MultiCell(0, 0, $order['customer_address'], 0, 'L');
$pdf->Cell(0, 0, $order['city'] . ', ' . $order['province'], 0, 1);
$pdf->Ln(10);

// Items table header
$pdf->SetFont('helvetica', 'B', 10);
$pdf->Cell(100, 7, 'Product', 1, 0, 'L');
$pdf->Cell(30, 7, 'Price', 1, 0, 'R');
$pdf->Cell(20, 7, 'Qty', 1, 0, 'C');
$pdf->Cell(30, 7, 'Total', 1, 1, 'R');

// Items
$pdf->SetFont('helvetica', '', 10);
foreach ($items as $item) {
    $pdf->Cell(100, 7, $item['product_name'] . ' (' . $item['size'] . ', ' . $item['color'] . ')', 1, 0, 'L');
    $pdf->Cell(30, 7, 'R' . number_format($item['price'], 2), 1, 0, 'R');
    $pdf->Cell(20, 7, $item['quantity'], 1, 0, 'C');
    $pdf->Cell(30, 7, 'R' . number_format($item['price'] * $item['quantity'], 2), 1, 1, 'R');
}

// Summary
$pdf->Ln(10);
$pdf->SetFont('helvetica', 'B', 10);
$pdf->Cell(130, 7, 'Subtotal:', 0, 0, 'R');
$pdf->Cell(30, 7, 'R' . number_format($order['total_amount'] - $order['shipping_fee'], 2), 0, 1, 'R');

$pdf->Cell(130, 7, 'Shipping:', 0, 0, 'R');
$pdf->Cell(30, 7, 'R' . number_format($order['shipping_fee'], 2), 0, 1, 'R');

$pdf->SetFont('helvetica', 'B', 12);
$pdf->Cell(130, 10, 'Total:', 0, 0, 'R');
$pdf->Cell(30, 10, 'R' . number_format($order['total_amount'], 2), 0, 1, 'R');

// Footer
$pdf->SetY(-30);
$pdf->SetFont('helvetica', 'I', 8);
$pdf->Cell(0, 10, 'Thank you for shopping with Gone Waistless!', 0, 1, 'C');
$pdf->Cell(0, 10, 'For any inquiries, please contact sales@gonewaistless.co.za', 0, 1, 'C');

// Output PDF
$pdf->Output('order_' . $order_id . '.pdf', 'D');