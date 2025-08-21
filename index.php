<?php require_once 'functions.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Brew Craft</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700&family=Playfair+Display:wght@700&display=swap" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
</head>
<body>
    <!-- Navigation -->
    <?php include 'navbar.php'; ?>

    <!-- Hero Section -->
    <section class="hero-section py-5" style="background-image:url('images/pic2.jpg');">
        <div class="container text-center">
            <div class="row align-items-center">
                <div class="col-md-6 text-center">
                    <h1 class="display-4 fw-bold text-center">Create Your Perfect Home Cafe</h1>
                    <p class="lead">Premium cafe kits, mugs, and coffee to transform your home into a coffee lover's paradise.</p>
                    <a class="fancy" href="products.php">
                    <span class="top-key"></span>
                    <span class="text">Shop Now</span>
                    <span class="bottom-key-1"></span>
                    <span class="bottom-key-2"></span>
                    </a>
                </div>
            </div>
        </div>
    </section>

        <!-- Video & About Us Section -->
    <section class="video-about py-5 bg-light">
        <div class="container">
            <div class="row align-items-center">
                <!-- Video Column -->
                <div class="col-lg-6 mb-4 mb-lg-0">
                    <div class="ratio ratio-16x9">
                        <video autoplay loop muted playsinline class="rounded shadow">
                            <source src="images/home-vid.mp4" type="video/mp4">
                            Your browser does not support the video tag.
                        </video>
                    </div>
                </div>
                
                <!-- About Us Column -->
                <div class="col-lg-6 ps-lg-5">
                    <h2 class="mb-4">About Brew Craft</h2>
                    <p class="lead">Crafting exceptional coffee experiences since 2015.</p>
                    <p>At Brew Craft, we're passionate about bringing the cafe experience to your home. Our team of coffee enthusiasts carefully curates premium beans, equipment, and accessories to help you create barista-quality beverages.</p>
                    <p>We source directly from ethical growers and roast in small batches to ensure maximum freshness and flavor in every cup.</p>
                    <a href="about-us.php" class="btn btn-outline-primary mt-3">Learn More About Us</a>
                </div>
            </div>
        </div>
    </section>

    <!-- Featured Coffee Products -->
    <section class="featured-products py-5">
        <div class="container">
            <h2 class="text-center mb-5">Our Premium Coffee Selection</h2>
            <div class="row">
                <?php 
                $coffeeProducts = getCoffeeProducts(4);
                if (!empty($coffeeProducts)) {
                    foreach ($coffeeProducts as $product): 
                ?>
                <div class="col-md-3 mb-4">
                    <div class="card h-100">
                        <img src="<?= htmlspecialchars($product['image_path']) ?>" class="card-img-top" alt="<?= htmlspecialchars($product['name']) ?>">
                        <div class="card-body">
                            <h5 class="card-title"><?= htmlspecialchars($product['name']) ?></h5>
                            <p class="card-text"><?= htmlspecialchars(substr($product['description'], 0, 100)) ?>...</p>
                            <p class="text-black fw-bold">R <?= number_format($product['price'], 2) ?></p>
                        </div>
                        <div class="card-footer bg-white">
                            <a href="product-detail.php?id=<?= $product['id'] ?>" class="btn btn-outline-primary">View Details</a>
                            <button class="btn btn-primary add-to-cart" data-product-id="<?= $product['id'] ?>">Add to Cart</button>
                        </div>
                    </div>
                </div>
                <?php 
                    endforeach;
                } else {
                    echo '<div class="col-12 text-center"><p>No coffee products available at the moment.</p></div>';
                }
                ?>
            </div>
            <div class="text-center mt-4">
                <a href="products.php?category=coffee" class="btn btn-outline-secondary">View All Coffee Products</a>
            </div>
        </div>
    </section>

    <?php include 'footer.php'; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    

    <script>
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.add-to-cart').forEach(button => {
        button.addEventListener('click', function() {
            const productId = this.getAttribute('data-product-id');
            const originalButton = this;
            
            // Store original button state
            const originalText = this.innerHTML;
            const originalClasses = this.className;
            
            // Change button appearance immediately to show feedback
            this.innerHTML = '<i class="fas fa-check"></i> Added!';
            this.className = 'btn btn-success';
            this.disabled = true;

            <?php if (isLoggedIn()): ?>
            fetch('add-to-cart.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: `product_id=${productId}&quantity=1`
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showToast('Product added!');
                    triggerPlaneDrop();

                    const cartBadge = document.querySelector('.navbar .badge');
                    if (cartBadge) {
                        cartBadge.textContent = parseInt(cartBadge.textContent || '0') + 1;
                        cartBadge.classList.add('cart-bounce');
                        setTimeout(() => cartBadge.classList.remove('cart-bounce'), 600);
                    }
                    
                    // Reset button after animation completes (2.2 seconds)
                    setTimeout(() => {
                        originalButton.innerHTML = originalText;
                        originalButton.className = originalClasses;
                        originalButton.disabled = false;
                    }, 2200);
                } else {
                    // Reset button immediately if there's an error
                    originalButton.innerHTML = originalText;
                    originalButton.className = originalClasses;
                    originalButton.disabled = false;
                }
            })
            .catch(error => {
                // Reset button on error
                originalButton.innerHTML = originalText;
                originalButton.className = originalClasses;
                originalButton.disabled = false;
            });
            <?php else: ?>
            // Reset button before redirecting
            setTimeout(() => {
                originalButton.innerHTML = originalText;
                originalButton.className = originalClasses;
                originalButton.disabled = false;
            }, 1000);
            window.location.href = 'login.php?redirect=' + encodeURIComponent(window.location.href);
            <?php endif; ?>
        });
    });

    function showToast(message) {
        const toast = document.createElement('div');
        toast.className = 'cart-toast';
        toast.textContent = message;
        document.body.appendChild(toast);

        toast.animate([
            { transform: 'translateY(0)', opacity: 0 },
            { transform: 'translateY(0)', opacity: 1 },
            { transform: 'translateY(-20px)', opacity: 1 },
            { transform: 'translateY(0)', opacity: 0 }
        ], {
            duration: 2200,
            easing: 'ease-out',
            fill: 'forwards'
        }).onfinish = () => toast.remove();
    }

    function triggerPlaneDrop() {
        const cartIcon = document.querySelector('.fa-shopping-cart');
        if (!cartIcon) return;
        const cartRect = cartIcon.getBoundingClientRect();

        // Plane element using uploaded PNG
        const plane = document.createElement('img');
        plane.src = 'images/airplane.png';
        plane.className = 'plane';
        plane.style.position = "fixed";
        plane.style.left = "-80px"; 
        plane.style.top = (cartRect.top + 30) + "px";
        plane.style.width = "60px";
        plane.style.height = "60px";
        plane.style.zIndex = 9999;
        plane.style.pointerEvents = 'none';
        document.body.appendChild(plane);

        // Animate plane in an arc
        const keyframes = [
            { left: "-80px", top: (cartRect.top + 30) + "px" },
            { left: (cartRect.left - 30) + "px", top: (cartRect.top - 50) + "px" },
            { left: cartRect.left + "px", top: cartRect.top + "px" }
        ];
        plane.animate(keyframes, {
            duration: 2200,
            easing: 'ease-in-out',
            fill: 'forwards'
        }).onfinish = () => {
            plane.remove();

            // Drop box
            const box = document.createElement('div');
            box.className = 'box';
            box.innerHTML = `
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 64 64" width="36" height="36" fill="#b08968">
                    <path d="M2 16v32h60V16H2zm30 0l28 16H32L2 32l30-16z"/>
                </svg>
            `;
            box.style.position = "fixed";
            box.style.left = cartRect.left + "px";
            box.style.top = (cartRect.top - 50) + "px";
            box.style.zIndex = 9999;
            document.body.appendChild(box);

            box.animate([
                { transform: 'translateY(-50px) scale(1)', opacity: 1 },
                { transform: 'translateY(0) scale(0.8)', opacity: 1 },
                { transform: 'translateY(5px) scale(0.6)', opacity: 0.7 },
                { transform: 'translateY(0) scale(0.5)', opacity: 0 }
            ], {
                duration: 1000,
                easing: 'ease-out'
            }).onfinish = () => box.remove();
        };
    }
});
</script>
</html>