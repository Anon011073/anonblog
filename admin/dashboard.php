<?php
// admin/dashboard.php
include 'partials/header.php';
?>

<div class="page-header">
    <h1>Dashboard</h1>
</div>

<?php if (isset($_GET['success'])): ?>
    <div class="card" style="background-color: #d4edda; color: #155724; border-color: #c3e6cb; margin-bottom: 20px;">
        <?php echo htmlspecialchars($_GET['success']); ?>
    </div>
<?php endif; ?>

<div class="card">
    <h2>Welcome, Admin!</h2>
    <p>This is your control panel. From here, you can manage your blog posts, themes, plugins, and settings.</p>
    <p style="margin-top: 20px;">
        <a href="create.php" class="btn">Create New Post</a>
    </p>
</div>

<?php
include 'partials/footer.php';
?>
