<?php
require_once __DIR__ . '/../config/helpers.php';
require_once __DIR__ . '/../models/Content.php';

$id = (int)($_GET['id'] ?? 0);
$content = Content::find($pdo, $id);

if (!$content) {
    die('File not found.');
}

Content::incrementDownload($pdo, $id);
redirect($content['file_path']);

