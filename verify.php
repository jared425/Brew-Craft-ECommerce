<?php
require_once 'functions.php';
require_once 'db.php';

$message = '';

if (isset($_GET['token'])) {
    $token = $_GET['token'];
    
    try {
        // Check if token exists and is not expired
        $stmt = $pdo->prepare("SELECT id FROM users WHERE verification_token = ? AND token_expires_at > NOW() AND is_verified = 0");
        $stmt->execute([$token]);
        $user = $stmt->fetch();
        
        if ($user) {
            // Mark user as verified
            $stmt = $pdo->prepare("UPDATE users SET is_verified = 1, verification_token = NULL, token_expires_at = NULL WHERE id = ?");
            $stmt->execute([$user['id']]);
            
            // Auto-login the user
            $_SESSION['user_id'] = $user['id'];
            
            // Get user details for session
            $stmt = $pdo->prepare("SELECT username, email FROM users WHERE id = ?");
            $stmt->execute([$user['id']]);
            $userDetails = $stmt->fetch();
            
            $_SESSION['username'] = $userDetails['username'];
            $_SESSION['email'] = $userDetails['email'];
            
            // Redirect to either the stored URL or home page
            $redirect = $_SESSION['redirect_url'] ?? 'index.php';
            unset($_SESSION['redirect_url']);
            header("Location: $redirect");
            exit;
        } else {
            $message = "Invalid or expired verification token.";
        }
    } catch (PDOException $e) {
        $message = "Error verifying email: " . $e->getMessage();
    }
} else {
    $message = "No verification token provided.";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Email Verification</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
    <?php include 'navbar.php'; ?>
    
    <section class="verification-section py-5">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-6">
                    <div class="card shadow">
                        <div class="card-header bg-primary text-white">
                            <h3 class="mb-0 text-center">Email Verification</h3>
                        </div>
                        <div class="card-body text-center">
                            <?php if (isset($message)): ?>
                                <p><?= htmlspecialchars($message) ?></p>
                                <?php if (strpos($message, 'Invalid') === false && strpos($message, 'No token') === false): ?>
                                    <a href="index.php" class="btn btn-primary">Continue to Home</a>
                                <?php else: ?>
                                    <a href="signup.php" class="btn btn-primary">Sign Up Again</a>
                                <?php endif; ?>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    
    <?php include 'footer.php'; ?>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>