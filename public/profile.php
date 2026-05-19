<?php
require_once __DIR__ . '/../config/helpers.php';
require_once __DIR__ . '/../controllers/ProfileController.php';
require_once __DIR__ . '/../models/User.php';
require_login();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    handle_profile_update($pdo);
}

$user = User::findById($pdo, current_user_id());
include __DIR__ . '/../views/partials/header.php';
?>
<section class="form-box">
    <h1>My Profile</h1>
    <?php if (!empty($user['profile_picture'])): ?>
        <img class="profile-img" src="<?php echo e($user['profile_picture']); ?>" alt="Profile picture">
    <?php endif; ?>

    <form method="post" enctype="multipart/form-data" onsubmit="return validateProfileForm()">
        <?php echo csrf_field(); ?>
        <label>Name</label>
        <input type="text" name="name" id="profile_name" value="<?php echo e($user['name']); ?>">

        <label>Email</label>
        <input type="text" name="email" id="profile_email" value="<?php echo e($user['email']); ?>">

        <label>Profile Picture</label>
        <input type="file" name="profile_picture">

        <h2>Change Password</h2>
        <label>Current Password</label>
        <input type="password" name="current_password">

        <label>New Password</label>
        <input type="password" name="new_password" id="new_password">

        <label>Confirm New Password</label>
        <input type="password" name="confirm_password" id="profile_confirm_password">

        <button type="submit">Update Profile</button>
    </form>
</section>
<script src="assets/js/validation.js"></script>
<?php include __DIR__ . '/../views/partials/footer.php'; ?>
