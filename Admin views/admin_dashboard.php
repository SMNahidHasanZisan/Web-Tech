<?php
require_once __DIR__ . '/../config/helpers.php';
require_once __DIR__ . '/../models/ContentRequest.php';
require_role('admin');

$totalContents = $pdo->query('SELECT COUNT(*) FROM contents')->fetchColumn();
$totalCategories = $pdo->query('SELECT COUNT(*) FROM categories')->fetchColumn();
$totalModerators = $pdo->query("SELECT COUNT(*) FROM users WHERE role = 'moderator'")->fetchColumn();
$pendingRequests = ContentRequest::pendingCount($pdo);

include __DIR__ . '/../views/partials/header.php';
?>
<h1>Admin Dashboard</h1>
<div class="stats">
    <div><strong><?php echo e($totalContents); ?></strong><span>Contents</span></div>
    <div><strong><?php echo e($totalCategories); ?></strong><span>Categories</span></div>
    <div><strong><?php echo e($totalModerators); ?></strong><span>Moderators</span></div>
    <div><strong><?php echo e($pendingRequests); ?></strong><span>Pending Requests</span></div>
</div>

<div class="panel">
    <a class="button" href="admin_moderators.php">Manage Moderators</a>
    <a class="button" href="admin_contents.php">Manage Contents</a>
    <a class="button" href="requests.php">View Requests</a>
</div>
<?php include __DIR__ . '/../views/partials/footer.php'; ?>

