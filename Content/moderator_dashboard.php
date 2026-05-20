<?php
require_once __DIR__ . '/../config/helpers.php';
require_role('moderator');
include __DIR__ . '/../views/partials/header.php';
?>
<h1>Moderator Dashboard</h1>
<div class="panel">
    <a class="button" href="moderator_contents.php">Manage Contents</a>
    <a class="button" href="requests.php">View Requests</a>
</div>
<?php include __DIR__ . '/../views/partials/footer.php'; ?>

