<?php
require_once __DIR__ . '/../config/helpers.php';
require_once __DIR__ . '/../controllers/AuthController.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    handle_register($pdo);
}

include __DIR__ . '/../views/partials/header.php';
?>
<section class="form-box">
    <h1>Registration</h1>
    <form method="post" onsubmit="return validateRegisterForm()">
        <?php echo csrf_field(); ?>
        <label>Name</label>
        <input type="text" name="name" id="name">

        <label>Email</label>
        <input type="text" name="email" id="email">

        <label>Password</label>
        <input type="password" name="password" id="password">

        <label>Confirm Password</label>
        <input type="password" name="confirm_password" id="confirm_password">

        <label>Role</label>
        <select name="role" id="role">
            <option value="">Select Role</option>
            <option value="admin">Admin</option>
            <option value="moderator">Moderator</option>
        </select>

        <button type="submit">Register</button>
    </form>
</section>
<script src="assets/js/validation.js"></script>
<?php include __DIR__ . '/../views/partials/footer.php'; ?>
