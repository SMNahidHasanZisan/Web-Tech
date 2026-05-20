<?php
require_once __DIR__ . '/../config/helpers.php';
require_once __DIR__ . '/../models/ContentRequest.php';
require_login();

if (!in_array(current_user_role(), ['admin', 'moderator'], true)) {
    redirect('index.php');
}

$requests = ContentRequest::all($pdo);
include __DIR__ . '/../views/partials/header.php';
?>
<h1>Content Requests</h1>
<section class="panel">
    <table>
        <tr><th>Title</th><th>Category</th><th>Message</th><th>Status</th><th>Action</th></tr>
        <?php foreach ($requests as $request): ?>
            <tr>
                <td><?php echo e($request['content_title']); ?></td>
                <td><?php echo e($request['category_requested']); ?></td>
                <td><?php echo e($request['message']); ?></td>
                <td id="status-<?php echo e($request['id']); ?>"><?php echo e($request['status']); ?></td>
                <td>
                    <button type="button" onclick="updateRequestStatus(<?php echo e($request['id']); ?>, 'fulfilled')">Fulfilled</button>
                    <button type="button" onclick="updateRequestStatus(<?php echo e($request['id']); ?>, 'rejected')">Rejected</button>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
</section>
<script src="assets/js/main.js?v=2"></script>
<?php include __DIR__ . '/../views/partials/footer.php'; ?>
