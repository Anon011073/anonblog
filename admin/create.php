<?php
// admin/create.php

// This page is protected and requires login.
require_once 'auth.php';
require_login();

// Load existing categories to provide suggestions in the form
$categories_json = file_get_contents(__DIR__ . '/../data/categories.json');
$categories = json_decode($categories_json, true);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Create New Post</title>
    <link rel="stylesheet" href="../public/css/style.css">
    <style>
        .admin-container {
            max-width: 960px;
            margin: 40px auto;
            padding: 20px;
        }
        .form-group {
            margin-bottom: 20px;
        }
        label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
        }
        input[type="text"],
        textarea {
            box-sizing: border-box;
            width: 100%;
            padding: 12px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        textarea {
            min-height: 400px;
            font-family: "SFMono-Regular", Consolas, "Liberation Mono", Menlo, Courier, monospace;
            line-height: 1.6;
        }
        .btn {
            padding: 12px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 1em;
            text-decoration: none;
            color: white;
        }
        .btn-submit {
            background-color: #007bff;
        }
        .btn-submit:hover {
            background-color: #0056b3;
        }
        .markdown-guide {
            margin-top: 30px;
            padding: 20px;
            background-color: #f8f9fa;
            border-radius: 4px;
            border: 1px solid #e7e7e7;
        }
        .markdown-guide h4 {
            margin-top: 0;
            border-bottom: 1px solid #ddd;
            padding-bottom: 10px;
            margin-bottom: 15px;
        }
        .markdown-guide ul {
            list-style: none;
            padding: 0;
            margin: 0;
            font-family: "SFMono-Regular", Consolas, "Liberation Mono", Menlo, Courier, monospace;
            font-size: 0.9em;
        }
        .markdown-guide li {
            margin-bottom: 8px;
            padding: 8px 12px;
            background-color: #fff;
            border-radius: 3px;
            border: 1px solid #eee;
        }
    </style>
</head>
<body>
    <div class="admin-container">
        <header style="margin-bottom: 20px;">
            <h1>Create a New Post</h1>
            <a href="dashboard.php">&larr; Back to Dashboard</a>
        </header>

        <hr style="margin-bottom: 20px;">

        <form action="save-post.php" method="post">
            <div class="form-group">
                <label for="title">Post Title</label>
                <input type="text" id="title" name="title" required placeholder="Enter your post title here">
            </div>
            <div class="form-group">
                <label for="category">Category</label>
                <input type="text" id="category" name="category" list="category-suggestions" required placeholder="e.g., Technology" value="General">
                <datalist id="category-suggestions">
                    <?php foreach ($categories as $category): ?>
                        <option value="<?php echo htmlspecialchars($category); ?>">
                    <?php endforeach; ?>
                </datalist>
            </div>

            <div class="form-group">
                <label for="content">Content (in Markdown)</label>
                <textarea id="content" name="content" required placeholder="Write your post content using Markdown..."></textarea>
            </div>
            <button type="submit" class="btn btn-submit">Save and Publish</button>
        </form>

        <div class="markdown-guide">
            <h4>Markdown Quick Guide</h4>
            <ul>
                <li><code># Heading 1</code></li>
                <li><code>## Heading 2</code></li>
                <li><code>**bold text**</code> or <code>__bold text__</code></li>
                <li><code>*italic text*</code> or <code>_italic text_</code></li>
                <li><code>[Link Text](https://example.com)</code></li>
                <li><code>![Image Alt Text](/path/to/image.jpg)</code></li>
                <li><code>- Unordered list item</code></li>
                <li><code>1. Ordered list item</code></li>
                <li><code>> Blockquote</code></li>
                <li><code>`inline code`</code></li>
                <li><code>---</code> (Horizontal Rule)</li>
                <li><pre><code>```php&#10;echo "A code block";&#10;```</code></pre></li>
            </ul>
        </div>
    </div>
</body>
</html>
