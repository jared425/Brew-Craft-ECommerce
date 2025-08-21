<?php require_once 'functions.php';

requireVerifiedUser();

$userId = $_SESSION['user_id'];
$cartItems = getCartItems($userId);
$cartTotal = getCartTotal($userId);

if (empty($cartItems)) {
    header('Location: cart.php');
    exit;
}

// Check if user has payment methods
$hasPaymentMethod = hasPaymentMethod($userId);
$paymentMethods = getUserPaymentMethods($userId);

// Handle checkout submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['checkout'])) {
    if (empty($_POST['payment_method_id'])) {
        $error = "Please select a payment method";
    } else {
        $paymentMethodId = $_POST['payment_method_id'];
        
        try {
            $orderId = createOrder($userId, $paymentMethodId, $cartTotal);
            header("Location: order-confirmation.php?id=$orderId");
            exit;
        } catch (Exception $e) {
            $error = "Error processing your order: " . $e->getMessage();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
    <!-- Navigation -->
    <?php include 'navbar.php'; ?>

    <!-- Checkout Section -->
    <section class="checkout-section py-5">
        <div class="container">
            <h1 class="mb-5">Checkout</h1>
            
            <?php if (isset($error)): ?>
                <div class="alert alert-danger"><?= $error ?></div>
            <?php endif; ?>
            
            <div class="row">
                <div class="col-md-8">
                    <div class="card mb-4">
                        <div class="card-header bg-primary text-white">
                            <h5 class="mb-0">Payment Method</h5>
                        </div>
                        <div class="card-body">
                            <?php if (!$hasPaymentMethod): ?>
                                <div class="alert alert-warning">
                                    You don't have any payment methods saved. <a href="payment-methods.php" class="alert-link">Add a payment method</a> to continue.
                                </div>
                            <?php else: ?>
                                <form method="post">
                                    <?php foreach ($paymentMethods as $method): ?>
                                    <div class="form-check mb-3">
                                        <input class="form-check-input" type="radio" name="payment_method_id" id="payment-<?= $method['id'] ?>" value="<?= $method['id'] ?>" <?= $method['is_default'] ? 'checked' : '' ?>>
                                        <label class="form-check-label" for="payment-<?= $method['id'] ?>">
                                            <?php 
                                            switch ($method['method_type']) {
                                                case 'card':
                                                    echo "Credit Card ending in " . substr($method['card_number'], -4);
                                                    break;
                                                case 'paypal':
                                                    echo "PayPal: " . htmlspecialchars($method['paypal_username']);
                                                    break;
                                                case 'ozow':
                                                    echo "Ozow: " . htmlspecialchars($method['ozow_username']);
                                                    break;
                                            }
                                            ?>
                                            <?= $method['is_default'] ? '(Default)' : '' ?>
                                        </label>
                                    </div>
                                    <?php endforeach; ?>
                                    
                                    <div class="mt-4">
                                        <a href="payment-methods.php" class="btn btn-outline-primary">Manage Payment Methods</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-4">
                            <div class="card">
                                <div class="card-header bg-primary text-white">
                                    <h5 class="mb-0">Order Summary</h5>
                                </div>
                                <div class="card-body">
                                    <table class="table table-sm">
                                        <thead>
                                            <tr>
                                                <th>Product</th>
                                                <th>Qty</th>
                                                <th>Price</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($cartItems as $item): ?>
                                            <tr>
                                                <td><?= htmlspecialchars($item['name']) ?></td>
                                                <td><?= $item['quantity'] ?></td>
                                                <td>R<?= number_format($item['price'], 2) ?></td>
                                            </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                    <hr>
                                    <div class="d-flex justify-content-between fw-bold">
                                        <span>Total:</span>
                                        <span>R<?= number_format($cartTotal, 2) ?></span>
                                    </div>
                                </div>
                                <div class="card-footer bg-white">
                                    <?php if ($hasPaymentMethod): ?>
                                        <button type="submit" name="checkout" class="btn btn-primary w-100">Place Order</button>
                                    <?php else: ?>
                                        <button type="button" class="btn btn-primary w-100" disabled>Place Order</button>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </form>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <?php include 'footer.php'; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>