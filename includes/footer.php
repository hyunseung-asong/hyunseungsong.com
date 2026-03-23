<?php if (!isset($site_root_prefix)) { $site_root_prefix = ''; } ?>
        </main>
        <footer class="site-footer">
            <p>&copy; <?php echo date('Y'); ?> RiftMind. Real-time AI coaching for League of Legends.</p>
            <nav class="footer-nav">
                <a href="<?php echo htmlspecialchars($site_root_prefix); ?>index.php">Home</a>
                <a href="<?php echo htmlspecialchars($site_root_prefix); ?>about.php">About</a>
                <a href="<?php echo htmlspecialchars($site_root_prefix); ?>products.php">Services</a>
                <a href="<?php echo htmlspecialchars($site_root_prefix); ?>news.php">News</a>
                <a href="<?php echo htmlspecialchars($site_root_prefix); ?>contacts.php">Contacts</a>
                <a href="<?php echo htmlspecialchars($site_root_prefix); ?>secure.php">Secure</a>
            </nav>
        </footer>
    </div>
</body>
</html>
