            </main>
        </div>
        <footer>
            <p><?php echo $config['footer_text'] ?? '&copy; ' . date('Y') . ' ' . htmlspecialchars($config['blog_title']); ?></p>
            <?php if (function_exists('is_logged_in') && is_logged_in()): ?>
                <p><a href="../admin/dashboard.php">Admin</a></p>
            <?php endif; ?>
            <?php if ($config['show_back_to_top'] ?? false): ?>
                <p style="margin-top: 1rem;"><a href="#top">Back to Top</a></p>
            <?php endif; ?>
        </footer>
    </div>
    <?php do_hook('footer_scripts'); ?>
</body>
</html>
