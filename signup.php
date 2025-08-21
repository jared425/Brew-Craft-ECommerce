<?php require_once 'functions.php';
require_once 'mailer.php';
// Redirect if already logged in
if (isLoggedIn()) {
    header('Location: index.php');
    exit;
}
$error = '';
$success = '';
// Handle registration form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $confirmPassword = $_POST['confirm_password'];
    $firstName = trim($_POST['first_name']);
    $lastName = trim($_POST['last_name']);
    $phone = trim($_POST['phone']);
    $address = trim($_POST['address']);
    // Validate inputs
    if (empty($username) || empty($email) || empty($firstName) || empty($lastName)) {
        $error = "Please fill in all required fields";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Invalid email format";
    } elseif ($password !== $confirmPassword) {
        $error = "Passwords do not match";
    } elseif (strlen($password) < 8) {
        $error = "Password must be at least 8 characters long";
    } else {
        try {
            // Check if username or email already exists
            $stmt = $pdo->prepare("SELECT COUNT(*) as count FROM users WHERE username = ? OR email = ?");
            $stmt->execute([$username, $email]);
            $result = $stmt->fetch();
            if ($result['count'] > 0) {
                $error = "Username or email already exists";
            } else {
                // Hash password
                $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
                // Generate verification token
                $token = bin2hex(random_bytes(32));
                $expires = date('Y-m-d H:i:s', strtotime('+24 hours'));
                // Insert new user
                $stmt = $pdo->prepare("
                    INSERT INTO users (username, email, password, first_name, last_name, phone, address, verification_token, token_expires_at)
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)
                ");
                $stmt->execute([$username, $email, $hashedPassword, $firstName, $lastName, $phone, $address, $token, $expires]);
                // Send verification email
                if (sendVerificationEmail($email, "$firstName $lastName", $token)) {
                    $success = "Registration successful! Please check your email to verify your account.";
                } else {
                    $pdo->prepare("DELETE FROM users WHERE email = ?")->execute([$email]);
                    $error = "Failed to send verification email. Please try again or contact support.";
                }
            }
        } catch (PDOException $e) {
            $error = "Database error: " . $e->getMessage();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
<style>
    /* Signup specific styles */
    .signup-container {
        display: flex;
        justify-content: center;
        align-items: center;
        min-height: calc(100vh - 120px);
        padding: 20px;
        background: linear-gradient(145deg,var(--light-coffee), #ffffff);
    }
    .signup-title {
        color: #000;
        text-transform: uppercase;
        letter-spacing: 2px;
        display: block;
        font-weight: bold;
        font-size: x-large;
        margin-bottom: 30px;
        text-align: center;
    }
    .signup-card {
        display: flex;
        flex-direction: column;
        gap: 30px;
        background: linear-gradient(145deg,var(--light-coffee), #ffffff);
        box-shadow: 16px 16px 32px #C8C8C8, -16px -16px 32px #FEFEFE;
        border-radius: 8px;
        padding: 40px;
        width: 420px;
        max-width: 100%;
    }
    .inputBox {
        position: relative;
        width: 100%;
        margin-bottom: 15px;
    }
    .inputBox input, .inputBox textarea {
        width: 100%;
        padding: 12px;
        outline: none;
        border: none;
        color: #000;
        font-size: 1em;
        background: transparent;
        border-left: 2px solid #000;
        border-bottom: 2px solid #000;
        transition: 0.2s;
        border-bottom-left-radius: 8px;
    }
    .inputBox textarea {
        resize: vertical;
        min-height: 80px;
        font-family: inherit;
    }
    .inputBox span {
        margin-top: 5px;
        position: absolute;
        left: 0;
        transform: translateY(-4px);
        margin-left: 10px;
        padding: 10px;
        pointer-events: none;
        font-size: 12px;
        color: #000;
        text-transform: uppercase;
        transition: 0.5s;
        letter-spacing: 3px;
        border-radius: 8px;
    }
    .inputBox input:valid~span,
    .inputBox input:focus~span,
    .inputBox textarea:valid~span,
    .inputBox textarea:focus~span {
        transform: translateX(113px) translateY(-15px);
        font-size: 0.8em;
        padding: 5px 10px;
        background: #000;
        letter-spacing: 0.2em;
        color: #fff;
        border: 2px;
    }
    .inputBox input:valid,
    .inputBox input:focus,
    .inputBox textarea:valid,
    .inputBox textarea:focus {
        border: 2px solid #000;
        border-radius: 8px;
    }
    .signup-btn {
        height: 50px;
        width: 100%;
        border-radius: 5px;
        border: 2px solid #000;
        cursor: pointer;
        background-color: transparent;
        transition: 0.5s;
        text-transform: uppercase;
        font-size: 14px;
        letter-spacing: 2px;
        margin-top: 20px;
    }
    .signup-btn:hover {
        background-color: rgb(0, 0, 0);
        color: white;
    }
    .error-message {
        color: #FF3333;
        font-size: 0.9em;
        margin: -10px 0 15px 0;
        text-align: center;
    }
    .success-message {
        color: #009900;
        font-size: 0.9em;
        margin: -10px 0 20px 0;
        text-align: center;
    }
    .login-link {
        font-size: 0.9em;
        color: #000;
        text-decoration: none;
        transition: 0.3s;
        text-align: center;
        display: block;
        margin-top: 25px;
    }
    .login-link:hover {
        color: #555;
        text-decoration: underline;
    }
    .name-fields {
        display: flex;
        gap: 20px;
        margin-bottom: 15px;
    }
    .name-field {
        flex: 1;
    }
</style>
</head>
<body>
    <!-- Navigation -->
    <?php include 'navbar.php'; ?>
    <div class="signup-container">
        <div class="signup-card">
            <span class="signup-title">Create Account</span>
            <?php if ($error): ?>
                <div class="error-message"><?= htmlspecialchars($error) ?></div>
            <?php endif; ?>
            <?php if ($success): ?>
                <div class="success-message"><?= htmlspecialchars($success) ?></div>
            <?php endif; ?>
            <form method="post">
                <div class="name-fields">
                    <div class="inputBox name-field">
                        <input type="text" name="first_name" id="first_name" required>
                        <span>First Name</span>
                    </div>
                    <div class="inputBox name-field">
                        <input type="text" name="last_name" id="last_name" required>
                        <span>Last Name</span>
                    </div>
                </div>
                <div class="inputBox">
                    <input type="text" name="username" id="username" required>
                    <span>Username</span>
                </div>
                <div class="inputBox">
                    <input type="email" name="email" id="email" required>
                    <span>Email</span>
                </div>
                <div class="inputBox">
                    <input type="tel" name="phone" id="phone" required>
                    <span>Phone</span>
                </div>
                <div class="inputBox">
                    <textarea name="address" id="address" required></textarea>
                    <span>Address</span>
                </div>
                <div class="inputBox">
                    <input type="password" name="password" id="password" required>
                    <span>Password</span>
                </div>
                <div class="inputBox">
                    <input type="password" name="confirm_password" id="confirm_password" required>
                    <span>Confirm Password</span>
                </div>
                <button type="submit" class="signup-btn">Sign Up</button>
            </form>
            <a href="login.php" class="login-link">Already have an account? Login here</a>
        </div>
    </div>
    <!-- Footer -->
    <?php include 'footer.php'; ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>