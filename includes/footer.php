<?php
// This footer is used for public pages (index.php, post.php)
?>
    <!-- Footer -->
    <footer class="bg-dark text-white mt-5 py-4">
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <h5>My CMS</h5>
                    <p>A simple and powerful content management system built with PHP and MySQL.</p>
                </div>
                <div class="col-md-3">
                    <h5>Quick Links</h5>
                    <ul class="list-unstyled">
                        <li><a href="<?php echo BASE_URL; ?>" class="text-white-50 text-decoration-none">Home</a></li>
                        <li><a href="#" class="text-white-50 text-decoration-none">About</a></li>
                        <li><a href="#" class="text-white-50 text-decoration-none">Contact</a></li>
                    </ul>
                </div>
                <div class="col-md-3">
                    <h5>Admin</h5>
                    <ul class="list-unstyled">
                        <?php if(isLoggedIn()): ?>
                            <li><a href="admin/dashboard.php" class="text-white-50 text-decoration-none">Dashboard</a></li>
                            <li><a href="logout.php" class="text-white-50 text-decoration-none">Logout</a></li>
                        <?php else: ?>
                            <li><a href="login.php" class="text-white-50 text-decoration-none">Login</a></li>
                        <?php endif; ?>
                    </ul>
                </div>
            </div>
            <hr class="bg-light">
            <div class="row">
                <div class="col-md-12 text-center">
                    <p class="mb-0">&copy; <?php echo date('Y'); ?> My CMS. All rights reserved.</p>
                </div>
            </div>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Custom JS -->
    <script src="assets/js/script.js"></script>
    
    <?php
    // Show debug info if in development
    if(isset($_GET['debug']) && $_GET['debug'] == 1) {
        echo '<div class="container mt-3">';
        echo '<div class="card">';
        echo '<div class="card-header">Debug Info</div>';
        echo '<div class="card-body">';
        echo '<pre>';
        echo 'Session: '; print_r($_SESSION);
        echo 'Posts Count: ' . ($posts ?? 'N/A');
        echo '</pre>';
        echo '</div>';
        echo '</div>';
        echo '</div>';
    }
    ?>
</body>
</html>
