<?php
require_once __DIR__ . '/../config/helpers.php';
require_once __DIR__ . '/../controllers/AuthController.php';
require_once __DIR__ . '/../models/Category.php';
require_once __DIR__ . '/../models/Content.php';
check_remember_login($pdo);

$categories = Category::topLevel($pdo);
$highlighted = Content::highlighted($pdo);

include __DIR__ . '/../views/partials/header.php';
?>
<section class="hero">
    <div>
        <h1>ISP Media Content</h1>
        <p class="lead">Browse movies, software, TV series, games and other media contents.</p>
    </div>
    <div class="hero-badge">Movies • Software • TV Series • Games</div>
</section>

<section class="panel">
    <h2>Categories</h2>
    <div class="tabs">
        <?php foreach ($categories as $cat): ?>
            <a href="category.php?id=<?php echo e($cat['id']); ?>"><?php echo e($cat['name']); ?></a>
        <?php endforeach; ?>
    </div>
</section>

<section class="panel">
    <h2>Highlighted Contents</h2>
    <div class="grid">
        <?php foreach ($highlighted as $item): ?>
            <?php include __DIR__ . '/../views/content_card.php'; ?>
        <?php endforeach; ?>
    </div>
</section>

<?php include __DIR__ . '/../views/partials/footer.php'; ?>
