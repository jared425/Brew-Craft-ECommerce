<?php require_once 'functions.php';

requireVerifiedUser();

$userId = $_SESSION['user_id'];
$cartItems = getCartItems($userId);
$cartTotal = getCartTotal($userId);

// Handle remove from cart
if (isset($_POST['remove_item'])) {
    removeFromCart($userId, $_POST['product_id']);
    header('Location: cart.php');
    exit;
}

// Handle quantity update
if (isset($_POST['update_quantity'])) {
    updateCartQuantity($userId, $_POST['product_id'], $_POST['quantity']);
    header('Location: cart.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shopping Cart</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        /* Custom styles for consistent button sizing and alignment */
        .checkout-buttons {
            display: flex;
            flex-direction: column;
            gap: 12px;
        }
        
        .checkout-buttons .btn {
            padding: 12px 16px;
            font-size: 1rem;
            border-radius: 6px;
            height: 48px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 500;
        }
        
        .quantity-form {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .quantity-input {
            width: 70px;
        }
        
        .cart-btn {
            padding: 0.4rem 0.8rem;
            font-size: 0.9rem;
        }
        
        @media (max-width: 768px) {
            .checkout-buttons .btn {
                height: 44px;
                font-size: 0.95rem;
            }
        }
    </style>
</head>
<body>
    <!-- Navbar link -->
    <?php include 'navbar.php'; ?>

    <!-- Cart Section -->
    <section class="cart-section py-5">
        <div class="container" style="min-height: 60vh; display: flex; flex-direction: column; justify-content: center;">
        <h1 class="mb-5">Your Shopping Cart</h1>
        
        <?php if (empty($cartItems)): ?>
            <div class="alert alert-info text-center">
                Your cart is empty. <a href="products.php" class="alert-link">Continue shopping</a>.
            </div>
            <?php else: ?>
                <div class="row">
                    <div class="col-md-8">
                        <div class="card">
                            <div class="card-body">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>Product</th>
                                            <th>Price</th>
                                            <th>Quantity</th>
                                            <th>Total</th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($cartItems as $item): ?>
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <img src="<?= htmlspecialchars($item['image_path']) ?>" alt="<?= htmlspecialchars($item['name']) ?>" width="80" class="me-3">
                                                    <div>
                                                        <h5 class="mb-0"><?= htmlspecialchars($item['name']) ?></h5>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>R<?= number_format($item['price'], 2) ?></td>
                                            <td>
                                                <form method="post" class="quantity-form">
                                                    <input type="hidden" name="product_id" value="<?= $item['product_id'] ?>">
                                                    <input type="number" name="quantity" value="<?= $item['quantity'] ?>" min="1" class="form-control quantity-input">
                                                    <button type="submit" name="update_quantity" class="btn btn-sm btn-outline-secondary cart-btn">Update</button>
                                                </form>
                                            </td>
                                            <td>R<?= number_format($item['total_price'], 2) ?></td>
                                            <td>
                                                <form method="post">
                                                    <input type="hidden" name="product_id" value="<?= $item['product_id'] ?>">
                                                    <button type="submit" name="remove_item" class="btn btn-sm btn-danger cart-btn">Remove</button>
                                                </form>
                                            </td>
                                        </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-4">
                        <div class="card">
                            <div class="card-header bg-primary text-white">
                                <h5 class="mb-0">Order Summary</h5>
                            </div>
                            <div class="card-body">
                                <div class="d-flex justify-content-between mb-2">
                                    <span>Subtotal:</span>
                                    <span>R<?= number_format($cartTotal, 2) ?></span>
                                </div>
                                <div class="d-flex justify-content-between mb-2">
                                    <span>Shipping:</span>
                                    <span>Free</span>
                                </div>
                                <hr>
                                <div class="d-flex justify-content-between fw-bold">
                                    <span>Total:</span>
                                    <span>R<?= number_format($cartTotal, 2) ?></span>
                                </div>
                            </div>
                            <div class="card-footer bg-white checkout-buttons">
                                <a href="checkout.php" class="btn btn-primary">Proceed to Checkout</a>
                                <a href="products.php" class="btn btn-outline-secondary">Continue Shopping</a>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </section>

    <!-- Footer -->
    <?php include 'footer.php'; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>