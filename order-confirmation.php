<?php require_once 'functions.php';

requireVerifiedUser();

if (!isset($_GET['id'])) {
    header('Location: index.php');
    exit;
}

$orderId = $_GET['id'];
$userId = $_SESSION['user_id'];

// Get order details
$stmt = $pdo->prepare("
    SELECT o.*, 
           CASE 
               WHEN pm.method_type = 'card' THEN CONCAT('Card ending in ', RIGHT(pm.card_number, 4))
               WHEN pm.method_type = 'paypal' THEN CONCAT('PayPal: ', pm.paypal_username)
               WHEN pm.method_type = 'ozow' THEN CONCAT('Ozow: ', pm.ozow_username)
           END as payment_method
    FROM orders o
    JOIN payment_methods pm ON o.payment_method_id = pm.id
    WHERE o.id = ? AND o.user_id = ?
");
$stmt->execute([$orderId, $userId]);
$order = $stmt->fetch();

if (!$order) {
    header('Location: index.php');
    exit;
}

// Get order items
$stmt = $pdo->prepare("
    SELECT oi.*, p.name, p.image_path
    FROM order_items oi
    JOIN products p ON oi.product_id = p.id
    WHERE oi.order_id = ?
");
$stmt->execute([$orderId]);
$orderItems = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Confirmation</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
    <!-- Navigation -->
    <?php include 'navbar.php'; ?>

    <!-- Order Confirmation Section -->
    <section class="order-confirmation py-5">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="card">
                        <div class="card-header bg-success text-white">
                            <h3 class="mb-0 text-center">Order Confirmation</h3>
                        </div>
                        <div class="card-body">
                            <div class="text-center mb-4">
                                <i class="fas fa-check-circle text-success" style="font-size: 5rem;"></i>
                                <h2 class="mt-3">Thank You for Your Order!</h2>
                            </div>
                            
                            <div class="row mb-4">
                                <div class="col-md-6">
                                    <h5>Order Summary</h5>
                                    <p><strong>Order Number:</strong> HC-<?= str_pad($orderId, 6, '0', STR_PAD_LEFT) ?></p>
                                    <p><strong>Date:</strong> <?= date('F j, Y', strtotime($order['created_at'])) ?></p>
                                    <p><strong>Payment Method:</strong> <?= $order['payment_method'] ?></p>
                                </div>
                                <div class="col-md-6">
                                    <h5>Order Total</h5>
                                    <p><strong>Subtotal:</strong> R<?= number_format($order['total_amount'], 2) ?></p>
                                    <p><strong>Shipping:</strong> Free</p>
                                    <p><strong>Total:</strong> R<?= number_format($order['total_amount'], 2) ?></p>
                                </div>
                            </div>
                            
                            <h5 class="mb-3">Order Details</h5>
                            <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>Product</th>
                                            <th>Price</th>
                                            <th>Quantity</th>
                                            <th>Total</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($orderItems as $item): ?>
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <img src="<?= htmlspecialchars($item['image_path']) ?>" width="60" class="me-3">
                                                    <?= htmlspecialchars($item['name']) ?>
                                                </div>
                                            </td>
                                            <td>R<?= number_format($item['price'], 2) ?></td>
                                            <td><?= $item['quantity'] ?></td>
                                            <td>R<?= number_format($item['price'] * $item['quantity'], 2) ?></td>
                                        </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                            
                            <div class="text-center mt-4">
                                <a href="products.php" class="btn btn-primary">Continue Shopping</a>
                                <a href="my-account.php" class="btn btn-outline-secondary ms-2">View Order History</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <?php include 'footer.php'; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
</body>
</html>