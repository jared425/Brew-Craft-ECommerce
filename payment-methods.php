<?php require_once 'functions.php';

requireVerifiedUser();

$userId = $_SESSION['user_id'];
$paymentMethods = getUserPaymentMethods($userId);

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['add_card'])) {
        // Add card payment method
        $cardNumber = str_replace(' ', '', $_POST['card_number']);
        $expiry = $_POST['card_expiry'];
        $cvv = $_POST['card_cvv'];
        
        // Server-side validation
        if (strlen($cardNumber) !== 16 || !is_numeric($cardNumber)) {
            $error = "Invalid card number - must be 16 digits";
        } elseif (!preg_match('/^\d{2}\/\d{2}$/', $expiry)) {
            $error = "Invalid expiry date format (MM/YY)";
        } else {
            // Validate expiry date
            list($month, $year) = explode('/', $expiry);
            $month = (int)$month;
            $year = (int)$year;
            $currentYear = (int)date('y');
            $currentMonth = (int)date('m');
            
            if ($month < 1 || $month > 12) {
                $error = "Invalid month - must be between 01 and 12";
            } elseif ($year < $currentYear || ($year == $currentYear && $month < $currentMonth)) {
                $error = "Card has expired";
            } elseif (!preg_match('/^\d{3,4}$/', $cvv)) {
                $error = "Invalid CVV number";
            } else {
                try {
                    $stmt = $pdo->prepare("
                        INSERT INTO payment_methods 
                        (user_id, method_type, card_number, card_expiry, card_cvv, is_default) 
                        VALUES (?, 'card', ?, ?, ?, ?)
                    ");
                    $isDefault = empty($paymentMethods) ? 1 : 0;
                    $stmt->execute([$userId, $cardNumber, $expiry, $cvv, $isDefault]);
                    header('Location: payment-methods.php');
                    exit;
                } catch (PDOException $e) {
                    $error = "Error saving payment method: " . $e->getMessage();
                }
            }
        }
    } elseif (isset($_POST['add_paypal'])) {
        // Add PayPal payment method
        $username = $_POST['paypal_username'];
        $account = $_POST['paypal_account'];
        
        if (empty($username) || empty($account)) {
            $error = "Please fill all PayPal fields";
        } elseif (strlen($account) !== 10 || !is_numeric($account)) {
            $error = "PayPal account number must be 10 digits";
        } else {
            try {
                $stmt = $pdo->prepare("
                    INSERT INTO payment_methods 
                    (user_id, method_type, paypal_username, paypal_account, is_default) 
                    VALUES (?, 'paypal', ?, ?, ?)
                ");
                $isDefault = empty($paymentMethods) ? 1 : 0;
                $stmt->execute([$userId, $username, $account, $isDefault]);
                header('Location: payment-methods.php');
                exit;
            } catch (PDOException $e) {
                $error = "Error saving payment method: " . $e->getMessage();
            }
        }
    } elseif (isset($_POST['add_ozow'])) {
        // Add Ozow payment method
        $username = $_POST['ozow_username'];
        $account = $_POST['ozow_account'];
        
        if (empty($username) || empty($account)) {
            $error = "Please fill all Ozow fields";
        } elseif (strlen($account) !== 10 || !is_numeric($account)) {
            $error = "Ozow account number must be 10 digits";
        } else {
            try {
                $stmt = $pdo->prepare("
                    INSERT INTO payment_methods 
                    (user_id, method_type, ozow_username, ozow_account, is_default) 
                    VALUES (?, 'ozow', ?, ?, ?)
                ");
                $isDefault = empty($paymentMethods) ? 1 : 0;
                $stmt->execute([$userId, $username, $account, $isDefault]);
                header('Location: payment-methods.php');
                exit;
            } catch (PDOException $e) {
                $error = "Error saving payment method: " . $e->getMessage();
            }
        }
    } elseif (isset($_POST['set_default'])) {
        // Set default payment method
        $methodId = $_POST['method_id'];
        
        try {
            $pdo->beginTransaction();
            
            // Reset all defaults
            $stmt = $pdo->prepare("UPDATE payment_methods SET is_default = 0 WHERE user_id = ?");
            $stmt->execute([$userId]);
            
            // Set new default
            $stmt = $pdo->prepare("UPDATE payment_methods SET is_default = 1 WHERE id = ? AND user_id = ?");
            $stmt->execute([$methodId, $userId]);
            
            $pdo->commit();
            header('Location: payment-methods.php');
            exit;
        } catch (PDOException $e) {
            $pdo->rollBack();
            $error = "Error updating payment method: " . $e->getMessage();
        }
    } elseif (isset($_POST['delete_method'])) {
        // Delete payment method
        $methodId = $_POST['method_id'];
        
        try {
            // First check if this payment method is used in any orders
            $stmt = $pdo->prepare("SELECT COUNT(*) as order_count FROM orders WHERE payment_method_id = ?");
            $stmt->execute([$methodId]);
            $result = $stmt->fetch();
            
            if ($result['order_count'] > 0) {
                $error = "Cannot delete this payment method because it's associated with existing orders.";
            } else {
                $stmt = $pdo->prepare("DELETE FROM payment_methods WHERE id = ? AND user_id = ?");
                $stmt->execute([$methodId, $userId]);
                header('Location: payment-methods.php');
                exit;
            }
        } catch (PDOException $e) {
            $error = "Error deleting payment method: " . $e->getMessage();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Methods</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700&family=Playfair+Display:wght@700&display=swap" rel="stylesheet">
</head>
<body>
    <!-- Navigation -->
    <?php include 'navbar.php'; ?>

    <!-- Payment Methods Section -->
    <section class="payment-methods py-5">
        <div class="container">
            <h1 class="mb-5">Payment Methods</h1>
            <?php if (isset($error)): ?>
                <div class="alert alert-danger">
                    <?= $error ?>
                    <?php if (strpos($error, 'associated with existing orders') !== false): ?>
                        <br>You can set another payment method as default instead.
                    <?php endif; ?>
                </div>
            <?php endif; ?>
            
            <div class="row">
                <div class="col-md-8">
                    <div class="card mb-4">
                        <div class="card-header bg-primary text-white">
                            <h5 class="mb-0">Your Payment Methods</h5>
                        </div>
                        <div class="card-body">
                            <?php if (empty($paymentMethods)): ?>
                                <div class="alert alert-info">
                                    You don't have any payment methods saved yet.
                                </div>
                            <?php else: ?>
                                <div class="list-group">
                                    <?php foreach ($paymentMethods as $method): ?>
                                    <div class="list-group-item">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div>
                                                <?php 
                                                switch ($method['method_type']) {
                                                    case 'card':
                                                        echo '<i class="far fa-credit-card me-2"></i> Credit Card ending in ' . substr($method['card_number'], -4);
                                                        break;
                                                    case 'paypal':
                                                        echo '<i class="fab fa-paypal me-2"></i> PayPal: ' . htmlspecialchars($method['paypal_username']);
                                                        break;
                                                    case 'ozow':
                                                        echo '<i class="fas fa-money-bill-wave me-2"></i> Ozow: ' . htmlspecialchars($method['ozow_username']);
                                                        break;
                                                }
                                                ?>
                                                <?php if ($method['is_default']): ?>
                                                    <span class="badge bg-success ms-2">Default</span>
                                                <?php endif; ?>
                                            </div>
                                            <div>
                                                <?php if (!$method['is_default']): ?>
                                                    <form method="post" class="d-inline">
                                                        <input type="hidden" name="method_id" value="<?= $method['id'] ?>">
                                                        <button type="submit" name="set_default" class="btn btn-sm btn-outline-primary me-2">Set Default</button>
                                                    </form>
                                                <?php endif; ?>
                                                <form method="post" class="d-inline">
                                                    <input type="hidden" name="method_id" value="<?= $method['id'] ?>">
                                                    <button type="submit" name="delete_method" class="btn btn-sm btn-outline-danger">Delete</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                    <?php endforeach; ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                    
                    <!-- Add Card Form -->
                    <div class="card mb-4">
                        <div class="card-header bg-primary text-white">
                            <h5 class="mb-0">Add Credit/Debit Card</h5>
                        </div>
                        <div class="card-body">
                            <form method="post">
                                <div class="mb-3">
                                    <label for="card_number" class="form-label">Card Number</label>
                                    <input type="text" class="form-control" id="card_number" name="card_number" 
                                        placeholder="1234 5678 9012 3456" maxlength="19" 
                                        oninput="formatCardNumber(this)" pattern="[0-9\s]{13,19}" required>
                                    <small class="text-muted">Enter 16-digit card number</small>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label for="card_expiry" class="form-label">Expiry Date</label>
                                        <input type="text" class="form-control" id="card_expiry" name="card_expiry" 
                                            placeholder="MM/YY" maxlength="5" 
                                            oninput="formatExpiryDate(this)" pattern="\d{2}/\d{2}" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="card_cvv" class="form-label">CVV</label>
                                        <input type="text" class="form-control" id="card_cvv" name="card_cvv" 
                                            placeholder="123" maxlength="4" pattern="\d{3,4}" required>
                                    </div>
                                </div>
                                <button type="submit" name="add_card" class="btn btn-primary">Add Card</button>
                            </form>
                        </div>
                    </div>
                    
                    <!-- Add PayPal Form -->
                    <div class="card mb-4">
                        <div class="card-header bg-primary text-white">
                            <h5 class="mb-0">Add PayPal Account</h5>
                        </div>
                        <div class="card-body">
                            <form method="post">
                                <div class="mb-3">
                                    <label for="paypal_username" class="form-label">PayPal Username</label>
                                    <input type="text" class="form-control" id="paypal_username" name="paypal_username" required>
                                </div>
                                <div class="mb-3">
                                    <label for="paypal_account" class="form-label">PayPal Account Number</label>
                                    <input type="text" class="form-control" id="paypal_account" name="paypal_account" 
                                        maxlength="10" pattern="\d{10}" oninput="this.value=this.value.replace(/[^\d]/g,'')" required>
                                    <small class="text-muted">Enter 10-digit account number</small>
                                </div>
                                <button type="submit" name="add_paypal" class="btn btn-primary">Add PayPal</button>
                            </form>
                        </div>
                    </div>
                    
                    <!-- Add Ozow Form -->
                    <div class="card">
                        <div class="card-header bg-primary text-white">
                            <h5 class="mb-0">Add Ozow Account</h5>
                        </div>
                        <div class="card-body">
                            <form method="post">
                                <div class="mb-3">
                                    <label for="ozow_username" class="form-label">Ozow Username</label>
                                    <input type="text" class="form-control" id="ozow_username" name="ozow_username" required>
                                </div>
                                <div class="mb-3">
                                    <label for="ozow_account" class="form-label">Ozow Account Number</label>
                                    <input type="text" class="form-control" id="ozow_account" name="ozow_account" 
                                        maxlength="10" pattern="\d{10}" oninput="this.value=this.value.replace(/[^\d]/g,'')" required>
                                    <small class="text-muted">Enter 10-digit account number</small>
                                </div>
                                <button type="submit" name="add_ozow" class="btn btn-primary">Add Ozow</button>
                            </form>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-header bg-primary text-white">
                            <h5 class="mb-0">Payment Security</h5>
                        </div>
                        <div class="card-body">
                            <div class="alert alert-info">
                                <i class="fas fa-lock me-2"></i>All payment information is securely encrypted and stored.
                            </div>
                            <p>We accept the following payment methods:</p>
                            <ul>
                                <li>Visa</li>
                                <li>Mastercard</li>
                                <li>American Express</li>
                                <li>PayPal</li>
                                <li>Ozow</li>
                            </ul>
                            <p>Your payment details will be saved for faster checkout on future orders.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <?php include 'footer.php'; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
    <script>
        // Format card number as 4 groups of 4 digits
        function formatCardNumber(input) {
            // Remove all non-digits
            let value = input.value.replace(/\D/g, '');
            
            // Limit to 16 digits
            if (value.length > 16) {
                value = value.substring(0, 16);
            }
            
            // Add space after every 4 digits
            value = value.replace(/(\d{4})(?=\d)/g, '$1 ');
            
            input.value = value;
        }

        // Format expiry date as MM/YY and validate
        function formatExpiryDate(input) {
            let value = input.value.replace(/\D/g, '');
            
            // Limit to 4 digits (MMYY)
            if (value.length > 4) {
                value = value.substring(0, 4);
            }
            
            // Add slash after 2 digits (MM)
            if (value.length > 2) {
                value = value.substring(0, 2) + '/' + value.substring(2);
            }
            
            input.value = value;
            
            // Validate in real-time
            validateExpiryDate(input);
        }
        
        function validateExpiryDate(input) {
            const value = input.value;
            if (!value || value.length < 5) return;
            
            const [monthStr, yearStr] = value.split('/');
            const month = parseInt(monthStr, 10);
            const year = parseInt(yearStr, 10);
            
            // Get current date parts
            const currentDate = new Date();
            const currentYear = currentDate.getFullYear() % 100; // Get last 2 digits
            const currentMonth = currentDate.getMonth() + 1; // Months are 0-indexed
            
            // Validate month
            if (month < 1 || month > 12) {
                input.setCustomValidity('Invalid month (must be 01-12)');
                return;
            }
        
            // Validate year
            if (year < currentYear || (year === currentYear && month < currentMonth)) {
                input.setCustomValidity('Card has expired - please enter a future date');
                return;
            }
            
            // If valid, clear any previous error
            input.setCustomValidity('');
        }
        
        document.addEventListener('DOMContentLoaded', function() {
            // Add event listener for blur to validate when user leaves the field
            document.getElementById('card_expiry').addEventListener('blur', function() {
                validateExpiryDate(this);
            });
            
            // Restrict input to numbers only for specific fields
            // Card number field
            document.getElementById('card_number').addEventListener('keypress', function(e) {
                if (e.key < '0' || e.key > '9') {
                    e.preventDefault();
                }
            });
            
            // CVV field
            document.getElementById('card_cvv').addEventListener('keypress', function(e) {
                if (e.key < '0' || e.key > '9') {
                    e.preventDefault();
                }
            });
            
            // PayPal account field
            document.getElementById('paypal_account').addEventListener('keypress', function(e) {
                if (e.key < '0' || e.key > '9') {
                    e.preventDefault();
                }
            });
            
            // Ozow account field
            document.getElementById('ozow_account').addEventListener('keypress', function(e) {
                if (e.key < '0' || e.key > '9') {
                    e.preventDefault();
                }
            });
        });
    </script>
</body>
</html>