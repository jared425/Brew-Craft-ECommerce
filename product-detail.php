<?php require_once 'functions.php';

if (!isset($_GET['id'])) {
    header('Location: products.php');
    exit;
}

$productId = $_GET['id'];
$stmt = $pdo->prepare("SELECT * FROM products WHERE id = ?");
$stmt->execute([$productId]);
$product = $stmt->fetch();

if (!$product) {
    header('Location: products.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($product['name']) ?> - Home Cafe</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        /* Custom styles for better alignment */
        .quantity-input-group {
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .quantity-input {
            width: 90px !important;
        }
        
        .add-to-cart-btn {
            height: 38px; /* Match the height of the quantity input */
            white-space: nowrap;
        }
        
        @media (max-width: 576px) {
            .quantity-input-group {
                flex-direction: column;
                align-items: flex-start;
            }
            
            .add-to-cart-btn {
                width: 100%;
            }
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    <?php include 'navbar.php'; ?>

    <!-- Product Detail Section -->
    <section class="product-detail py-5">
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <img src="<?= htmlspecialchars($product['image_path']) ?>" alt="<?= htmlspecialchars($product['name']) ?>" class="img-fluid rounded">
                </div>
                <div class="col-md-6">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                            <li class="breadcrumb-item"><a href="products.php">Products</a></li>
                            <li class="breadcrumb-item active" aria-current="page"><?= htmlspecialchars($product['name']) ?></li>
                        </ol>
                    </nav>
                    
                    <h1 class="mb-3"><?= htmlspecialchars($product['name']) ?></h1>
                    <p class="text-muted">SKU: HC-<?= str_pad($product['id'], 4, '0', STR_PAD_LEFT) ?></p>
                    
                    <div class="mb-4">
                        <span class="display-6 text-black">R<?= number_format($product['price'], 2) ?></span>
                        <?php if ($product['stock'] > 0): ?>
                            <span class="badge bg-success ms-2">In Stock</span>
                        <?php else: ?>
                            <span class="badge bg-danger ms-2">Out of Stock</span>
                        <?php endif; ?>
                    </div>
                    
                    <div class="mb-4">
                        <h5>Description</h5>
                        <p><?= nl2br(htmlspecialchars($product['description'])) ?></p>
                    </div>
                    
                    <?php if ($product['stock'] > 0): ?>
                    <div class="mb-4">
                        <label for="quantity" class="form-label">Quantity</label>
                        <div class="quantity-input-group">
                            <input type="number" id="quantity" class="form-control quantity-input" value="1" min="1" max="<?= $product['stock'] ?>">
                            <button class="btn btn-primary add-to-cart-btn add-to-cart" data-product-id="<?= $product['id'] ?>" <?= $product['stock'] <= 0 ? 'disabled' : '' ?>>
                                Add to Cart
                            </button>
                        </div>
                    </div>
                    <?php endif; ?>
                    
                    <div class="product-meta">
                        <p class="mb-1"><strong>Category:</strong> 
                            <?= ucfirst(str_replace('_', ' ', $product['category'])) ?>
                        </p>
                        <p class="mb-1"><strong>Availability:</strong> 
                            <?= $product['stock'] ?> units available
                        </p>
                    </div>
                </div>
            </div>
            
            <!-- Related Products -->
            <div class="row mt-5">
                <div class="col-12">
                    <h3 class="mb-4">You May Also Like</h3>
                    <div class="row">
                        <?php 
                        $stmt = $pdo->prepare("SELECT * FROM products WHERE category = ? AND id != ? LIMIT 4");
                        $stmt->execute([$product['category'], $product['id']]);
                        ?>
                        <?php while ($related = $stmt->fetch()): ?>
                        <div class="col-md-3 mb-4">
                            <div class="card h-100">
                                <img src="<?= htmlspecialchars($related['image_path']) ?>" class="card-img-top" alt="<?= htmlspecialchars($related['name']) ?>">
                                <div class="card-body">
                                    <h5 class="card-title"><?= htmlspecialchars($related['name']) ?></h5>
                                    <p class="text-black fw-bold">R<?= number_format($related['price'], 2) ?></p>
                                </div>
                                <div class="card-footer bg-white">
                                    <a href="product-detail.php?id=<?= $related['id'] ?>" class="btn btn-outline-primary btn-sm">View Details</a>
                                </div>
                            </div>
                        </div>
                        <?php endwhile; ?>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <?php include 'footer.php'; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
    // Add to cart functionality
    document.querySelectorAll('.add-to-cart').forEach(button => {
        button.addEventListener('click', function() {
            const productId = this.getAttribute('data-product-id');
            const quantity = document.getElementById('quantity').value;
            const button = this;
            
            <?php if (isLoggedIn()): ?>
                // Show loading state
                const originalText = button.innerHTML;
                button.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Adding...';
                button.disabled = true;
                
                // User is logged in - add to cart via AJAX
                fetch('add-to-cart.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: `product_id=${productId}&quantity=${quantity}`
                })
                .then(response => response.json())
                .then(data => {
                    // Reset button state
                    button.innerHTML = originalText;
                    button.disabled = false;
                    
                    if (data.success) {
                        // Update cart count in navbar
                        const cartBadge = document.querySelector('.navbar .badge');
                        if (cartBadge) {
                            cartBadge.textContent = parseInt(cartBadge.textContent || '0') + parseInt(quantity);
                            // Add animation to cart badge
                            cartBadge.classList.add('animate-bounce');
                            setTimeout(() => cartBadge.classList.remove('animate-bounce'), 1000);
                        }
                        
                        // Show coffee-themed success message
                        Swal.fire({
                            title: 'Brew-tiful!',
                            text: 'Added to your coffee stash!',
                            icon: 'success',
                            imageUrl: 'images/logo.png',
                            imageWidth: 80,
                            imageHeight: 80,
                            imageAlt: 'Coffee cup',
                            confirmButtonColor: '#865f46',
                            background: '#f9f5f0',
                            backdrop: `
                                rgba(134,95,70,0.4)
                                url("images/coffee-beans-pattern.png")
                                center top
                                no-repeat
                            `,
                            customClass: {
                                popup: 'coffee-swal',
                                title: 'coffee-swal-title',
                                confirmButton: 'coffee-swal-button'
                            }
                        });
                    } else {
                        Swal.fire({
                            title: 'Oops!',
                            text: data.message || 'Something went wrong',
                            icon: 'error',
                            confirmButtonColor: '#865f46'
                        });
                    }
                })
                .catch(error => {
                    button.innerHTML = originalText;
                    button.disabled = false;
                    Swal.fire({
                        title: 'Error',
                        text: 'Failed to add to cart',
                        icon: 'error',
                        confirmButtonColor: '#865f46'
                    });
                });
            <?php else: ?>
                // User not logged in - redirect to login
                window.location.href = 'login.php?redirect=' + encodeURIComponent(window.location.href);
            <?php endif; ?>
        });
    });
});
    </script>
</body>
</html>