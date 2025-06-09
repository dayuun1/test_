<?php ob_start(); ?>

<div class="row">
    <div class="col-12 col-lg-8 mx-auto">
        <article class="mb-4">
            <header class="mb-4">
                <h1 class="display-5 mb-3"><?= htmlspecialchars($article['title']) ?></h1>

                <div class="d-flex align-items-center text-muted mb-3">
                    <i class="fas fa-user me-2"></i>
                    <span class="me-3"><?= htmlspecialchars($article['author_name']) ?></span>

                    <i class="fas fa-calendar me-2"></i>
                    <span class="me-3"><?= date('d.m.Y H:i', strtotime($article['created_at'])) ?></span>

                    <i class="fas fa-eye me-2"></i>
                    <span><?= $article['views'] ?> переглядів</span>
                </div>

                <?php if (Auth::hasRole('admin')): ?>
                    <div class="mb-3">
                        <a href="/news/edit/<?= $article['id'] ?>" class="btn btn-sm btn-outline-primary">
                            <i class="fas fa-edit"></i> Редагувати
                        </a>
                    </div>
                <?php endif; ?>
            </header>

            <?php if (!empty($article['image'])): ?>
                <div class="text-center mb-4">
                    <img src="/public/uploads/news/<?= htmlspecialchars($article['image']) ?>"
                         class="img-fluid rounded"
                         alt="<?= htmlspecialchars($article['title']) ?>"
                         style="max-height: 600px; object-fit: cover;">
                </div>
            <?php endif; ?>

            <div class="article-content">
                <?= nl2br(htmlspecialchars($article['content'])) ?>
            </div>
        </article>

        <div class="d-flex justify-content-between align-items-center border-top pt-3">
            <a href="/news" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left"></i> Назад до новин
            </a>

            <div class="text-muted small">
                Опубліковано: <?= date('d.m.Y H:i', strtotime($article['created_at'])) ?>
                <?php if ($article['updated_at'] !== $article['created_at']): ?>
                    <br>Оновлено: <?= date('d.m.Y H:i', strtotime($article['updated_at'])) ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<style>
    .article-content {
        font-size: 1.1rem;
        line-height: 1.8;
    }

    .article-content p {
        margin-bottom: 1.5rem;
    }
</style>

<?php $content = ob_get_clean(); ?>
<?php include __DIR__ . '/../layouts/main.php'; ?>
