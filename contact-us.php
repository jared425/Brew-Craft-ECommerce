<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact-Us</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700&family=Playfair+Display:wght@700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
    <?php include 'navbar.php' ?>
<?php

require_once 'functions.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $subject = trim($_POST['subject']);
    $message = trim($_POST['message']);
    // Basic validation
    $errors = [];
    if (empty($name)) $errors[] = "Name is required";
    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = "Valid email is required";
    if (empty($subject)) $errors[] = "Subject is required";
    if (empty($message)) $errors[] = "Message is required";
    if (empty($errors)) {
        try {
            $stmt = $pdo->prepare("INSERT INTO contact_messages (name, email, subject, message) VALUES (?, ?, ?, ?)");
            $stmt->execute([$name, $email, $subject, $message]);
            // Shows success modal
            echo '<script>
                document.addEventListener("DOMContentLoaded", function() {
                    var successModal = new bootstrap.Modal(document.getElementById("successModal"));
                    successModal.show();
                });
            </script>';
        } catch (PDOException $e) {
            $errors[] = "Error sending message: " . $e->getMessage();
        }
    }
    if (!empty($errors)) {
        $_SESSION['contact_errors'] = $errors;
    }
}
?>
    <!-- Contact Us Heading -->
        <div class="contact-container py-5">
            <div class="row">
                <div class="col-12 text-center">
                    <h1 class="mb-3">Contact Us</h1>
                    <p class="lead">We'd love to hear from you! Whether you have a question about our products, need assistance with an order, or just want to share your coffee experience, our team is ready to help.</p>
                </div>
            </div>
        </div>
        <?php if (isset($_SESSION['contact_errors'])): ?>
    <div class="alert alert-danger">
        <ul class="mb-0">
            <?php foreach ($_SESSION['contact_errors'] as $error): ?>
                <li><?= htmlspecialchars($error) ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
    <?php unset($_SESSION['contact_errors']); ?>
<?php endif; ?>
    <!-- Success Modal -->
    <div class="modal fade" id="successModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title">Message Sent!</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Thank you for contacting us! Your message has been recorded and we'll get back to you soon.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-success" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
    <section class="container my-1">
        <div class="row">
            <!-- Contact Form (Left Column) -->
            <div class="col-lg-6 mb-4 mb-lg-0">
                <div class="card shadow-sm">
                    <div class="card-body p-4" style="position: relative; z-index: 1;">
                        <h3 class="h4 mb-4">Send us a message</h3>
                        <form id="contactForm" method="POST">
                            <div class="mb-3">
                                <label for="name" class="form-label">Your Name</label>
                                <input type="text" class="form-control" id="name" name="name" required
                                       style="background-color: #fff; color: #212121;">
                            </div>
                            <div class="mb-3">
                                <label for="email" class="form-label">Email Address</label>
                                <input type="email" class="form-control" id="email" name="email" required
                                       style="background-color: #fff; color: #212121;">
                            </div>
                            <div class="mb-3">
                                <label for="subject" class="form-label">Subject</label>
                                <select class="form-select" id="subject" name="subject"
                                        style="background-color: #fff; color: #212121;">
                                    <option value="General Inquiry">General Inquiry</option>
                                    <option value="Product Questions">Product Questions</option>
                                    <option value="Order Support">Order Support</option>
                                    <option value="Wholesale Inquiry">Wholesale Inquiry</option>
                                    <option value="Other">Other</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="message" class="form-label">Message</label>
                                <textarea class="form-control" id="message" name="message" rows="4" required
                                          style="background-color: #fff; color: #212121;"></textarea>
                            </div>
                            <button type="submit" class="btn btn-primary">Send Message</button>
                        </form>
                    </div>
                </div>
            </div>
            <!-- Contact Info (Right Column) -->
            <div class="col-lg-6">
                <div class="card shadow-sm h-100">
                    <div class="card-body p-4">
                        <h3 class="h4 mb-4">Our Information</h3>
                        <div class="d-flex mb-4">
                            <div class="me-3 text-primary">
                                <i class="fas fa-map-marker-alt fs-3"></i>
                            </div>
                            <div>
                                <h4 class="h5">Visit Us</h4>
                                <p class="mb-0">314 Imam Haron Road<br>Landsdowne, Cape Town<br>South Africa</p>
                            </div>
                        </div>
                        <div class="d-flex mb-4">
                            <div class="me-3 text-primary">
                                <i class="fas fa-envelope fs-3"></i>
                            </div>
                            <div>
                                <h4 class="h5">Email Us</h4>
                                <p class="mb-0">
                                    <a href="mailto:info@homecafe.com" class="email-link" style="color: var(--dark-coffee);">info@brewcraft.com</a><br>
                                    <a href="mailto:support@homecafe.com" class="email-link" style="color: var(--dark-coffee);">support@brewcraft.com</a>
                                </p>
                            </div>
                        </div>
                        <div class="d-flex mb-4">
                            <div class="me-3 text-primary">
                                <i class="fas fa-phone-alt fs-3"></i>
                            </div>
                            <div>
                                <h4 class="h5">Call Us</h4>
                                <p class="mb-0">(123) 456-7890<br>Mon-Fri: 9am-5pm</p>
                            </div>
                        </div>
                        <div class="d-flex">
                            <div class="me-3 text-primary">
                                <i class="fas fa-share-alt fs-3"></i>
                            </div>
                            <div>
                                <h4 class="h5">Follow Us</h4>
                                <div class="social-links">
                                    <a href="https://www.instagram.com/_brewcraft_?igsh=MWU4YXdzb2NjeDh4Nw%3D%3D&utm_source=qr" class="text-decoration-none me-3"><i class="fab fa-instagram" style="color: black"></i></a>
                                    <a href="https://www.tiktok.com/@brew_craft?_t=ZS-8z0WYF4zzwF&_r=1" class="text-decoration-none"><i class="fab fa-tiktok" style="color: black"></i></a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Map Section -->
    <section class="map-section py-0">
        <div class="container-fluid px-0">
            <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3308.32571565896!2d18.42381531521239!3d-33.99882598062148!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x1dcc676f8b30aed1%3A0x9f7a5d8e814b627!2sCape%20Town%2C%20South%20Africa!5e0!3m2!1sen!2sus!4v1620000000000!5m2!1sen!2sus"
                    width="100%"
                    height="450"
                    style="border:0;"
                    allowfullscreen=""
                    loading="lazy"></iframe>
        </div>
    </section>
    <?php include 'footer.php' ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
   <script>
    document.getElementById('contactForm').addEventListener('submit', function(e) {
    });
</script>
</body>
</html>

