<?php require_once 'functions.php';
// Redirect if already logged in
if (isLoggedIn()) {
    header('Location: index.php' . (isAdmin() ? 'admin.php' : 'index.php'));
    exit;
}
$error = '';
$redirect = $_GET['redirect'] ?? 'index.php';
// Handle login form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];
    try {
        $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ? OR email = ?");
        $stmt->execute([$username, $username]);
        $user = $stmt->fetch();
        if ($user && password_verify($password, $user['password'])) {
            if (!$user['is_verified']) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['email'] = $user['email'];
                $_SESSION['is_admin'] = (bool)$user['is_admin'];
                header("Location: " . ($user['is_admin'] ? 'admin.php' : $redirect));
                exit;
            }
            // Login successful
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['email'] = $user['email'];
            // Redirect to either the stored URL or home page
            $redirect = $_SESSION['redirect_url'] ?? 'index.php';
            unset($_SESSION['redirect_url']);
            header("Location: $redirect");
            exit;
        } else {
            $error = "Invalid username or password";
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
    <title>Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        /* Login specific styles */
        .login-container {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: calc(100vh - 120px);
            padding: 20px;
            background: linear-gradient(145deg,var(--light-coffee), #ffffff);
        }
        .login-title {
            color: #000;
            text-transform: uppercase;
            letter-spacing: 2px;
            display: block;
            font-weight: bold;
            font-size: x-large;
            margin-bottom: 20px;
            text-align: center;
        }
        .login-card {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 350px;
            width: 300px;
            flex-direction: column;
            gap: 35px;
            background: linear-gradient(145deg, #ffffff, var(--light-coffee));
            box-shadow: 16px 16px 32px #C8C8C8,
                  -16px -16px 32px #FEFEFE;
            border-radius: 8px;
            padding: 20px;
        }
        .inputBox {
            position: relative;
            width: 250px;
        }
        .inputBox input {
            width: 100%;
            padding: 10px;
            outline: none;
            border: none;
            color: #000;
            font-size: 1em;
            background: transparent;
            border-left: 2px solid #000;
            border-bottom: 2px solid #000;
            transition: 0.1s;
            border-bottom-left-radius: 8px;
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
        .inputBox input:focus~span {
            transform: translateX(113px) translateY(-15px);
            font-size: 0.8em;
            padding: 5px 10px;
            background: #000;
            letter-spacing: 0.2em;
            color: #fff;
            border: 2px;
        }
        .inputBox input:valid,
        .inputBox input:focus {
            border: 2px solid #000;
            border-radius: 8px;
        }
        .login-btn {
            height: 45px;
            width: 100px;
            border-radius: 5px;
            border: 2px solid #000;
            cursor: pointer;
            background-color: transparent;
            transition: 0.5s;
            text-transform: uppercase;
            font-size: 10px;
            letter-spacing: 2px;
            margin-bottom: 1em;
        }
        .login-btn:hover {
            background-color: rgb(0, 0, 0);
            color: white;
        }
        .error-message {
            color: #FF3333;
            font-size: 0.9em;
            margin-top: -20px;
            text-align: center;
        }
        .signup-link {
            font-size: 0.9em;
            color: #000;
            text-decoration: none;
            transition: 0.3s;
        }
        .signup-link:hover {
            color: #555;
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    <?php include 'navbar.php'; ?>
    <div class="login-container">
        <div class="login-card">
            <span class="login-title">Log in</span>
            <?php if ($error): ?>
                <div class="error-message"><?= htmlspecialchars($error) ?></div>
            <?php endif; ?>
            <form method="post" style="display: flex; flex-direction: column; align-items: center; gap: 35px;">
                <div class="inputBox">
                    <input type="text" name="username" required="required">
                    <span class="user">Username</span>
                </div>
                <div class="inputBox">
                    <input type="password" name="password" required="required">
                    <span>Password</span>
                </div>
                <button type="submit" class="login-btn">Enter</button>
            </form>
            <a href="signup.php" class="signup-link">Don't have an account? Sign up</a>
        </div>
    </div>
    <!-- Footer -->
    <?php include 'footer.php'; ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>