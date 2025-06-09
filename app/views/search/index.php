<?php ob_start(); ?>

<div class="row mb-4">
    <div class="col">
        <h1>Результати пошуку</h1>
        <?php if (!empty($query)): ?>
            <p class="text-muted">Пошук за запитом: <strong><?= htmlspecialchars($query) ?></strong></p>
        <?php endif; ?>
    </div>
</div>

<?php if (empty($results)): ?>
    <div class="alert alert-info">
        За запитом нічого не знайдено.
    </div>
<?php else: ?>
    <div class="row">
        <?php foreach ($results as $manga): ?>
            <div class="col-md-3 col-sm-6 mb-4">
                <div class="card h-100">
                    <?php if ($manga['cover_image']): ?>
                        <img src="/public/uploads/manga/covers/<?= $manga['cover_image'] ?>"
                             class="card-img-top"
                             alt="<?= htmlspecialchars($manga['title']) ?>"
                             style="height: 300px; object-fit: cover;">
                    <?php endif; ?>
                    <div class="card-body d-flex flex-column">
                        <h5 class="card-title"><?= htmlspecialchars($manga['title']) ?></h5>
                        <p class="card-text flex-grow-1">
                            <?= htmlspecialchars(substr($manga['description'], 0, 100)) ?>...
                        </p>
                        <div class="mt-auto">
                            <a href="/manga/<?= $manga['id'] ?>" class="btn btn-primary btn-sm float-end">Читати</a>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<?php $content = ob_get_clean(); ?>
<?php include __DIR__ . '/../layouts/main.php'; ?>
