<?php
session_start();
require_once 'includes/config.php';
error_reporting(E_ALL);
ini_set('display_errors', 1);
// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

try {
    // Get user data
    $stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
    $stmt->execute([$_SESSION['user_id']]);
    $user = $stmt->fetch();

    if (!$user || !$user['is_admin']) {
        header('Location: index.php');
        exit;
    }

// Set active tab
$active_tab = isset($_GET['tab']) ? $_GET['tab'] : 'products';

// Handle product sale toggle
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['toggle_sale'])) {
    $product_id = $_POST['product_id'];
    $sale_price = $_POST['sale_price'];
    
    // Validate sale price
    if (!is_numeric($sale_price) || $sale_price < 0) {
        $error = "Invalid sale price";
    } else {
        // Get product to compare prices
        $stmt = $pdo->prepare("SELECT price FROM products WHERE id = ?");
        $stmt->execute([$product_id]);
        $product = $stmt->fetch();
        
        if ($product && $sale_price < $product['price']) {
            // Update sale status and price
            $stmt = $pdo->prepare("UPDATE products SET is_on_sale = 1, sale_price = ? WHERE id = ?");
            $stmt->execute([$sale_price, $product_id]);
            $success = "Sale price updated successfully";
        } else {
            // Disable sale if price is invalid
            $stmt = $pdo->prepare("UPDATE products SET is_on_sale = 0, sale_price = NULL WHERE id = ?");
            $stmt->execute([$product_id]);
            $success = "Sale disabled (sale price must be lower than regular price)";
        }
    }
}

// Get products for products tab
if ($active_tab === 'products') {
    $stmt = $pdo->query("SELECT p.*, c.name AS category_name 
                         FROM products p 
                         JOIN categories c ON p.category_id = c.id 
                         ORDER BY p.created_at DESC");
    $products = $stmt->fetchAll();
}

// Get orders for orders tab
if ($active_tab === 'orders') {
    $stmt = $pdo->query("SELECT o.*, 
                         (SELECT COUNT(*) FROM order_items WHERE order_id = o.id) AS item_count 
                         FROM orders o 
                         ORDER BY o.created_at DESC");
    $orders = $stmt->fetchAll();
}
} catch (PDOException $e) {
    error_log("Admin page error: " . $e->getMessage());
    die("An error occurred. Please try again later.");
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Gone Waistless</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="assets/admin.css">
    <link rel="shortcut icon" href="assets/logogw.png">
</head>
<body>
    
        
        <button class="menu-toggle" id="menuToggle">
        <i class="fas fa-bars"></i>
        </button>

    <div class="admin-container">
        <div class="sidebar" id="sidebar">
            <div class="sidebar-header">
                <h3>Gone Waistless</h3>
                
            </div>
            <ul class="sidebar-menu">
                <li class="<?= $active_tab === 'products' ? 'active' : '' ?>">
                    <a href="?tab=products">
                        <i class="fas fa-box"></i>
                        <span>Products</span>
                    </a>
                </li>
                <li class="<?= $active_tab === 'orders' ? 'active' : '' ?>">
                    <a href="?tab=orders">
                        <i class="fas fa-shopping-cart"></i>
                        <span>Orders</span>
                    </a>
                </li>
                <li>
                    <a href="index.php">
                        <i class="fas fa-home"></i>
                        <span>Back to Shop</span>
                    </a>
                </li>
            </ul>
        </div>

        <!-- Main Content -->
        <div class="main-content">
            <div class="content-header">
                <h1><?= ucfirst($active_tab) ?></h1>
                <div class="user-info">
                    <span>Welcome, <?= htmlspecialchars($user['username']) ?></span>
                    <a href="login.php" class="logout-btn">Logout</a>
                </div>
            </div>

            <div class="content-body">
                <?php if (isset($error)): ?>
                    <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
                <?php endif; ?>
                
                <?php if (isset($success)): ?>
                    <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
                <?php endif; ?>

                <?php if ($active_tab === 'products'): ?>
                    <!-- Products Tab -->
                    <div class="card">
                        <div class="card-header">
                            <h2>Product Management</h2>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="product-table">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Name</th>
                                            <th>Category</th>
                                            <th>Price</th>
                                            <th>Sale Price</th>
                                            <th>Status</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($products as $product): ?>
                                            <tr>
                                                <td><?= $product['id'] ?></td>
                                                <td><?= htmlspecialchars($product['name']) ?></td>
                                                <td><?= htmlspecialchars($product['category_name']) ?></td>
                                                <td>R<?= number_format($product['price'], 2) ?></td>
                                                <td>
                                                    <?= $product['is_on_sale'] ? 'R' . number_format($product['sale_price'], 2) : 'N/A' ?>
                                                </td>
                                                <td>
                                                    <span class="status-badge <?= $product['is_on_sale'] ? 'on-sale' : 'regular' ?>">
                                                        <?= $product['is_on_sale'] ? 'On Sale' : 'Regular' ?>
                                                    </span>
                                                </td>
                                                <td>
                                                    <form method="POST" class="sale-form">
                                                        <input type="hidden" name="product_id" value="<?= $product['id'] ?>">
                                                        <div class="form-group">
                                                            <input type="number" name="sale_price" 
                                                                   value="<?= $product['is_on_sale'] ? $product['sale_price'] : '' ?>" 
                                                                   placeholder="Sale Price" step="0.01" min="0" required>
                                                        </div>
                                                        <button type="submit" name="toggle_sale" class="btn <?= $product['is_on_sale'] ? 'btn-danger' : 'btn-success' ?>">
                                                            <?= $product['is_on_sale'] ? 'Remove Sale' : 'Set Sale' ?>
                                                        </button>
                                                    </form>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                <?php elseif ($active_tab === 'orders'): ?>
                    <!-- Orders Tab -->
                    <div class="card">
                        <div class="card-header">
                            <h2>Order Management</h2>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="order-table">
                                    <thead>
                                        <tr>
                                            <th>Order ID</th>
                                            <th>Customer</th>
                                            <th>Date</th>
                                            <th>Items</th>
                                            <th>Total</th>
                                            <th>Status</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <!-- In the orders table section -->
                                        <tbody>
                                            <?php foreach ($orders as $order): ?>
                                                <tr>
                                                    <td data-label="Order ID"><?= $order['id'] ?></td>
                                                    <td data-label="Customer"><?= htmlspecialchars($order['customer_name']) ?></td>
                                                    <td data-label="Date"><?= date('d M Y', strtotime($order['created_at'])) ?></td>
                                                    <td data-label="Items"><?= $order['item_count'] ?></td>
                                                    <td data-label="Total">R<?= number_format($order['total_amount'], 2) ?></td>
                                                    <td data-label="Status">
                                                        <span class="status-badge <?= strtolower($order['status']) ?>">
                                                            <?= htmlspecialchars($order['status']) ?>
                                                        </span>
                                                    </td>
                                                    <td data-label="Actions">
                                                        <a href="generate_pdf.php?order_id=<?= $order['id'] ?>" class="btn btn-primary">
                                                            <i class="fas fa-file-pdf"></i> <span class="action-text">Download PDF</span>
                                                        </a>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

   <script>
    // Toggle sidebar on small screens
    document.getElementById('menuToggle').addEventListener('click', function() {
        document.getElementById('sidebar').classList.toggle('active');
    });

    // Close sidebar when a menu item is clicked (for mobile)
    document.querySelectorAll('.sidebar-menu a').forEach(item => {
        item.addEventListener('click', function() {
            if (window.innerWidth <= 992) {
                document.getElementById('sidebar').classList.remove('active');
            }
        });
    });
</script>

</body>
</html>