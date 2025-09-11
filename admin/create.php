<?php
// admin/create.php

// This page is protected and requires login.
require_once 'auth.php';
require_login();

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
                <label for="content">Content (in Markdown)</label>
                <textarea id="content" name="content" required placeholder="Write your post content using Markdown..."></textarea>
            </div>
            <button type="submit" class="btn btn-submit">Save and Publish</button>
        </form>
    </div>
</body>
</html>
