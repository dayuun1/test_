<?php ob_start(); ?>

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Новини</h1>
        <?php if (Auth::hasRole('admin')): ?>
            <a href="/news/create" class="btn btn-primary">Додати новину</a>
        <?php endif; ?>
    </div>

<?php if (empty($news)): ?>
    <div class="text-center py-5">
        <h3>Поки що новин немає</h3>
        <p class="text-muted">Слідкуйте за оновленнями!</p>
    </div>
<?php else: ?>
    <div class="row">
        <?php foreach ($news as $article): ?>
            <div class="col-md-6 col-lg-4 mb-4">
                <div class="card h-100">
                    <?php if (!empty($article['image'])): ?>
                        <img src="/public/uploads/news/<?= htmlspecialchars($article['image']) ?>"
                             class="card-img-top"
                             alt="<?= htmlspecialchars($article['title']) ?>"
                             style="height: 200px; object-fit: cover;">
                    <?php endif; ?>

                    <div class="card-body d-flex flex-column">
                        <h5 class="card-title">
                            <a href="/news/<?= htmlspecialchars($article['id']) ?>" class="text-decoration-none">
                                <?= htmlspecialchars($article['title']) ?>
                            </a>
                        </h5>

                        <p class="card-text text-muted small">
                            <?= htmlspecialchars(substr(strip_tags($article['content']), 0, 150)) ?>...
                        </p>

                        <div class="mt-auto">
                            <small class="text-muted">
                                <i class="fas fa-user"></i> <?= htmlspecialchars($article['author_name']) ?>
                                <i class="fas fa-calendar ms-2"></i> <?= date('d.m.Y', strtotime($article['created_at'])) ?>
                                <i class="fas fa-eye ms-2"></i> Перегляди: <?= $article['views'] ?>
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <!-- Пагінація -->
    <?php if ($totalPages > 1): ?>
        <nav aria-label="Навігація по сторінках">
            <ul class="pagination justify-content-center">
                <?php if ($currentPage > 1): ?>
                    <li class="page-item">
                        <a class="page-link" href="/news?page=<?= $currentPage - 1 ?>">Попередня</a>
                    </li>
                <?php endif; ?>

                <?php for ($i = max(1, $currentPage - 2); $i <= min($totalPages, $currentPage + 2); $i++): ?>
                    <li class="page-item <?= $i == $currentPage ? 'active' : '' ?>">
                        <a class="page-link" href="/news?page=<?= $i ?>"><?= $i ?></a>
                    </li>
                <?php endfor; ?>

                <?php if ($currentPage < $totalPages): ?>
                    <li class="page-item">
                        <a class="page-link" href="/news?page=<?= $currentPage + 1 ?>">Наступна</a>
                    </li>
                <?php endif; ?>
            </ul>
        </nav>
    <?php endif; ?>
<?php endif; ?>

<?php $content = ob_get_clean(); ?>
<?php include __DIR__ . '/../layouts/main.php'; ?>