<?php
require_once __DIR__ . '/../config/helpers.php';
require_once __DIR__ . '/../controllers/ContentController.php';
require_once __DIR__ . '/../models/Category.php';
require_once __DIR__ . '/../models/Content.php';
require_role('moderator');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['delete_id'])) {
        $_POST['id'] = $_POST['delete_id'];
        handle_content_delete($pdo, 'moderator_contents.php');
    } else {
        handle_content_create($pdo, 'moderator_contents.php');
    }
}

$categories = Category::all($pdo);
$search = trim($_GET['q'] ?? '');
$filterCategory = (int)($_GET['category_id'] ?? 0);
$contents = ($search !== '' || $filterCategory > 0)
    ? Content::search($pdo, $search, $filterCategory ?: null)
    : Content::all($pdo);
include __DIR__ . '/../views/partials/header.php';
?>
<h1>Moderator Contents</h1>
<section class="form-box">
    <h2>Add New Content</h2>
    <form method="post" enctype="multipart/form-data" onsubmit="return validateContentForm()">
        <?php echo csrf_field(); ?>
        <label>Title</label>
        <input type="text" name="title" id="content_title">
        <label>Description</label>
        <textarea name="description" id="content_description"></textarea>
        <label>Category</label>
        <select name="category_id" id="content_category">
            <option value="">Select Category</option>
            <?php foreach ($categories as $cat): ?>
                <option value="<?php echo e($cat['id']); ?>"><?php echo e($cat['name']); ?></option>
            <?php endforeach; ?>
        </select>
        <label>File</label>
        <input type="file" name="content_file" id="content_file">
        <button type="submit">Upload</button>
    </form>
</section>

<section class="panel">
    <h2>All Uploaded Contents</h2>
    <form method="get" class="form-row">
        <input type="text" name="q" placeholder="Search title or description" value="<?php echo e($search); ?>">
        <select name="category_id">
            <option value="">All Categories</option>
            <?php foreach ($categories as $cat): ?>
                <option value="<?php echo e($cat['id']); ?>" <?php echo $filterCategory === (int)$cat['id'] ? 'selected' : ''; ?>><?php echo e($cat['name']); ?></option>
            <?php endforeach; ?>
        </select>
        <button type="submit">Filter</button>
    </form>
    <table>
        <tr><th>Title</th><th>Category</th><th>Uploader</th><th>Action</th></tr>
        <?php foreach ($contents as $item): ?>
            <tr>
                <td><?php echo e($item['title']); ?></td>
                <td><?php echo e($item['category_name']); ?></td>
                <td><?php echo e($item['uploader_name']); ?></td>
                <td>
                    <form method="post" onsubmit="return confirm('Delete this content?')">
                        <?php echo csrf_field(); ?>
                        <input type="hidden" name="delete_id" value="<?php echo e($item['id']); ?>">
                        <button type="submit" class="danger">Delete</button>
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
</section>
<script src="assets/js/validation.js"></script>
<?php include __DIR__ . '/../views/partials/footer.php'; ?>
