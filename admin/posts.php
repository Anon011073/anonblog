<?php
// admin/posts.php
include 'partials/header.php';
// We need the config file for the core functions to work
require_once __DIR__ . '/../config.php';
// We need the core functions to fetch post data
require_once __DIR__ . '/../src/core.php';

// Fetch all posts to display in the table
$posts = get_all_posts();
?>

<div class="page-header">
    <h1>Manage Posts</h1>
    <a href="create.php" class="btn">Add New Post</a>
</div>

<?php if (isset($_GET['success'])): ?>
    <div class="card" style="background-color: #d4edda; color: #155724; border-color: #c3e6cb; margin-bottom: 20px;">
        <?php echo htmlspecialchars($_GET['success']); ?>
    </div>
<?php endif; ?>

<div class="card">
    <table class="posts-table">
        <thead>
            <tr>
                <th>Title</th>
                <th>Category</th>
                <th>Date</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($posts)): ?>
                <tr>
                    <td colspan="4" style="text-align: center; padding: 20px;">No posts found. Why not create one?</td>
                </tr>
            <?php else: ?>
                <?php foreach ($posts as $post): ?>
                    <tr>
                        <td><strong><?php echo htmlspecialchars($post['title']); ?></strong></td>
                        <td><?php echo htmlspecialchars($post['category']); ?></td>
                        <td><?php echo date('Y-m-d', $post['timestamp']); ?></td>
                        <td class="actions">
                            <a href="edit.php?slug=<?php echo htmlspecialchars($post['slug']); ?>" class="btn-edit">Edit</a>
                            <a href="delete.php?slug=<?php echo htmlspecialchars($post['slug']); ?>" class="btn-delete">Delete</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<?php
include 'partials/footer.php';
?>
