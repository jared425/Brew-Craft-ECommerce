<?php require_once 'functions.php';

requireVerifiedUser();

$userId = $_SESSION['user_id'];

// Get user details
$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$userId]);
$user = $stmt->fetch();

// Get payment methods
$paymentMethods = getUserPaymentMethods($userId);

// Get order history
$stmt = $pdo->prepare("
    SELECT o.id, o.created_at, o.total_amount, o.status, 
           COUNT(oi.id) as item_count 
    FROM orders o
    JOIN order_items oi ON o.id = oi.order_id
    WHERE o.user_id = ?
    GROUP BY o.id
    ORDER BY o.created_at DESC
    LIMIT 5
");
$stmt->execute([$userId]);
$orders = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Account</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
    <!-- Navigation -->
    <?php include 'navbar.php'; ?>

    <!-- Account Section -->
    <section class="account-section py-5">
        <div class="container">
            <h1 class="mb-5">My Account</h1>
            <div class="row">
                <div class="col-md-4">
                    <div class="card mb-4">
                        <div class="card-header bg-primary text-white">
                            <h5 class="mb-0" style="color: white;">Account Details</h5>
                        </div>
                        <div class="card-body">
                            <div class="text-center mb-4">
                                <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center mx-auto" style="width: 100px; height: 100px; font-size: 3rem;">
                                    <?= strtoupper(substr($user['first_name'], 0, 1) . substr($user['last_name'], 0, 1)) ?>
                                </div>
                                <h4 class="mt-3"><?= htmlspecialchars($user['first_name'] . ' ' . $user['last_name']) ?></h4>
                                <p class="text-muted">Member since <?= date('F Y', strtotime($user['created_at'])) ?></p>
                            </div>
                            
                            <div class="mb-3">
                                <h6>Contact Information</h6>
                                <p>
                                    <strong>Email:</strong> <?= htmlspecialchars($user['email']) ?><br>
                                    <strong>Phone:</strong> <?= htmlspecialchars($user['phone'] ?? 'Not provided') ?>
                                </p>
                            </div>
                            
                            <div class="mb-3">
                                <h6>Address</h6>
                                <p><?= nl2br(htmlspecialchars($user['address'] ?? 'Not provided')) ?></p>
                            </div>                           
                                <a href="edit-profile.php" class="btn btn-outline-primary w-100">Edit Profile</a>
                            </div>
                    </div>
                    
                    <div class="card">
                        <div class="card-header bg-primary text-white">
                            <h5 class="mb-0" style="color: white;">Payment Methods</h5>
                        </div>
                        <div class="card-body">
                            <?php if (empty($paymentMethods)): ?>
                                <div class="alert alert-info">
                                    No payment methods saved. <a href="payment-methods.php" class="alert-link">Add a payment method</a>.
                                </div>
                            <?php else: ?>
                                <ul class="list-group list-group-flush">
                                    <?php foreach ($paymentMethods as $method): ?>
                                    <li class="list-group-item">
                                        <?php 
                                        switch ($method['method_type']) {
                                            case 'card':
                                                echo '<i class="far fa-credit-card me-2"></i> Card ending in ' . substr($method['card_number'], -4);
                                                break;
                                            case 'paypal':
                                                echo '<i class="fab fa-paypal me-2"></i> PayPal: ' . htmlspecialchars($method['paypal_username']);
                                                break;
                                            case 'ozow':
                                                echo '<i class="fas fa-money-bill-wave me-2"></i> Ozow: ' . htmlspecialchars($method['ozow_username']);
                                                break;
                                        }
                                        ?>
                                        <?= $method['is_default'] ? '<span class="badge bg-success ms-2">Default</span>' : '' ?>
                                    </li>
                                    <?php endforeach; ?>
                                </ul>
                            <?php endif; ?>
                            <div class="mt-3">
                                <a href="payment-methods.php" class="btn btn-outline-primary w-100">Manage Payment Methods</a>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-8">
                    <div class="card mb-4">
                        <div class="card-header bg-primary text-white">
                            <h5 class="mb-0" style="color: white;">Recent Orders</h5>
                        </div>
                        <div class="card-body">
                            <?php if (empty($orders)): ?>
                                <div class="alert alert-info">
                                    You haven't placed any orders yet. <a href="products.php" class="alert-link">Start shopping</a>.
                                </div>
                            <?php else: ?>
                                <div class="table-responsive">
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th>Order #</th>
                                                <th>Date</th>
                                                <th>Items</th>
                                                <th>Total</th>
                                                <th>Status</th>
                                                <th></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($orders as $order): ?>
                                            <tr>
                                                <td>HC-<?= str_pad($order['id'], 6, '0', STR_PAD_LEFT) ?></td>
                                                <td><?= date('M j, Y', strtotime($order['created_at'])) ?></td>
                                                <td><?= $order['item_count'] ?></td>
                                                <td>R<?= number_format($order['total_amount'], 2) ?></td>
                                                <td>
                                                    <span class="badge 
                                                        <?= $order['status'] === 'delivered' ? 'bg-success' : 
                                                           ($order['status'] === 'shipped' ? 'bg-info' : 
                                                           ($order['status'] === 'processing' ? 'bg-warning' : 'bg-secondary')) ?>">
                                                        <?= ucfirst($order['status']) ?>
                                                    </span>
                                                </td>
                                                <td>
                                                    <a href="order-confirmation.php?id=<?= $order['id'] ?>" class="btn btn-sm btn-outline-primary">View</a>
                                                </td>
                                            </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                            <?php endif; ?>
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