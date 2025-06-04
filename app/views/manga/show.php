<?php ob_start(); ?>

    <div class="row">
        <div class="col-md-4">
            <?php if ($manga['cover_image']): ?>
                <img src="/public/uploads/manga/covers/<?= $manga['cover_image'] ?>"
                     class="img-fluid rounded" alt="<?= htmlspecialchars($manga['title']) ?>">
            <?php endif; ?>
        </div>
        <div class="col-md-8">
            <h1><?= htmlspecialchars($manga['title']) ?></h1>

            <div class="mb-3">
                <span class="badge bg-primary"><?= ucfirst($manga['status']) ?></span>
                <span class="badge bg-secondary">Переглядів: <?= $manga['views'] ?></span>
                <?php if ($manga['rating'] > 0): ?>
                    <span class="badge bg-warning">Рейтинг: <?= $manga['rating'] ?>/5</span>
                <?php endif; ?>
            </div>

            <p><strong>Автор:</strong> <?= htmlspecialchars($manga['author']) ?></p>
            <p><strong>Художник:</strong> <?= htmlspecialchars($manga['artist']) ?></p>

            <div class="mb-4">
                <h5>Опис</h5>
                <p><?= nl2br(htmlspecialchars($manga['description'])) ?></p>
            </div>

            <?php if (Auth::check() && (Auth::hasRole('translator') || Auth::hasRole('admin'))): ?>
                <a href="/manga/<?= $manga['id'] ?>/upload" class="btn btn-success">Завантажити розділ</a>
            <?php endif; ?>
        </div>
    </div>

    <div class="mt-5">
        <h3>Розділи</h3>
        <?php if (empty($chapters)): ?>
            <p>Розділи ще не додані.</p>
        <?php else: ?>
            <div class="list-group">
                <?php foreach ($chapters as $chapter): ?>
                    <a href="/manga/<?= $manga['slug'] ?>/chapter/<?= $chapter['chapter_number'] ?>"
                       class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="mb-1">Розділ <?= $chapter['chapter_number'] ?></h6>
                            <?php if ($chapter['title']): ?>
                                <p class="mb-1"><?= htmlspecialchars($chapter['title']) ?></p>
                            <?php endif; ?>
                            <small>Сторінок: <?= $chapter['pages_count'] ?> | Переглядів: <?= $chapter['views'] ?></small>
                        </div>
                        <small class="text-muted"><?= date('d.m.Y', strtotime($chapter['created_at'])) ?></small>
                    </a>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>

<?php $content = ob_get_clean(); ?>
<?php include __DIR__ . '/../layouts/main.php'; ?>