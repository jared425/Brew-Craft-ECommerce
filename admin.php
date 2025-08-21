<?php
require_once 'functions.php';
// Check if admin is logged in
if (!isset($_SESSION['is_admin'])) {
    header('Location: login.php');
    exit;
}
// Initialize filter and search variables
$category_filter = isset($_GET['category']) ? $_GET['category'] : '';
$search_query = isset($_GET['search']) ? $_GET['search'] : '';
// Build the base SQL query
$sql = "SELECT * FROM products WHERE 1=1";
$params = [];
// Apply category filter if selected
if (!empty($category_filter)) {
    $sql .= " AND category = ?";
    $params[] = $category_filter;
}
// Apply search query if provided
if (!empty($search_query)) {
    $sql .= " AND (name LIKE ? OR description LIKE ?)";
    $search_term = "%$search_query%";
    $params[] = $search_term;
    $params[] = $search_term;
}
// Complete the query with sorting
$sql .= " ORDER BY id DESC";
// Prepare and execute the query
$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$products = $stmt->fetchAll();
// Handle product actions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['add_product'])) {
        // Add new product
        $name = $_POST['name'];
        $description = $_POST['description'];
        $price = $_POST['price'];
        $category = $_POST['category'];
        $image_path = $_POST['image_path'];
        $stock = $_POST['stock'];
        try {
            $stmt = $pdo->prepare("INSERT INTO products (name, description, price, category, image_path, stock) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->execute([$name, $description, $price, $category, $image_path, $stock]);
            $_SESSION['success_message'] = "Product added successfully!";
        } catch (PDOException $e) {
            $_SESSION['error_message'] = "Error adding product: " . $e->getMessage();
        }
    } elseif (isset($_POST['update_product'])) {
        // Update existing product
        $id = $_POST['id'];
        $name = $_POST['name'];
        $description = $_POST['description'];
        $price = $_POST['price'];
        $category = $_POST['category'];
        $image_path = $_POST['image_path'];
        $stock = $_POST['stock'];
        try {
            $stmt = $pdo->prepare("UPDATE products SET name = ?, description = ?, price = ?, category = ?, image_path = ?, stock = ? WHERE id = ?");
            $stmt->execute([$name, $description, $price, $category, $image_path, $stock, $id]);
            $_SESSION['success_message'] = "Product updated successfully!";
        } catch (PDOException $e) {
            $_SESSION['error_message'] = "Error updating product: " . $e->getMessage();
        }
    } elseif (isset($_POST['delete_product'])) {
        // Delete product
        $id = $_POST['id'];
        try {
            $stmt = $pdo->prepare("DELETE FROM products WHERE id = ?");
            $stmt->execute([$id]);
            $_SESSION['success_message'] = "Product deleted successfully!";
        } catch (PDOException $e) {
            $_SESSION['error_message'] = "Error deleting product: " . $e->getMessage();
        }
    }
    // Redirect to avoid form resubmission
    header('Location: admin.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
    <style>
        .product-image {
            max-width: 100px;
            max-height: 100px;
        }
        .form-section {
            background-color: #F8F9FA;
            border-radius: 5px;
            padding: 20px;
            margin-bottom: 30px;
        }
        .filter-section {
            background-color: #F8F9FA;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="admin.php">Brew Craft Admin</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="index.php">View Site</a>
                    </li>
                    <li class="nav-item">
                            <a class="nav-link" href="admin-messages.php">Messages</a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="adminDropdown" role="button" data-bs-toggle="dropdown">
                            <?= htmlspecialchars($_SESSION['username']) ?> (Admin)
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><a class="dropdown-item" href="admin.php">Dashboard</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="logout.php">Logout</a></li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    <div class="container mt-4">
        <!-- Success/Error Messages -->
        <?php if (isset($_SESSION['success_message'])): ?>
            <div class="alert alert-success"><?= $_SESSION['success_message'] ?></div>
            <?php unset($_SESSION['success_message']); ?>
        <?php endif; ?>
        <?php if (isset($_SESSION['error_message'])): ?>
            <div class="alert alert-danger"><?= $_SESSION['error_message'] ?></div>
            <?php unset($_SESSION['error_message']); ?>
        <?php endif; ?>
        <!-- Add/Edit Product Form -->
        <div class="form-section mb-5">
            <h2><?= isset($_GET['edit']) ? 'Edit Product' : 'Add New Product' ?></h2>
            <form method="POST">
                <?php if (isset($_GET['edit'])): ?>
                    <?php
                    $editId = $_GET['edit'];
                    $editProduct = $pdo->query("SELECT * FROM products WHERE id = $editId")->fetch();
                    ?>
                    <input type="hidden" name="id" value="<?= $editProduct['id'] ?>">
                <?php endif; ?>
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="name" class="form-label">Product Name</label>
                            <input type="text" class="form-control" id="name" name="name"
                                   value="<?= isset($editProduct) ? htmlspecialchars($editProduct['name']) : '' ?>" required>
                        </div>
                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control" id="description" name="description" rows="3" required><?= isset($editProduct) ? htmlspecialchars($editProduct['description']) : '' ?></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="price" class="form-label">Price</label>
                            <input type="number" step="0.01" class="form-control" id="price" name="price"
                                   value="<?= isset($editProduct) ? $editProduct['price'] : '' ?>" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="category" class="form-label">Category</label>
                            <select class="form-select" id="category" name="category" required>
                                <option value="">Select Category</option>
                                <option value="cafe_kit" <?= (isset($editProduct) && $editProduct['category'] === 'cafe_kit') ? 'selected' : '' ?>>Cafe Kit</option>
                                <option value="mug" <?= (isset($editProduct) && $editProduct['category'] === 'mug') ? 'selected' : '' ?>>Mug</option>
                                <option value="coffee" <?= (isset($editProduct) && $editProduct['category'] === 'coffee') ? 'selected' : '' ?>>Coffee</option>
                                <option value="machines" <?= (isset($editProduct) && $editProduct['category'] === 'machines') ? 'selected' : '' ?>>Machines</option>
                                <option value="decor" <?= (isset($editProduct) && $editProduct['category'] === 'decor') ? 'selected' : '' ?>>Decor</option>
                                <option value="others" <?= (isset($editProduct) && $editProduct['category'] === 'others') ? 'selected' : '' ?>>Others</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="image_path" class="form-label">Image Path</label>
                            <input type="text" class="form-control" id="image_path" name="image_path" placeholder="Paste image address here (URL)"
                                   value="<?= isset($editProduct) ? htmlspecialchars($editProduct['image_path']) : '' ?>" required>
                        </div>
                        <div class="mb-3">
                            <label for="stock" class="form-label">Stock Quantity</label>
                            <input type="number" class="form-control" id="stock" name="stock"
                                   value="<?= isset($editProduct) ? $editProduct['stock'] : '' ?>" required>
                        </div>
                    </div>
                </div>
                <div class="text-end">
                    <?php if (isset($_GET['edit'])): ?>
                        <button type="submit" name="update_product" class="btn btn-primary">Update Product</button>
                        <a href="admin.php" class="btn btn-secondary">Cancel</a>
                    <?php else: ?>
                        <button type="submit" name="add_product" class="btn btn-primary">Add Product</button>
                    <?php endif; ?>
                </div>
            </form>
        </div>
<!-- Filter and Search Section -->
        <div class="filter-section mb-4">
            <h2>Filter Products</h2>
            <form method="GET" class="row g-3">
                <div class="col-md-4">
                    <label for="category" class="form-label">Category</label>
                    <select class="form-select" id="category" name="category">
                        <option value="">All Categories</option>
                        <option value="cafe_kit" <?= $category_filter === 'cafe_kit' ? 'selected' : '' ?>>Cafe Kit</option>
                        <option value="mug" <?= $category_filter === 'mug' ? 'selected' : '' ?>>Mug</option>
                        <option value="coffee" <?= $category_filter === 'coffee' ? 'selected' : '' ?>>Coffee</option>
                        <option value="machines" <?= $category_filter === 'machines' ? 'selected' : '' ?>>Machines</option>
                        <option value="decor" <?= $category_filter === 'decor' ? 'selected' : '' ?>>Decor</option>
                        <option value="others" <?= $category_filter === 'others' ? 'selected' : '' ?>>Others</option>
                    </select>
                </div>
                <div class="col-md-6">
                    <label for="search" class="form-label">Search</label>
                    <input type="text" class="form-control" id="search" name="search" placeholder="Search by name or description" value="<?= htmlspecialchars($search_query) ?>">
                </div>
                <div class="col-md-2 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary me-2">Filter</button>
                    <a href="admin.php" class="btn btn-secondary">Reset</a>
                </div>
            </form>
        </div>
        <!-- Products List -->
        <h2>Manage Products</h2>
        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Image</th>
                        <th>Name</th>
                        <th>Price</th>
                        <th>Category</th>
                        <th>Stock</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($products)): ?>
                        <tr>
                            <td colspan="7" class="text-center">No products found matching your criteria.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($products as $product): ?>
                        <tr>
                            <td><?= $product['id'] ?></td>
                            <td>
                                <?php if ($product['image_path']): ?>
                                    <img src="<?= htmlspecialchars($product['image_path']) ?>" alt="<?= htmlspecialchars($product['name']) ?>" class="product-image">
                                <?php endif; ?>
                            </td>
                            <td><?= htmlspecialchars($product['name']) ?></td>
                            <td>R<?= number_format($product['price'], 2) ?></td>
                            <td><?= ucfirst(str_replace('_', ' ', $product['category'])) ?></td>
                            <td><?= $product['stock'] ?></td>
                            <td>
                                <a href="admin.php?edit=<?= $product['id'] ?>" class="btn btn-sm btn-primary">Edit</a>
                                <form method="POST" style="display: inline-block;">
                                    <input type="hidden" name="id" value="<?= $product['id'] ?>">
                                    <button type="submit" name="delete_product" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this product?')">Delete</button>
                                </form>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
