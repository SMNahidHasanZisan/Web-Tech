<?php
require_once __DIR__ . '/../config/helpers.php';
require_once __DIR__ . '/../models/Category.php';
require_once __DIR__ . '/../models/Content.php';

$categoryId = (int)($_GET['id'] ?? 0);
$subId = (int)($_GET['sub'] ?? 0);
$ids = $subId > 0 ? [$subId] : Category::idsWithChildren($pdo, $categoryId);
$subcategories = Category::children($pdo, $categoryId);
$contents = $categoryId > 0 ? Content::byCategoryIds($pdo, $ids) : [];

include __DIR__ . '/../views/partials/header.php';
?>
<h1>Category Contents</h1>

<div class="tabs">
    <a href="category.php?id=<?php echo e($categoryId); ?>">All</a>
    <?php foreach ($subcategories as $sub): ?>
        <a href="category.php?id=<?php echo e($categoryId); ?>&sub=<?php echo e($sub['id']); ?>"><?php echo e($sub['name']); ?></a>
    <?php endforeach; ?>
</div>

<div class="grid">
    <?php foreach ($contents as $item): ?>
        <?php include __DIR__ . '/../views/content_card.php'; ?>
    <?php endforeach; ?>
</div>
<?php include __DIR__ . '/../views/partials/footer.php'; ?>

