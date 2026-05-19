<?php
require_once __DIR__ . '/../config/helpers.php';
require_once __DIR__ . '/../models/ContentRequest.php';

header('Content-Type: application/json');

if (!is_logged_in() || !in_array(current_user_role(), ['admin', 'moderator'], true)) {
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit;
}

$csrf = $_POST['csrf_token'] ?? '';
if (empty($_SESSION['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $csrf)) {
    echo json_encode(['success' => false, 'message' => 'Invalid form request']);
    exit;
}

$id = (int)($_POST['id'] ?? 0);
$status = $_POST['status'] ?? '';

if ($id <= 0 || !in_array($status, ['pending', 'fulfilled', 'rejected'], true)) {
    echo json_encode(['success' => false, 'message' => 'Invalid data']);
    exit;
}

ContentRequest::updateStatus($pdo, $id, $status);
echo json_encode(['success' => true, 'status' => $status]);
