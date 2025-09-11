<?php
// admin/dashboard.php

// This is a protected page. It requires the user to be logged in.
require_once 'auth.php';
require_login();

// Get any success message from the URL query string
$success_message = $_GET['success'] ?? '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="../public/css/style.css">
    <style>
        .admin-container {
            max-width: 960px;
            margin: 40px auto;
            padding: 20px;
        }
        .admin-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 1px solid #eee;
            padding-bottom: 15px;
            margin-bottom: 20px;
        }
        .admin-header h1 {
            margin: 0;
        }
        .admin-nav a {
            margin-left: 15px;
        }
        .btn-create {
            display: inline-block;
            padding: 12px 20px;
            background-color: #28a745;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            font-size: 1.1em;
        }
        .btn-create:hover {
            background-color: #218838;
        }
        .success-message {
            background-color: #D4EDDA;
            color: #155724;
            padding: 15px;
            border: 1px solid #C3E6CB;
            border-radius: 5px;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <div class="admin-container">
        <header class="admin-header">
            <h1>Dashboard</h1>
            <nav class="admin-nav">
                <a href="../public/index.php" target="_blank">View Live Site</a>
                <a href="logout.php">Logout</a>
            </nav>
        </header>

        <main>
            <?php if ($success_message): ?>
                <div class="success-message">
                    <?php echo htmlspecialchars($success_message); ?>
                </div>
            <?php endif; ?>

            <h2>Welcome, Admin!</h2>
            <p>From this dashboard, you can manage your blog content.</p>
            <hr>
            <h3>Manage Posts</h3>
            <p>
                <a href="create.php" class="btn-create">Create New Post</a>
            </p>
            <!-- In the future, a list of existing posts could go here -->
        </main>
    </div>
</body>
</html>
