<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Terms and Conditions</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700&family=Playfair+Display:wght@700&display=swap" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
    <?php include 'navbar.php' ?>
    <div class="terms-container">
        <div class="terms-header">
            <h1>Terms and Conditions</h1>
            <p class="last-updated">Last updated: <?php echo date('F j, Y'); ?></p>
        </div>
        <div class="terms-content">
            <div class="term-section">
                <h2><i class="fas fa-check-circle"></i> Order Acceptance</h2>
                <p>All orders are subject to availability and acceptance by BrewCraft. We reserve the right to refuse or cancel any order for any reason, including limitations on quantities or inaccuracies in product or pricing information.</p>
            </div>
            <div class="term-section">
                <h2><i class="fas fa-exchange-alt"></i> Customization & Returns</h2>
                <p>Customized coffee kits are made to order and may not be returned unless defective or damaged upon arrival.</p>
                <p>For eligible returns, customers must contact us within 3 days of delivery. Return shipping costs are the responsibility of the customer unless the item is faulty.</p>
            </div>
            <div class="term-section">
                <h2><i class="fas fa-truck"></i> Shipping & Delivery</h2>
                <p>Delivery times are estimates and not guaranteed. Delays may occur due to unforeseen circumstances.</p>
                <p>Shipping costs and taxes are calculated at checkout and are the customer's responsibility.</p>
            </div>
            <div class="term-section">
                <h2><i class="fas fa-exclamation-triangle"></i> Liability</h2>
                <p>BrewCraft is not liable for any misuse of equipment included in our kits. Customers assume full responsibility for proper use and maintenance of brewing tools.</p>
            </div>
            <div class="term-section">
                <h2><i class="fas fa-credit-card"></i> Payment</h2>
                <p>We accept credit/debit cards, EFT, and mobile payments. Payment is processed at the time of order confirmation.</p>
            </div>
            <div class="term-section">
                <h2><i class="fas fa-copyright"></i> Intellectual Property</h2>
                <p>All content on this website, including images and product descriptions, is the property of BrewCraft and may not be reproduced without permission.</p>
            </div>
            <div class="term-section">
                <h2><i class="fas fa-balance-scale"></i> Governing Law</h2>
                <p>These terms are governed by the laws of South Africa. Any disputes will be resolved in the courts of South Africa.</p>
            </div>
            <div class="term-section">
                <h2><i class="fas fa-sync-alt"></i> Changes to Terms</h2>
                <p>We reserve the right to update these terms at any time. Continued use of the site constitutes acceptance of the revised terms.</p>
            </div>
        </div>
    </div>
    <?php include 'footer.php' ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
</body>
</html>
<style>
    /* Main container styling */
    .terms-container {
        max-width: 900px;
        margin: 2rem auto;
        padding: 0 1.5rem;
        font-family: 'Montserrat', sans-serif;
        color: #333;
        line-height: 1.6;
    }
    /* Header styling */
    .terms-header {
        text-align: center;
        margin-bottom: 3rem;
        border-bottom: 1px solid #E0E0E0;
        padding-bottom: 1.5rem;
    }
    .terms-header h1 {
        font-family: 'Playfair Display', serif;
        font-size: 2.5rem;
        color: #2C3E50;
        margin-bottom: 0.5rem;
    }
    .last-updated {
        color: #7F8C8D;
        font-size: 0.9rem;
    }
    /* Term sections styling */
    .term-section {
        margin-bottom: 2.5rem;
        background: #fff;
        padding: 1.5rem;
        border-radius: 8px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    .term-section:hover {
        transform: translateY(-3px);
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    }
    .term-section h2 {
        font-size: 1.4rem;
        color: #2C3E50;
        margin-bottom: 1rem;
        display: flex;
        align-items: center;
        gap: 10px;
    }
    .term-section p {
        margin-bottom: 1rem;
    }
    /* Contact section specific styling */
    .contact-section {
        background-color: #F8F9FA;
    }
    .contact-section a {
        color: #3498DB;
        text-decoration: none;
    }
    .contact-section a:hover {
        text-decoration: underline;
    }
    /* Responsive adjustments */
    @media (max-width: 768px) {
        .terms-container {
            padding: 0 1rem;
        }
        .terms-header h1 {
            font-size: 2rem;
        }
        .term-section {
            padding: 1rem;
        }
    }
</style>