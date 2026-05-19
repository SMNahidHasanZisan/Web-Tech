<?php
require_once __DIR__ . '/../config/helpers.php';
require_once __DIR__ . '/../models/User.php';
require_role('admin');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();
    if (isset($_POST['delete_id'])) {
        User::delete($pdo, (int)$_POST['delete_id']);
        flash('success', 'Moderator deleted.');
        redirect('admin_moderators.php');
    }

    if (isset($_POST['edit_id'])) {
        $id = (int)$_POST['edit_id'];
        $name = trim($_POST['name'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';

        if ($id <= 0 || $name === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            flash('error', 'Enter valid moderator data.');
            redirect('admin_moderators.php');
        }

        $existing = User::findByEmail($pdo, $email);
        if ($existing && (int)$existing['id'] !== $id) {
            flash('error', 'Email already exists.');
            redirect('admin_moderators.php');
        }

        if ($password !== '' && strlen($password) < 8) {
            flash('error', 'Password must be at least 8 characters.');
            redirect('admin_moderators.php');
        }

        User::updateModerator($pdo, $id, $name, $email, $password);
        flash('success', 'Moderator updated.');
        redirect('admin_moderators.php');
    }

    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($name === '' || $email === '' || strlen($password) < 8 || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        flash('error', 'Enter valid moderator data.');
        redirect('admin_moderators.php');
    }

    if (User::findByEmail($pdo, $email)) {
        flash('error', 'Email already exists.');
        redirect('admin_moderators.php');
    }

    User::create($pdo, $name, $email, $password, 'moderator');
    flash('success', 'Moderator added.');
    redirect('admin_moderators.php');
}

$moderators = User::moderators($pdo);
include __DIR__ . '/../views/partials/header.php';
?>
<h1>Manage Moderators</h1>
<section class="form-box">
    <h2>Add Moderator</h2>
    <form method="post" onsubmit="return validateModeratorForm(this, true)">
        <?php echo csrf_field(); ?>
        <label>Name</label>
        <input type="text" name="name">
        <label>Email</label>
        <input type="text" name="email">
        <label>Password</label>
        <input type="password" name="password">
        <button type="submit">Add Moderator</button>
    </form>
</section>

<section class="panel">
    <h2>Moderator List</h2>
    <table>
        <tr><th>Name</th><th>Email</th><th>Edit</th><th>Delete</th></tr>
        <?php foreach ($moderators as $mod): ?>
            <tr>
                <td><?php echo e($mod['name']); ?></td>
                <td><?php echo e($mod['email']); ?></td>
                <td>
                    <form method="post" onsubmit="return validateModeratorForm(this, false)">
                        <?php echo csrf_field(); ?>
                        <input type="hidden" name="edit_id" value="<?php echo e($mod['id']); ?>">
                        <input type="text" name="name" value="<?php echo e($mod['name']); ?>">
                        <input type="text" name="email" value="<?php echo e($mod['email']); ?>">
                        <input type="password" name="password" placeholder="New password optional">
                        <button type="submit">Save</button>
                    </form>
                </td>
                <td>
                    <form method="post" onsubmit="return confirm('Delete this moderator?')">
                        <?php echo csrf_field(); ?>
                        <input type="hidden" name="delete_id" value="<?php echo e($mod['id']); ?>">
                        <button type="submit" class="danger">Delete</button>
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
</section>
<script src="assets/js/validation.js"></script>
<?php include __DIR__ . '/../views/partials/footer.php'; ?>
