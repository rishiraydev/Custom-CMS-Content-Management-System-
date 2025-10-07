<?php
$page_title = "Add New Post";
require_once 'header.php';

if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = sanitize($_POST['title']);
    $content = $_POST['content'];
    $meta_title = sanitize($_POST['meta_title']);
    $meta_description = sanitize($_POST['meta_description']);
    $status = sanitize($_POST['status']);
    $slug = generateSlug($title);
    
    // Handle featured image upload
    $featured_image = null;
    if(isset($_FILES['featured_image']) && $_FILES['featured_image']['error'] == 0) {
        $featured_image = uploadImage($_FILES['featured_image']);
    }
    
    // Check if slug already exists
    $stmt = $pdo->prepare("SELECT id FROM posts WHERE slug = ?");
    $stmt->execute([$slug]);
    if($stmt->fetch()) {
        $slug = $slug . '-' . uniqid();
    }
    
    $stmt = $pdo->prepare("INSERT INTO posts (title, slug, content, meta_title, meta_description, featured_image, author_id, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    
    if($stmt->execute([$title, $slug, $content, $meta_title, $meta_description, $featured_image, $_SESSION['user_id'], $status])) {
        $_SESSION['message'] = "Post created successfully!";
        redirect('posts.php');
    } else {
        $error = "Error creating post!";
    }
}
?>

<div class="card">
    <div class="card-body">
        <h4 class="card-title">Add New Post</h4>
        
        <?php if(isset($error)): ?>
            <div class="alert alert-danger"><?php echo $error; ?></div>
        <?php endif; ?>
        
        <form method="POST" enctype="multipart/form-data">
            <div class="mb-3">
                <label for="title" class="form-label">Title</label>
                <input type="text" class="form-control" id="title" name="title" required>
            </div>
            
            <div class="mb-3">
                <label for="content" class="form-label">Content</label>
                <textarea class="form-control" id="content" name="content" rows="15"></textarea>
            </div>
            
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="meta_title" class="form-label">Meta Title</label>
                        <input type="text" class="form-control" id="meta_title" name="meta_title">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="featured_image" class="form-label">Featured Image</label>
                        <input type="file" class="form-control" id="featured_image" name="featured_image" accept="image/*">
                    </div>
                </div>
            </div>
            
            <div class="mb-3">
                <label for="meta_description" class="form-label">Meta Description</label>
                <textarea class="form-control" id="meta_description" name="meta_description" rows="3"></textarea>
            </div>
            
            <div class="mb-3">
                <label for="status" class="form-label">Status</label>
                <select class="form-control" id="status" name="status">
                    <option value="published">Published</option>
                    <option value="unpublished">Unpublished</option>
                </select>
            </div>
            
            <button type="submit" class="btn btn-primary">Publish Post</button>
            <a href="posts.php" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
</div>

<script>
tinymce.init({
    selector: '#content',
    plugins: 'advlist autolink lists link image charmap preview anchor',
    toolbar: 'undo redo | formatselect | bold italic | alignleft aligncenter alignright | bullist numlist outdent indent | link image',
    height: 400
});
</script>

<?php require_once '../includes/footer.php'; ?>
