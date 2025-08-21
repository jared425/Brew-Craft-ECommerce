<?php require_once 'functions.php'; 
if (!isLoggedIn()) {
    header('Location: login.php');
    exit;
}

// Get user email for display
global $pdo;
$stmt = $pdo->prepare("SELECT email FROM users WHERE id = ?");
$stmt->execute([$_SESSION['user_id']]);
$user = $stmt->fetch();
$email = $user ? $user['email'] : '';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verification Pending</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
    <?php include 'navbar.php'; ?>
    
    <section class="verification-pending py-5">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-6">
                    <div class="card shadow">
                        <div class="card-header bg-primary text-white">
                            <h3 class="mb-0 text-center">Verification Required</h3>
                        </div>
                        <div class="card-body text-center">
                            <div class="mb-4">
                                <i class="fas fa-envelope fa-4x text-primary mb-3"></i>
                                <h4>Please verify your email address</h4>
                            </div>
                            
                            <p>We've sent a verification link to:</p>
                            <p class="fw-bold"><?= htmlspecialchars($email) ?></p>
                            
                            <p class="text-muted">Check your inbox and click the link in the email we sent you.</p>
                            
                            <div class="alert alert-info">
                                <i class="fas fa-info-circle me-2"></i>
                                Can't find the email? Check your spam folder or request a new verification link below.
                            </div>
                            
                            <div class="d-flex justify-content-center gap-2 mt-4">
                                <a href="resend-verification.php" class="btn btn-primary">
                                    <i class="fas fa-paper-plane me-2"></i>Resend Verification Email
                                </a>
                                <a href="logout.php" class="btn btn-outline-secondary">
                                    <i class="fas fa-sign-out-alt me-2"></i>Logout
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    
    <?php include 'footer.php'; ?>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
</body>
</html>