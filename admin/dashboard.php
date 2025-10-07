<?php
$page_title = "Dashboard";
require_once 'header.php';

// Get statistics
$stmt = $pdo->prepare("SELECT COUNT(*) as total_posts FROM posts");
$stmt->execute();
$total_posts = $stmt->fetch(PDO::FETCH_ASSOC)['total_posts'];

$stmt = $pdo->prepare("SELECT COUNT(*) as published_posts FROM posts WHERE status = 'published'");
$stmt->execute();
$published_posts = $stmt->fetch(PDO::FETCH_ASSOC)['published_posts'];

if(isAdmin()) {
    $stmt = $pdo->prepare("SELECT COUNT(*) as total_users FROM users");
    $stmt->execute();
    $total_users = $stmt->fetch(PDO::FETCH_ASSOC)['total_users'];
}
?>

<div class="row">
    <div class="col-md-3 mb-4">
        <div class="card text-white bg-primary">
            <div class="card-body">
                <h5 class="card-title">Total Posts</h5>
                <h2 class="card-text"><?php echo $total_posts; ?></h2>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-4">
        <div class="card text-white bg-success">
            <div class="card-body">
                <h5 class="card-title">Published Posts</h5>
                <h2 class="card-text"><?php echo $published_posts; ?></h2>
            </div>
        </div>
    </div>
    <?php if(isAdmin()): ?>
    <div class="col-md-3 mb-4">
        <div class="card text-white bg-warning">
            <div class="card-body">
                <h5 class="card-title">Total Users</h5>
                <h2 class="card-text"><?php echo $total_users; ?></h2>
            </div>
        </div>
    </div>
    <?php endif; ?>
</div>

<div class="row mt-4">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Recent Posts</h5>
                <?php
                $query = "SELECT p.*, u.name as author_name FROM posts p 
                         JOIN users u ON p.author_id = u.id 
                         ORDER BY p.created_at DESC LIMIT 5";
                $stmt = $pdo->prepare($query);
                $stmt->execute();
                $recent_posts = $stmt->fetchAll(PDO::FETCH_ASSOC);
                ?>
                
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Title</th>
                                <th>Author</th>
                                <th>Status</th>
                                <th>Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($recent_posts as $post): ?>
                            <tr>
                                <td><?php echo $post['title']; ?></td>
                                <td><?php echo $post['author_name']; ?></td>
                                <td>
                                    <span class="badge bg-<?php echo $post['status'] == 'published' ? 'success' : 'secondary'; ?>">
                                        <?php echo ucfirst($post['status']); ?>
                                    </span>
                                </td>
                                <td><?php echo date('M j, Y', strtotime($post['created_at'])); ?></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once '../includes/footer.php'; ?>
