<?php
require_once __DIR__ . '/../config/helpers.php';
require_once __DIR__ . '/../models/ContentRequest.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid request']);
    exit;
}

$csrf = $_POST['csrf_token'] ?? '';
if (empty($_SESSION['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $csrf)) {
    echo json_encode(['success' => false, 'message' => 'Invalid form request']);
    exit;
}

$title = trim($_POST['content_title'] ?? '');
$category = trim($_POST['category_requested'] ?? '');
$message = trim($_POST['message'] ?? '');

if ($title === '' || $category === '') {
    echo json_encode(['success' => false, 'message' => 'Content title and category are required']);
    exit;
}

$ip = $_SERVER['REMOTE_ADDR'] ?? session_id();
ContentRequest::create($pdo, $ip, $title, $category, $message);

echo json_encode(['success' => true, 'message' => 'Request submitted successfully']);
