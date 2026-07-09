<?php
require_once __DIR__ . '/../config/helpers.php';
require_once __DIR__ . '/../controllers/AuthController.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    handle_login($pdo);
}

include __DIR__ . '/../views/partials/header.php';
?>
<section class="form-box">
    <h1>Login</h1>
    <form method="post">
        <?php echo csrf_field(); ?>
        <label>Email</label>
        <input type="text" name="email">

        <label>Password</label>
        <input type="password" name="password">

        <label class="check">
            <input type="checkbox" name="remember" value="1"> Remember Me
        </label>

        <button type="submit">Login</button>
    </form>
</section>
<?php include __DIR__ . '/../views/partials/footer.php'; ?>
