<?php
require_once __DIR__ . '/../config/helpers.php';
require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../models/Category.php';
require_once __DIR__ . '/../models/Content.php';

$pdo->exec("INSERT IGNORE INTO categories (id, name, parent_id) VALUES
(1, 'Movies', NULL),
(2, 'Software', NULL),
(3, 'TV Series', NULL),
(4, 'Games', NULL),
(5, 'English Movies', 1),
(6, 'Action Movies', 1),
(7, 'Utility Software', 2),
(8, 'Windows Games', 4)");

if (!User::findByEmail($pdo, 'admin@example.com')) {
    User::create($pdo, 'Demo Admin', 'admin@example.com', 'admin12345', 'admin');
}

if (!User::findByEmail($pdo, 'moderator@example.com')) {
    User::create($pdo, 'Demo Moderator', 'moderator@example.com', 'moderator12345', 'moderator');
}

$admin = User::findByEmail($pdo, 'admin@example.com');
$count = $pdo->query('SELECT COUNT(*) FROM contents')->fetchColumn();
if ((int)$count === 0 && $admin) {
    $stmt = $pdo->prepare('INSERT INTO contents (title, description, file_path, category_id, uploader_id, download_count) VALUES (?, ?, ?, ?, ?, ?)');
    $stmt->execute(['Sample Movie', 'A demo movie content for browsing.', 'uploads/contents/sample.txt', 5, $admin['id'], 10]);
    $stmt->execute(['Sample Software', 'A useful software file example.', 'uploads/contents/sample.txt', 7, $admin['id'], 6]);
}

if (!is_file(__DIR__ . '/uploads/contents/sample.txt')) {
    file_put_contents(__DIR__ . '/uploads/contents/sample.txt', 'Sample download file');
}

echo '<h2>Setup complete</h2>';
echo '<p>Admin: admin@example.com / admin12345</p>';
echo '<p>Moderator: moderator@example.com / moderator12345</p>';
echo '<a href="index.php">Go to Home</a>';

