</main>
        </div>
        <footer>
            <small><?php echo htmlspecialchars($config['footer_text'] ?? '© ' . date('Y')); ?></small>
        </footer>
    </div>
    <a href="#" id="back-to-top" class="pico-button">Back to Top</a>
    <?php do_hook('footer_scripts'); ?>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var backToTopButton = document.getElementById('back-to-top');
            window.onscroll = function() {
                if (document.body.scrollTop > 200 || document.documentElement.scrollTop > 200) {
                    backToTopButton.style.display = 'block';
                } else {
                    backToTopButton.style.display = 'none';
                }
            };
            backToTopButton.onclick = function(e) {
                e.preventDefault();
                document.body.scrollTop = 0;
                document.documentElement.scrollTop = 0;
            };
        });
    </script>
</body>
</html>
