<?php
require_once __DIR__ . '/../config/helpers.php';
require_once __DIR__ . '/../controllers/AuthController.php';
require_once __DIR__ . '/../models/Category.php';
require_once __DIR__ . '/../models/Content.php';
check_remember_login($pdo);

$categories = Category::topLevel($pdo);
$allCategories = Category::all($pdo);
$subcategories = array_filter($allCategories, function ($cat) {
    return !empty($cat['parent_id']);
});
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
    <h2>Search Content</h2>
    <div class="form-row">
        <input type="text" id="searchText" placeholder="Search by title or description">
        <select id="searchCategory" onchange="updateSubcategoryFilter()">
            <option value="">All Categories</option>
            <?php foreach ($categories as $cat): ?>
                <option value="<?php echo e($cat['id']); ?>"><?php echo e($cat['name']); ?></option>
            <?php endforeach; ?>
        </select>
        <select id="searchSubcategory">
            <option value="">All Subcategories</option>
            <?php foreach ($subcategories as $cat): ?>
                <option value="<?php echo e($cat['id']); ?>" data-parent="<?php echo e($cat['parent_id']); ?>"><?php echo e($cat['name']); ?></option>
            <?php endforeach; ?>
        </select>
        <button type="button" onclick="searchContents()">Search</button>
    </div>
    <div id="searchResults" class="grid"></div>
</section>

<section class="panel">
    <h2>Highlighted Contents</h2>
    <div class="grid">
        <?php foreach ($highlighted as $item): ?>
            <?php include __DIR__ . '/../views/content_card.php'; ?>
        <?php endforeach; ?>
    </div>
</section>

<?php if (!is_logged_in()): ?>
    <section class="panel">
        <h2>Request New Content</h2>
        <form id="requestForm">
            <?php echo csrf_field(); ?>
            <label>Content Title</label>
            <input type="text" name="content_title" required>

            <label>Category</label>
            <select name="category_requested" required>
                <option value="">Select Category</option>
                <?php foreach ($categories as $cat): ?>
                    <option value="<?php echo e($cat['name']); ?>"><?php echo e($cat['name']); ?></option>
                <?php endforeach; ?>
            </select>

            <label>Message</label>
            <textarea name="message"></textarea>

            <button type="submit">Send Request</button>
        </form>
        <p id="requestMessage"></p>
    </section>
<?php endif; ?>

<script src="assets/js/main.js?v=2"></script>
<?php include __DIR__ . '/../views/partials/footer.php'; ?>
