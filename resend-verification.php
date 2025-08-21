<?php
require_once 'functions.php';
require_once 'mailer.php';

if (!isLoggedIn()) {
    header('Location: login.php');
    exit;
}

$success = '';
$error = '';

// Get user details
$stmt = $pdo->prepare("SELECT id, email, first_name, last_name, is_verified FROM users WHERE id = ?");
$stmt->execute([$_SESSION['user_id']]);
$user = $stmt->fetch();

if (!$user) {
    header('Location: login.php');
    exit;
}

if ($user['is_verified']) {
    header('Location: index.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Generate new token
    $token = bin2hex(random_bytes(32));
    $expires = date('Y-m-d H:i:s', strtotime('+24 hours'));
    
    try {
        // Update user with new token
        $stmt = $pdo->prepare("UPDATE users SET verification_token = ?, token_expires_at = ? WHERE id = ?");
        $stmt->execute([$token, $expires, $user['id']]);
        
        // Send verification email
        if (sendVerificationEmail($user['email'], $user['first_name'] . ' ' . $user['last_name'], $token)) {
            $success = "Verification email sent! Please check your inbox.";
        } else {
            $error = "Failed to send verification email. Please try again later.";
        }
    } catch (PDOException $e) {
        $error = "Database error: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Resend Verification</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
    <?php include 'navbar.php'; ?>
    
    <section class="resend-verification py-5">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-6">
                    <div class="card shadow">
                        <div class="card-header bg-primary text-white">
                            <h3 class="mb-0 text-center">Resend Verification Email</h3>
                        </div>
                        <div class="card-body">
                            <?php if ($error): ?>
                                <div class="alert alert-danger"><?= $error ?></div>
                            <?php endif; ?>
                            
                            <?php if ($success): ?>
                                <div class="alert alert-success"><?= $success ?></div>
                            <?php else: ?>
                                <p>We'll send a new verification link to <?= htmlspecialchars($user['email']) ?>.</p>
                                <form method="post">
                                    <button type="submit" class="btn btn-primary w-100">Resend Verification Email</button>
                                </form>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    
    <?php include 'footer.php'; ?>
</body>
</html>