<?php ob_start(); ?>

    <h1>Жанр: <?= htmlspecialchars($genre['name']) ?></h1>
    <p><?= nl2br(htmlspecialchars($genre['description'])) ?></p>

    <hr>

    <h2>Манга в цьому жанрі</h2>
    <div class="row">
        <?php if (!empty($manga)): ?>
            <?php foreach ($manga as $item): ?>
                <div class="col-md-3 col-sm-6 mb-4">
                    <div class="card h-100">
                        <?php if ($item['cover_image']): ?>
                            <img src="/public/uploads/manga/covers/<?= $item['cover_image'] ?>" class="card-img-top" alt="<?= htmlspecialchars($item['title']) ?>" style="height: 300px; object-fit: cover;">
                        <?php endif; ?>
                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title"><?= htmlspecialchars($item['title']) ?></h5>
                            <p class="card-text flex-grow-1"><?= htmlspecialchars(substr($item['description'], 0, 100)) ?>...</p>
                            <div class="mt-auto">
                                <a href="/manga/<?= $item['slug'] ?>" class="btn btn-primary btn-sm float-end">Читати</a>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>У цьому жанрі поки що немає манги.</p>
        <?php endif; ?>
    </div>

<?php $content = ob_get_clean(); ?>
<?php include __DIR__ . '/../layouts/main.php'; ?>