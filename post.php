<?php
require_once 'includes/config.php';
require_once 'includes/functions.php';

if(!isset($_GET['slug'])) {
    redirect('index.php');
}

$slug = sanitize($_GET['slug']);

// Get post
$stmt = $pdo->prepare("SELECT p.*, u.name as author_name FROM posts p 
                      JOIN users u ON p.author_id = u.id 
                      WHERE p.slug = ? AND p.status = 'published'");
$stmt->execute([$slug]);
$post = $stmt->fetch(PDO::FETCH_ASSOC);

if(!$post) {
    header("HTTP/1.0 404 Not Found");
    die("Post not found");
}

// Update view count
$stmt = $pdo->prepare("UPDATE posts SET views = views + 1 WHERE id = ?");
$stmt->execute([$post['id']]);

// Set meta tags
$meta_title = $post['meta_title'] ?: $post['title'];
$meta_description = $post['meta_description'] ?: substr(strip_tags($post['content']), 0, 160);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $meta_title; ?> - My CMS</title>
    <meta name="description" content="<?php echo $meta_description; ?>">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="index.php">My CMS</a>
            <a class="nav-link text-light" href="index.php">← Back to Blog</a>
        </div>
    </nav>

    <!-- Post Content -->
    <div class="container mt-4">
        <article>
            <header class="mb-4">
                <h1 class="display-4"><?php echo $post['title']; ?></h1>
                <div class="text-muted mb-3">
                    Posted by <?php echo $post['author_name']; ?> on 
                    <?php echo date('F j, Y', strtotime($post['created_at'])); ?> |
                    Views: <?php echo $post['views'] + 1; ?>
                </div>
                
                <?php if($post['featured_image']): ?>
                    <img src="uploads/<?php echo $post['featured_image']; ?>" class="img-fluid rounded mb-4" alt="<?php echo $post['title']; ?>">
                <?php endif; ?>
            </header>

            <div class="content">
                <?php echo $post['content']; ?>
            </div>
        </article>
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
