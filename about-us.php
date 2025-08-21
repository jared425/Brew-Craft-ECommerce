<?php require_once 'functions.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Us</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700&family=Playfair+Display:wght@700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
    <!-- Navigation -->
    <?php include 'navbar.php'; ?>
    <!-- Hero Section -->
    <section class="about-hero py-5">
        <div class="container text-center text-white">
            <h1 class="display-3 fw-bold mb-4">Crafting Your Perfect Coffee Journey</h1>
            <p class="lead mb-5" style="color: var(--dark-coffee);">We're passionate about bringing you the world's finest coffee experiences, curated with care and delivered to your door.</p>
        </div>
    </section>
    <!-- Mission Section -->
    <section class="py-5">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <h2 class="fw-bold mb-4">From bean to cup, we're redefining what premium coffee means.</h2>
                    <div class="d-flex flex-column gap-3 mb-4">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-check-circle text-primary me-3 fs-4"></i>
                            <span class="fs-5">Ethically Sourced</span>
                        </div>
                        <div class="d-flex align-items-center">
                            <i class="fas fa-check-circle text-primary me-3 fs-4"></i>
                            <span class="fs-5">Expertly Curated</span>
                        </div>
                        <div class="d-flex align-items-center">
                            <i class="fas fa-check-circle text-primary me-3 fs-4"></i>
                            <span class="fs-5">Sustainably Packaged</span>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <img src="https://th.bing.com/th/id/OIP.ojkvuu4mXTDSHPOzGdPzpQHaE7?w=276&h=184&c=7&r=0&o=7&dpr=1.3&pid=1.7&rm=3" alt="Coffee Journey" class="img-fluid rounded shadow">
                </div>
            </div>
        </div>
    </section>
    <!-- Quality Section -->
   <section class="py-5">
    <div class="container text-center">
        <h2 class="fw-bold mb-5">Premium Craftsmanship</h2>
        <p class="lead mb-4">Elevate Your Coffee Experience</p>
        <div class="row">
            <div class="col-md-4 mb-4">
                <div class="card h-100 border-0 bg-transparent">
                    <div class="card-body">
                        <i class="fas fa-tools text-primary mb-3 fs-1"></i>
                        <h3 class="h4">Professional-Grade Equipment</h3>
                        <p>Our kits feature tools designed by baristas, for baristas - durable construction with precision engineering</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-4">
                <div class="card h-100 border-0 bg-transparent">
                    <div class="card-body">
                        <i class="fas fa-award text-primary mb-3 fs-1"></i>
                        <h3 class="h4">Curated Collections</h3>
                        <p>Each kit is thoughtfully assembled with complementary pieces that create a complete coffee station aesthetic</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-4">
                <div class="card h-100 border-0 bg-transparent">
                    <div class="card-body">
                        <i class="fas fa-palette text-primary mb-3 fs-1"></i>
                        <h3 class="h4">Design-Forward Decor</h3>
                        <p>Stylish accessories that blend functionality with modern kitchen decor for Instagram-worthy setups</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
    <!-- Story Section -->
    <section class="py-5">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-6 order-md-2">
                    <h2 class="fw-bold mb-4">Our Story</h2>
                    <p>Founded in 2023, Brew Craft began with a simple idea: to bring the cafe experience home without compromise. Our founders, passionate coffee enthusiasts, were frustrated by the choice between convenience and quality.</p>
                    <p>We set out to create coffee kits that deliver barista-quality results with home-brewing convenience. Every product we offer is personally tested by our team to ensure it meets our high standards.</p>
                </div>
                <div class="col-md-6 order-md-1">
                    <img src="https://th.bing.com/th/id/OIP.kPPdeBZ10MxpUO_H0Y97ywHaEK?w=328&h=184&c=7&r=0&o=7&dpr=1.3&pid=1.7&rm=3" alt="Our Story" class="img-fluid rounded shadow">
                </div>
            </div>
        </div>
    </section>
    <?php include 'footer.php' ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
</body>
</html>