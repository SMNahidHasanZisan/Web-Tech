<?php
require_once __DIR__ . '/../../config/helpers.php';
?>
<!DOCTYPE html>
<html>
<head>
    <title>ISP Media Content</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <link rel="stylesheet" href="assets/css/style.css?v=4">
</head>
<body>
<nav class="navbar">
    <a class="brand" href="index.php">ISP Media</a>
    <div class="navlinks">
        <a href="index.php">Home</a>
        <?php if (is_logged_in()): ?>
            <a href="profile.php">Profile</a>
            <?php if (current_user_role() === 'admin'): ?>
                <span class="role-label">Admin</span>
            <?php endif; ?>
            <?php if (current_user_role() === 'moderator'): ?>
                <span class="role-label">Moderator</span>
            <?php endif; ?>
            <a href="logout.php">Logout</a>
        <?php endif; ?>
    </div>
</nav>

<main class="container">
<?php if ($message = flash('success')): ?>
    <div class="message success"><?php echo e($message); ?></div>
<?php endif; ?>
<?php if ($message = flash('error')): ?>
    <div class="message error"><?php echo e($message); ?></div>
<?php endif; ?>
