<div class="card">
    <span class="card-tag"><?php echo e($item['category_name'] ?? 'Media'); ?></span>
    <h3><?php echo e($item['title']); ?></h3>
    <p><?php echo e($item['description']); ?></p>
    <p><strong>Category:</strong> <?php echo e($item['category_name'] ?? ''); ?></p>
    <p><strong>Downloads:</strong> <?php echo e($item['download_count'] ?? 0); ?></p>
    <a class="button" href="download.php?id=<?php echo e($item['id']); ?>">Download</a>
</div>
