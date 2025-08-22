<?php require_once 'functions.php'; 

// Get filters from query parameters
$filters = [
    'category' => $_GET['category'] ?? null,
    'min_price' => $_GET['min_price'] ?? null,
    'max_price' => $_GET['max_price'] ?? null,
    'sort' => $_GET['sort'] ?? null
];

// Get filtered products
$products = getProducts($filters);

// Category title
$categoryTitles = [
    'cafe_kit' => 'Cafe Kits',
    'mug' => 'Mugs',
    'coffee' => 'Coffee',
    'machines' => 'Machines',
    'others' => 'Others',
    'decor' => 'Decor'
];

$pageTitle = 'All Products';
if (!empty($filters['category']) && isset($categoryTitles[$filters['category']])) {
    $pageTitle = $categoryTitles[$filters['category']];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Our Products</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

</head>
<body>
    <!-- Navigation -->
    <?php include 'navbar.php'; ?>

    <!-- Products Section -->
    <section class="products-section py-5">
        <div class="container">
            <div class="row mb-4">
                <div class="col-md-6">
                    <h1><?= $pageTitle ?></h1>
                </div>
                <div class="col-md-6 text-end">
                    <p class="mb-0 style="color: var(--dark-coffee);""><?= count($products) ?> Products Found</p>
                </div>
            </div>
            
            <div class="products-cards row">
                <!-- Filters Sidebar -->
                <div class="col-md-3">
                    <div class="card mb-4">
                        <div class="card-header bg-primary text-white">
                            <h5 class="mb-0">Filters</h5>
                        </div>
                        <div class="card-body">
                            <form id="filter-form">
                                <!-- Category Filter -->
                                <div class="mb-3">
                                    <h6>Category</h6>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="category" id="category-all" value="" <?= empty($filters['category']) ? 'checked' : '' ?>>
                                        <label class="form-check-label" for="category-all">All Categories</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="category" id="category-kit" value="cafe_kit" <?= $filters['category'] === 'cafe_kit' ? 'checked' : '' ?>>
                                        <label class="form-check-label" for="category-kit">Cafe Kits</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="category" id="category-mug" value="mug" <?= $filters['category'] === 'mug' ? 'checked' : '' ?>>
                                        <label class="form-check-label" for="category-mug">Mugs</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="category" id="category-coffee" value="coffee" <?= $filters['category'] === 'coffee' ? 'checked' : '' ?>>
                                        <label class="form-check-label" for="category-coffee">Coffee</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="category" id="category-machines" value="machines" <?= $filters['category'] === 'machines' ? 'checked' : '' ?>>
                                        <label class="form-check-label" for="category-machines">Machines</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="category" id="category-decor" value="decor" <?= $filters['category'] === 'decor' ? 'checked' : '' ?>>
                                        <label class="form-check-label" for="category-decor">Decor</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="category" id="category-others" value="others" <?= $filters['category'] === 'others' ? 'checked' : '' ?>>
                                        <label class="form-check-label" for="category-others">Other</label>
                                    </div>
                                </div>
                                
                                <!-- Price Filter -->
                                <div class="mb-3">
                                    <h6>Price Range</h6>
                                    <div class="row g-2">
                                        <div class="col">
                                            <input type="number" class="form-control" placeholder="Min" name="min_price" value="<?= $filters['min_price'] ?? '' ?>">
                                        </div>
                                        <div class="col">
                                            <input type="number" class="form-control" placeholder="Max" name="max_price" value="<?= $filters['max_price'] ?? '' ?>">
                                        </div>
                                    </div>
                                </div>
                                
                                <button type="submit" class="btn btn-primary w-100">Apply Filters</button>
                            </form>
                        </div>
                    </div>
                </div>
                
                <!-- Products Listing -->
                <div class="col-md-9">
                    <!-- Sort Options -->
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <div>
                            <span class="me-2">Sort by:</span>
                            <div class="btn-group" role="group">
                                <a href="?<?= http_build_query(array_merge($_GET, ['sort' => 'price_asc'])) ?>" class="btn btn-outline-secondary <?= $filters['sort'] === 'price_asc' ? 'active' : '' ?>">Price Low to High</a>
                                <a href="?<?= http_build_query(array_merge($_GET, ['sort' => 'price_desc'])) ?>" class="btn btn-outline-secondary <?= $filters['sort'] === 'price_desc' ? 'active' : '' ?>">Price High to Low</a>
                                <a href="?<?= http_build_query(array_merge($_GET, ['sort' => 'name_asc'])) ?>" class="btn btn-outline-secondary <?= $filters['sort'] === 'name_asc' ? 'active' : '' ?>">Name A-Z</a>
                                <a href="?<?= http_build_query(array_merge($_GET, ['sort' => 'name_desc'])) ?>" class="btn btn-outline-secondary <?= $filters['sort'] === 'name_desc' ? 'active' : '' ?>">Name Z-A</a>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Products Grid -->
                    <div class="row">
                        <?php if (empty($products)): ?>
                            <div class="col-12">
                                <div class="alert alert-info">No products found matching your filters.</div>
                            </div>
                        <?php else: ?>
                            <?php foreach ($products as $product): ?>
                            <div class="col-md-4 mb-4">
                                <div class="card h-100">
                                    <img src="<?= htmlspecialchars($product['image_path']) ?>" class="card-img-top" alt="<?= htmlspecialchars($product['name']) ?>" style="max-height: 200px; object-fit: contain;">
                                    <div class="card-body">
                                        <h5 class="card-title"><?= htmlspecialchars($product['name']) ?></h5>
                                        <p class="card-text"><?= htmlspecialchars(substr($product['description'], 0, 100)) ?>...</p>
                                        <p class="text-black fw-bold">R<?= number_format($product['price'], 2) ?></p>
                                    </div>
                                    <div class="card-footer bg-white">
                                        <a href="product-detail.php?id=<?= $product['id'] ?>" class="btn btn-outline-primary">View Details</a>
                                        <button class="btn btn-primary add-to-cart" data-product-id="<?= $product['id'] ?>">Add to Cart</button>
                                    </div>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
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
            const button = this;
            
            <?php if (isLoggedIn()): ?>
                // Show loading state
                button.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Adding...';
                button.disabled = true;
                
                // User is logged in then add to cart
                fetch('add-to-cart.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: `product_id=${productId}&quantity=1`
                })
                .then(response => response.json())
                .then(data => {
                    // Reset button state
                    button.innerHTML = 'Add to Cart';
                    button.disabled = false;
                    
                    if (data.success) {
                        // Update cart count in navbar
                        const cartBadge = document.querySelector('.navbar .badge');
                        if (cartBadge) {
                            cartBadge.textContent = parseInt(cartBadge.textContent || '0') + 1;
                            // Add animation to cart badge
                            cartBadge.classList.add('animate-bounce');
                            setTimeout(() => cartBadge.classList.remove('animate-bounce'), 1000);
                        }
                        
                        // Coffee Animation
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
                                url("images/coffee-beans-pattern.png") // Optional: add a coffee beans pattern
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
                    button.innerHTML = 'Add to Cart';
                    button.disabled = false;
                    Swal.fire({
                        title: 'Error',
                        text: 'Failed to add to cart',
                        icon: 'error',
                        confirmButtonColor: '#865f46'
                    });
                });
            <?php else: ?>
                // Redirects if the user is not logged in
                window.location.href = 'login.php?redirect=' + encodeURIComponent(window.location.href);
            <?php endif; ?>
        });
    });
});
    </script>
</body>

</html>
