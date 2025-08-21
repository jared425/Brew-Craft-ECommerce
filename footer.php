<footer class="bg-dark text-white pt-5 pb-3">
    <div class="container">
        <div class="row g-4">
            <div class="col-lg-4 col-md-6">
                <div class="footer-brand mb-3">
                    <h4 class="text-white fw-bold mb-3">Brew Craft</h4>
                    <p class="text-white">Bringing the cafe experience to your home with premium quality products and equipment.</p>
                    <a href="terms-conditions.php" class="text-decoration-none text-white small">Terms and Conditions</a>
                </div>
            </div>
            
            <div class="col-lg-2 col-md-6">
                <h5 class="text-white fw-bold mb-3">Account</h5>
                <ul class="list-unstyled">
                    <?php if (isLoggedIn()): ?>
                        <li class="mb-2"><a href="my-account.php" class="text-decoration-none text-white hover-white">My Account</a></li>
                        <li class="mb-2"><a href="cart.php" class="text-decoration-none text-white hover-white">Cart</a></li>
                        <li><a href="logout.php" class="text-decoration-none text-white hover-white">Logout</a></li>
                    <?php else: ?>
                        <li class="mb-2"><a href="login.php" class="text-decoration-none text-white hover-white">Login</a></li>
                        <li><a href="signup.php" class="text-decoration-none text-white hover-white">Sign Up</a></li>
                    <?php endif; ?>
                </ul>
            </div>
            
            <div class="col-lg-4 col-md-6">
                <h5 class="text-white fw-bold mb-3">Contact Us</h5>
                <address class="text-white mb-3">
                    <p class="mb-1"><i class="fas fa-envelope me-2"></i> info@brewcraft.com</p>
                    <p><i class="fas fa-phone me-2"></i> (123) 456-7890</p>
                </address>
                <div class="social-links">
                    <a href="https://www.tiktok.com/@brew_craft?_t=ZS-8z0WYF4zzwF&_r=1" class="text-decoration-none text-muted me-3 hover-white" aria-label="TikTok"><i class="fab fa-tiktok fa-lg"></i></a>
                    <a href="https://www.instagram.com/_brewcraft_?igsh=MWU4YXdzb2NjeDh4Nw%3D%3D&utm_source=qr" class="text-decoration-none text-muted me-3 hover-white" aria-label="Instagram"><i class="fab fa-instagram fa-lg"></i></a>
                </div>
            </div>
            
            <div class="col-lg-2 col-md-6">
                <h5 class="text-white fw-bold mb-3">Quick Links</h5>
                <ul class="list-unstyled">
                    <li class="mb-2"><a href="about-us.php" class="text-decoration-none text-white hover-white">About Us</a></li>
                    <li><a href="contact-us.php" class="text-decoration-none text-white hover-white">Contact</a></li>
                </ul>
            </div>
        </div>
        
        <hr class="my-4 bg-secondary">
        
        <div class="text-center pt-2">
            <p class="small text-white mb-0">&copy; <?= date('Y') ?> Brew Craft. All rights reserved.</p>
        </div>
    </div>
</footer>

<style>
    .hover-white:hover {
        color: white !important;
        transition: color 0.3s ease;
    }
    
    .social-links a:hover {
        transform: translateY(-2px);
        transition: transform 0.3s ease;
    }
    
    footer a:hover {
        text-decoration: underline;
    }
    
    .footer-brand h4 {
        position: relative;
        padding-bottom: 10px;
    }
    
    .footer-brand h4:after {
        content: '';
        position: absolute;
        left: 0;
        bottom: 0;
        width: 50px;
        height: 2px;
        background: #fff;
    }
</style>