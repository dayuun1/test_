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
            <?php if (!empty($genres)): ?>
                <p><strong>Жанри:</strong>
                    <?php foreach ($genres as $genre): ?>
                        <a href="/genres/<?= $genre['id'] ?>" class="badge bg-info text-decoration-none"><?= htmlspecialchars($genre['name']) ?></a>
                    <?php endforeach; ?>
                </p>
            <?php endif; ?>
            <div class="mb-4">
                <h5>Опис</h5>
                <p><?= nl2br(htmlspecialchars($manga['description'])) ?></p>
            </div>

        </div>
        <?php if (!empty($characters)): ?>
            <div class="mt-5">
                <h3>Персонажі</h3>
                <div class="row">
                    <?php foreach ($characters as $character): ?>
                        <div class="col-md-2 mb-2">
                            <div class="card h-100">
                                <?php if (!empty($character['image'])): ?>
                                    <img src="/public/uploads/characters/<?= htmlspecialchars($character['image']) ?>" style="height: 200px; object-fit: cover;" class="card-img-top" alt="<?= htmlspecialchars($character['name']) ?>">
                                <?php endif; ?>
                                <div class="card-body">
                                    <h5 class="card-title"><?= htmlspecialchars($character['name']) ?></h5>
                                    <?php if (!empty($character['description'])): ?>
                                        <p class="card-text"><?= htmlspecialchars(mb_strimwidth($character['description'], 0, 20, '...')) ?></p>
                                    <?php endif; ?>
                                    <a href="/characters/<?= $character['id'] ?>" class="btn btn-sm btn-outline-primary">Детальніше</a>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endif; ?>
    </div>
<?php
$teamModel = new Team();
if (Auth::check() && (Auth::hasRole('translator') && $teamModel->userHasAccessToManga(Auth::user()['id'], $manga['id']) || Auth::hasRole('admin'))): ?>
    <a href="/manga/<?= $manga['id'] ?>/upload" class="btn btn-success">Завантажити розділ</a>
<?php endif; ?>

    <div class="mt-5">
        <h3>Розділи</h3>
        <?php if (empty($chapters)): ?>
            <p>Розділи ще не додані.</p>
        <?php else: ?>
            <div class="list-group">
                <?php foreach ($chapters as $chapter): ?>
                    <a href="/manga/<?= $manga['id'] ?>/chapter/<?= $chapter['chapter_number'] ?>"
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

            <?php if ($totalPages > 1): ?>
                <nav class="mt-3">
                    <ul class="pagination">
                        <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                            <li class="page-item <?= $i == $currentPage ? 'active' : '' ?>">
                                <a class="page-link" href="?page=<?= $i ?>"><?= $i ?></a>
                            </li>
                        <?php endfor; ?>
                    </ul>
                </nav>
            <?php endif; ?>

        <?php endif; ?>
    </div>
<?php $content = ob_get_clean(); ?>
<?php include __DIR__ . '/../layouts/main.php'; ?>