<?php
require_once 'functions.php';
// Check if admin is logged in
if (!isset($_SESSION['is_admin'])) {
    header('Location: login.php');
    exit;
}
// Mark message as read
if (isset($_GET['mark_read'])) {
    $id = $_GET['mark_read'];
    $stmt = $pdo->prepare("UPDATE contact_messages SET is_read = 1 WHERE id = ?");
    $stmt->execute([$id]);
    header('Location: admin-messages.php');
    exit;
}
// Delete message
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $stmt = $pdo->prepare("DELETE FROM contact_messages WHERE id = ?");
    $stmt->execute([$id]);
    header('Location: admin-messages.php');
    exit;
}
// Get all messages
$stmt = $pdo->query("SELECT * FROM contact_messages ORDER BY created_at DESC");
$messages = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Messages - Admin Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
    <style>
        .unread {
            background-color: #F8F9FA;
            font-weight: bold;
        }
        .message-content {
            white-space: pre-wrap;
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
                        <a class="nav-link" href="admin.php">Products</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="admin-messages.php">Messages</a>
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
        <h1>Contact Messages</h1>
        <?php if (empty($messages)): ?>
            <div class="alert alert-info">No messages found.</div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Subject</th>
                            <th>Date</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($messages as $message): ?>
                            <tr class="<?= $message['is_read'] ? '' : 'unread' ?>">
                                <td><?= htmlspecialchars($message['name']) ?></td>
                                <td><a href="mailto:<?= htmlspecialchars($message['email']) ?>"><?= htmlspecialchars($message['email']) ?></a></td>
                                <td><?= htmlspecialchars($message['subject']) ?></td>
                                <td><?= date('M j, Y g:i A', strtotime($message['created_at'])) ?></td>
                                <td><?= $message['is_read'] ? 'Read' : 'Unread' ?></td>
                                <td>
                                    <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#messageModal<?= $message['id'] ?>">
                                        View
                                    </button>
                                    <?php if (!$message['is_read']): ?>
                                        <a href="admin-messages.php?mark_read=<?= $message['id'] ?>" class="btn btn-sm btn-success">Mark Read</a>
                                    <?php endif; ?>
                                    <a href="admin-messages.php?delete=<?= $message['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this message?')">Delete</a>
                                </td>
                            </tr>
                            <!-- Message Modal -->
                            <div class="modal fade" id="messageModal<?= $message['id'] ?>" tabindex="-1" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Message from <?= htmlspecialchars($message['name']) ?></h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <p><strong>Email:</strong> <?= htmlspecialchars($message['email']) ?></p>
                                            <p><strong>Subject:</strong> <?= htmlspecialchars($message['subject']) ?></p>
                                            <p><strong>Date:</strong> <?= date('M j, Y g:i A', strtotime($message['created_at'])) ?></p>
                                            <hr>
                                            <div class="message-content"><?= htmlspecialchars($message['message']) ?></div>
                                        </div>
                                        <div class="modal-footer">
                                            <a href="mailto:<?= htmlspecialchars($message['email']) ?>" class="btn btn-primary">Reply</a>
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>