<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require 'db.php';

function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

function isAdmin() {
    return isset($_SESSION['is_admin']) && $_SESSION['is_admin'] === true;
}

function requireVerifiedUser() {
    if (!isLoggedIn()) {
        // Store the current URL for redirect after login
        $_SESSION['redirect_url'] = $_SERVER['REQUEST_URI'];
        header('Location: login.php');
        exit;
    }
    
    // Check if user is verified
    global $pdo;
    $stmt = $pdo->prepare("SELECT is_verified FROM users WHERE id = ?");
    $stmt->execute([$_SESSION['user_id']]);
    $user = $stmt->fetch();
    
    if (!$user || !$user['is_verified']) {
        // Store the current URL for redirect after verification
        $_SESSION['redirect_url'] = $_SERVER['REQUEST_URI'];
        header('Location: verify-pending.php');
        exit;
    }
}

function getUserCartCount($userId) {
    global $pdo;
    $stmt = $pdo->prepare("SELECT SUM(quantity) as total FROM cart WHERE user_id = ?");
    $stmt->execute([$userId]);
    $result = $stmt->fetch();
    return $result['total'] ?? 0;
}

function addToCart($userId, $productId, $quantity = 1) {
    global $pdo;
    
    // Check if product already in cart
    $stmt = $pdo->prepare("SELECT * FROM cart WHERE user_id = ? AND product_id = ?");
    $stmt->execute([$userId, $productId]);
    $existingItem = $stmt->fetch();
    
    if ($existingItem) {
        // Update quantity
        $newQuantity = $existingItem['quantity'] + $quantity;
        $stmt = $pdo->prepare("UPDATE cart SET quantity = ? WHERE id = ?");
        $stmt->execute([$newQuantity, $existingItem['id']]);
    } else {
        // Add new item
        $stmt = $pdo->prepare("INSERT INTO cart (user_id, product_id, quantity) VALUES (?, ?, ?)");
        $stmt->execute([$userId, $productId, $quantity]);
    }
}

function getCartItems($userId) {
    global $pdo;
    $stmt = $pdo->prepare("
        SELECT c.*, p.name, p.price, p.image_path, (p.price * c.quantity) as total_price 
        FROM cart c 
        JOIN products p ON c.product_id = p.id 
        WHERE c.user_id = ?
    ");
    $stmt->execute([$userId]);
    return $stmt->fetchAll();
}

function getCartTotal($userId) {
    global $pdo;
    $stmt = $pdo->prepare("
        SELECT SUM(p.price * c.quantity) as total 
        FROM cart c 
        JOIN products p ON c.product_id = p.id 
        WHERE c.user_id = ?
    ");
    $stmt->execute([$userId]);
    $result = $stmt->fetch();
    return $result['total'] ?? 0;
}

function removeFromCart($userId, $productId) {
    global $pdo;
    $stmt = $pdo->prepare("DELETE FROM cart WHERE user_id = ? AND product_id = ?");
    $stmt->execute([$userId, $productId]);
}

function updateCartQuantity($userId, $productId, $quantity) {
    global $pdo;
    $stmt = $pdo->prepare("UPDATE cart SET quantity = ? WHERE user_id = ? AND product_id = ?");
    $stmt->execute([$quantity, $userId, $productId]);
}

function getUserPaymentMethods($userId) {
    global $pdo;
    $stmt = $pdo->prepare("SELECT * FROM payment_methods WHERE user_id = ?");
    $stmt->execute([$userId]);
    return $stmt->fetchAll();
}

function hasPaymentMethod($userId) {
    global $pdo;
    $stmt = $pdo->prepare("SELECT COUNT(*) as count FROM payment_methods WHERE user_id = ?");
    $stmt->execute([$userId]);
    $result = $stmt->fetch();
    return $result['count'] > 0;
}

function createOrder($userId, $paymentMethodId, $totalAmount) {
    global $pdo;
    
    try {
        $pdo->beginTransaction();
        
        // Create order
        $stmt = $pdo->prepare("
            INSERT INTO orders (user_id, payment_method_id, total_amount) 
            VALUES (?, ?, ?)
        ");
        $stmt->execute([$userId, $paymentMethodId, $totalAmount]);
        $orderId = $pdo->lastInsertId();
        
        // Add order items
        $cartItems = getCartItems($userId);
        foreach ($cartItems as $item) {
            $stmt = $pdo->prepare("
                INSERT INTO order_items (order_id, product_id, quantity, price) 
                VALUES (?, ?, ?, ?)
            ");
            $stmt->execute([$orderId, $item['product_id'], $item['quantity'], $item['price']]);
            
            // Update product stock
            $stmt = $pdo->prepare("UPDATE products SET stock = stock - ? WHERE id = ?");
            $stmt->execute([$item['quantity'], $item['product_id']]);
        }
        
        // Clear cart
        $stmt = $pdo->prepare("DELETE FROM cart WHERE user_id = ?");
        $stmt->execute([$userId]);
        
        $pdo->commit();
        return $orderId;
    } catch (Exception $e) {
        $pdo->rollBack();
        throw $e;
    }
}

function getProducts($filters = []) {
    global $pdo;
    
    $where = [];
    $params = [];
    
    if (!empty($filters['category'])) {
        $where[] = "category = ?";
        $params[] = $filters['category'];
    }
    
    if (!empty($filters['min_price'])) {
        $where[] = "price >= ?";
        $params[] = $filters['min_price'];
    }
    
    if (!empty($filters['max_price'])) {
        $where[] = "price <= ?";
        $params[] = $filters['max_price'];
    }
    
    $whereClause = $where ? "WHERE " . implode(" AND ", $where) : "";
    
    $orderBy = "ORDER BY created_at DESC";
    if (!empty($filters['sort'])) {
        switch ($filters['sort']) {
            case 'price_asc':
                $orderBy = "ORDER BY price ASC";
                break;
            case 'price_desc':
                $orderBy = "ORDER BY price DESC";
                break;
            case 'name_asc':
                $orderBy = "ORDER BY name ASC";
                break;
            case 'name_desc':
                $orderBy = "ORDER BY name DESC";
                break;
        }
    }
    
    $stmt = $pdo->prepare("SELECT * FROM products $whereClause $orderBy");
    $stmt->execute($params);
    return $stmt->fetchAll();
}

function getCoffeeProducts($limit = 4) {
    global $pdo;
    // Convert limit to integer to ensure proper type
    $limit = (int)$limit;
    $stmt = $pdo->prepare("SELECT * FROM products WHERE category = 'coffee' ORDER BY RAND() LIMIT :limit");
    $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetchAll();
}

function isVerified() {
    if (!isLoggedIn()) return false;
    
    global $pdo;
    $stmt = $pdo->prepare("SELECT is_verified FROM users WHERE id = ?");
    $stmt->execute([$_SESSION['user_id']]);
    $user = $stmt->fetch();
    
    return $user && $user['is_verified'];
}

?>
