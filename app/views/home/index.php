<?php ob_start(); ?>

    <div class="jumbotron bg-primary text-white p-5 rounded mb-5">
        <div class="container">
            <h1 class="display-4">Ласкаво просимо до MangaSite!</h1>
            <p class="lead">Найкращий сайт для читання манги українською мовою</p>
            <a class="btn btn-light btn-lg" href="/manga" role="button">Переглянути каталог</a>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8">
            <h2>Остання манга</h2>
            <div class="row">
                <?php foreach ($latestManga as $manga): ?>
                    <div class="col-md-4 col-sm-6 mb-4">
                        <div class="card h-100">
                            <?php if ($manga['cover_image']): ?>
                                <img src="/public/uploads/manga/covers/<?= $manga['cover_image'] ?>"
                                     class="card-img-top" alt="<?= htmlspecialchars($manga['title']) ?>"
                                     style="height: 250px; object-fit: cover;">
                            <?php else: ?>
                                <div class="card-img-top bg-light d-flex align-items-center justify-content-center" style="height: 250px;">
                                    <span class="text-muted">Немає обкладинки</span>
                                </div>
                            <?php endif; ?>
                            <div class="card-body d-flex flex-column">
                                <h5 class="card-title"><?= htmlspecialchars($manga['title']) ?></h5>
                                <p class="card-text flex-grow-1">
                                    <?= htmlspecialchars(substr($manga['description'], 0, 80)) ?>...
                                </p>
                                <div class="mt-auto">
                                    <small class="text-muted">
                                        <?= date('d.m.Y', strtotime($manga['created_at'])) ?>
                                    </small>
                                    <a href="/manga/<?= $manga['slug'] ?>" class="btn btn-primary btn-sm float-end">
                                        Читати
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

            <div class="text-center mt-4">
                <a href="/manga" class="btn btn-outline-primary">Переглянути всю мангу</a>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h5>Статистика сайту</h5>
                </div>
                <div class="card-body">
                    <p><strong>Всього манги:</strong> <?= count($latestManga) ?>+</p>
                    <p><strong>Активних користувачів:</strong> <?= Auth::check() ? 'Ви онлайн!' : 'Увійдіть в систему' ?></p>
                    <p><strong>Останнє оновлення:</strong> <?= date('d.m.Y H:i') ?></p>
                </div>
            </div>

            <div class="card mt-4">
                <div class="card-header">
                    <h5>Швидкі посилання</h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="/genres" class="btn btn-outline-secondary">Жанри</a>
                        <a href="/characters" class="btn btn-outline-secondary">Персонажі</a>
                        <a href="/news" class="btn btn-outline-secondary">Новини</a>
                        <a href="/forum" class="btn btn-outline-secondary">Форум</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

<?php $content = ob_get_clean(); ?>
<?php include 'layouts/main.php'; ?>