<?php
// admin/create.php
include 'partials/header.php';

// Load existing categories for the datalist
$categories_json = file_get_contents(__DIR__ . '/../data/categories.json');
$categories = json_decode($categories_json, true);
?>

<div class="page-header">
    <h1>Create New Post</h1>
    <a href="posts.php" class="btn">&larr; Back to All Posts</a>
</div>

<div class="card">
    <form action="save-post.php" method="post">
        <div class="form-group">
            <label for="title">Post Title</label>
            <input type="text" id="title" name="title" required placeholder="Enter your post title here">
        </div>
        <div class="form-group">
            <label for="category">Category</label>
            <input type="text" id="category" name="category" list="category-suggestions" required placeholder="e.g., Technology" value="General">
        </div>
        <div class="form-group">
            <label for="featured_image">Featured Image URL (Optional)</label>
            <input type="url" id="featured_image" name="featured_image" placeholder="https://example.com/image.jpg">
            <datalist id="category-suggestions">
                <?php foreach ($categories as $category): ?>
                    <option value="<?php echo htmlspecialchars($category); ?>"></option>
                <?php endforeach; ?>
            </datalist>
        </div>
        <div class="form-group">
            <label for="content">Content (in Markdown)</label>
            <textarea id="content" name="content" required placeholder="Write your post content using Markdown..."></textarea>
        </div>
        <button type="submit" class="btn">Save and Publish</button>
    </form>
</div>

<div class="card" style="margin-top: 20px;">
    <h4>Markdown Quick Guide</h4>
    <ul>
        <li><code># Heading 1</code> | <code>**bold**</code> | <code>*italic*</code></li>
        <li><code>[Link](url)</code> | <code>![Image](url)</code> | <code>`code`</code></li>
        <li><code>- List item</code> | <code>1. Numbered item</code> | <code>> Quote</code></li>
    </ul>
    <style>.markdown-guide ul { font-family: monospace; } .markdown-guide li { margin-bottom: 5px; }</style>
</div>


<?php
include 'partials/footer.php';
?>
