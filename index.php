<?php
require_once 'includes/config.php';
require_once 'includes/functions.php';

// Pagination
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$posts_per_page = 5;
$offset = ($page - 1) * $posts_per_page;

// Search functionality
$search = isset($_GET['search']) ? sanitize($_GET['search']) : '';

if($search) {
    $query = "SELECT * FROM posts WHERE status = 'published' AND (title LIKE ? OR content LIKE ?) 
             ORDER BY created_at DESC LIMIT ? OFFSET ?";
    $stmt = $pdo->prepare($query);
    $stmt->execute(["%$search%", "%$search%", $posts_per_page, $offset]);
    
    // Total count for pagination
    $count_query = "SELECT COUNT(*) FROM posts WHERE status = 'published' AND (title LIKE ? OR content LIKE ?)";
    $count_stmt = $pdo->prepare($count_query);
    $count_stmt->execute(["%$search%", "%$search%"]);
} else {
    $query = "SELECT * FROM posts WHERE status = 'published' ORDER BY created_at DESC LIMIT ? OFFSET ?";
    $stmt = $pdo->prepare($query);
    $stmt->execute([$posts_per_page, $offset]);
    
    // Total count for pagination
    $count_stmt = $pdo->prepare("SELECT COUNT(*) FROM posts WHERE status = 'published'");
    $count_stmt->execute();
}

$posts = $stmt->fetchAll(PDO::FETCH_ASSOC);
$total_posts = $count_stmt->fetchColumn();
$total_pages = ceil($total_posts / $posts_per_page);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My CMS Blog</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="assets/css/style.css" rel="stylesheet">
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="index.php">My CMS</a>
            <div class="navbar-nav ms-auto">
                <?php if(isLoggedIn()): ?>
                    <a class="nav-link" href="admin/dashboard.php">Dashboard</a>
                <?php else: ?>
                    <a class="nav-link" href="login.php">Login</a>
                <?php endif; ?>
            </div>
        </div>
    </nav>

    <!-- Header -->
    <div class="bg-primary text-white py-5">
        <div class="container">
            <h1 class="display-4">Welcome to Our Blog</h1>
            <p class="lead">Discover amazing content and stay updated</p>
        </div>
    </div>

    <!-- Main Content -->
    <div class="container mt-4">
        <!-- Search Bar -->
        <div class="row mb-4">
            <div class="col-md-6">
                <form method="GET" class="d-flex">
                    <input type="text" name="search" class="form-control me-2" placeholder="Search posts..." value="<?php echo $search; ?>">
                    <button type="submit" class="btn btn-outline-primary">Search</button>
                </form>
            </div>
        </div>

        <!-- Posts -->
        <div class="row">
            <?php if($posts): ?>
                <?php foreach($posts as $post): ?>
                    <div class="col-md-6 mb-4">
                        <div class="card h-100">
                            <?php if($post['featured_image']): ?>
                                <img src="uploads/<?php echo $post['featured_image']; ?>" class="card-img-top" alt="<?php echo $post['title']; ?>" style="height: 200px; object-fit: cover;">
                            <?php endif; ?>
                            <div class="card-body">
                                <h5 class="card-title"><?php echo $post['title']; ?></h5>
                                <p class="card-text">
                                    <?php 
                                    $content = strip_tags($post['content']);
                                    echo strlen($content) > 150 ? substr($content, 0, 150) . '...' : $content;
                                    ?>
                                </p>
                                <a href="post.php?slug=<?php echo $post['slug']; ?>" class="btn btn-primary">Read More</a>
                            </div>
                            <div class="card-footer text-muted">
                                Posted on <?php echo date('F j, Y', strtotime($post['created_at'])); ?>
                                | Views: <?php echo $post['views']; ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="col-12">
                    <div class="alert alert-info">No posts found.</div>
                </div>
            <?php endif; ?>
        </div>

        <!-- Pagination -->
        <?php if($total_pages > 1): ?>
            <nav aria-label="Page navigation">
                <ul class="pagination justify-content-center">
                    <?php for($i = 1; $i <= $total_pages; $i++): ?>
                        <li class="page-item <?php echo $i == $page ? 'active' : ''; ?>">
                            <a class="page-link" href="index.php?page=<?php echo $i; ?><?php echo $search ? '&search=' . urlencode($search) : ''; ?>">
                                <?php echo $i; ?>
                            </a>
                        </li>
                    <?php endfor; ?>
                </ul>
            </nav>
        <?php endif; ?>
    </div>

    <!-- Footer -->
    <footer class="bg-dark text-white mt-5 py-4">
        <div class="container text-center">
            <p>&copy; <?php echo date('Y'); ?> My CMS. All rights reserved.</p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
