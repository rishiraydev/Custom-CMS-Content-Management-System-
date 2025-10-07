<?php
$page_title = "Manage Posts";
require_once 'header.php';

// Handle post deletion
if(isset($_GET['delete_id'])) {
    $delete_id = (int)$_GET['delete_id'];
    
    // Check if user owns the post or is admin
    $stmt = $pdo->prepare("SELECT author_id FROM posts WHERE id = ?");
    $stmt->execute([$delete_id]);
    $post = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if($post && (isAdmin() || $post['author_id'] == $_SESSION['user_id'])) {
        // Delete featured image if exists
        $stmt = $pdo->prepare("SELECT featured_image FROM posts WHERE id = ?");
        $stmt->execute([$delete_id]);
        $post = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if($post['featured_image'] && file_exists(UPLOAD_PATH . $post['featured_image'])) {
            unlink(UPLOAD_PATH . $post['featured_image']);
        }
        
        $stmt = $pdo->prepare("DELETE FROM posts WHERE id = ?");
        $stmt->execute([$delete_id]);
        $_SESSION['message'] = "Post deleted successfully!";
    }
    
    redirect('posts.php');
}

// Fetch posts
if(isAdmin()) {
    $query = "SELECT p.*, u.name as author_name FROM posts p 
             JOIN users u ON p.author_id = u.id 
             ORDER BY p.created_at DESC";
    $stmt = $pdo->prepare($query);
} else {
    $query = "SELECT p.*, u.name as author_name FROM posts p 
             JOIN users u ON p.author_id = u.id 
             WHERE p.author_id = ? 
             ORDER BY p.created_at DESC";
    $stmt = $pdo->prepare($query);
    $stmt->execute([$_SESSION['user_id']]);
}
$stmt->execute();
$posts = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="d-flex justify-content-between mb-3">
    <h3>Posts</h3>
    <a href="add_post.php" class="btn btn-primary">Add New Post</a>
</div>

<?php if(isset($_SESSION['message'])): ?>
    <div class="alert alert-success"><?php echo $_SESSION['message']; unset($_SESSION['message']); ?></div>
<?php endif; ?>

<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Title</th>
                        <th>Author</th>
                        <th>Status</th>
                        <th>Views</th>
                        <th>Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($posts as $post): ?>
                    <tr>
                        <td><?php echo $post['title']; ?></td>
                        <td><?php echo $post['author_name']; ?></td>
                        <td>
                            <span class="badge bg-<?php echo $post['status'] == 'published' ? 'success' : 'secondary'; ?>">
                                <?php echo ucfirst($post['status']); ?>
                            </span>
                        </td>
                        <td><?php echo $post['views']; ?></td>
                        <td><?php echo date('M j, Y', strtotime($post['created_at'])); ?></td>
                        <td>
                            <a href="../post.php?slug=<?php echo $post['slug']; ?>" class="btn btn-sm btn-info" target="_blank">View</a>
                            <a href="edit_post.php?id=<?php echo $post['id']; ?>" class="btn btn-sm btn-warning">Edit</a>
                            <a href="posts.php?delete_id=<?php echo $post['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">Delete</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php require_once '../includes/footer.php'; ?>
