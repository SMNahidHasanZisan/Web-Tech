<?php
require_once __DIR__ . '/../config/helpers.php';
require_once __DIR__ . '/../controllers/ContentController.php';
require_once __DIR__ . '/../models/Category.php';
require_once __DIR__ . '/../models/Content.php';
require_role('admin');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['delete_id'])) {
        $_POST['id'] = $_POST['delete_id'];
        handle_content_delete($pdo, 'admin_contents.php');
    } elseif (isset($_POST['edit_id'])) {
        $_POST['id'] = $_POST['edit_id'];
        handle_content_update($pdo, 'admin_contents.php');
    } else {
        handle_content_create($pdo, 'admin_contents.php');
    }
}

$categories = Category::all($pdo);
$contents = Content::all($pdo);
include __DIR__ . '/../views/partials/header.php';
?>
<h1>Manage Contents</h1>
<section class="form-box">
    <h2>Upload Content</h2>
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
    <h2>All Contents</h2>
    <table>
        <tr><th>Title</th><th>Category</th><th>Uploader</th><th>Edit</th><th>Delete</th></tr>
        <?php foreach ($contents as $item): ?>
            <tr>
                <td><?php echo e($item['title']); ?></td>
                <td><?php echo e($item['category_name']); ?></td>
                <td><?php echo e($item['uploader_name']); ?></td>
                <td>
                    <form method="post" onsubmit="return validateContentEditForm(this)">
                        <?php echo csrf_field(); ?>
                        <input type="hidden" name="edit_id" value="<?php echo e($item['id']); ?>">
                        <input type="text" name="title" value="<?php echo e($item['title']); ?>">
                        <input type="text" name="description" value="<?php echo e($item['description']); ?>">
                        <select name="category_id">
                            <?php foreach ($categories as $cat): ?>
                                <option value="<?php echo e($cat['id']); ?>" <?php echo $cat['id'] == $item['category_id'] ? 'selected' : ''; ?>><?php echo e($cat['name']); ?></option>
                            <?php endforeach; ?>
                        </select>
                        <button type="submit">Save</button>
                    </form>
                </td>
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
