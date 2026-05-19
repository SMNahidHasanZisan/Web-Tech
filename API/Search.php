<?php
require_once __DIR__ . '/../config/helpers.php';
require_once __DIR__ . '/../models/Content.php';
require_once __DIR__ . '/../models/Category.php';

header('Content-Type: application/json');

$q = trim($_GET['q'] ?? '');
$categoryId = !empty($_GET['category_id']) ? (int)$_GET['category_id'] : null;

if ($categoryId) {
    $ids = Category::idsWithChildren($pdo, $categoryId);
    $results = $q === '' ? Content::byCategoryIds($pdo, $ids) : [];
    if ($q !== '') {
        foreach ($ids as $id) {
            $results = array_merge($results, Content::search($pdo, $q, $id));
        }
    }
} else {
    $results = Content::search($pdo, $q);
}
echo json_encode(['success' => true, 'data' => $results]);
