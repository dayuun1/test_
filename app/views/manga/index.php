<?php ob_start(); ?>

    <div class="row mb-4">
        <div class="col">
            <h1>Каталог манги</h1>
        </div>
        <div class="col-auto">
            <?php if (Auth::check() && (Auth::hasRole('translator') || Auth::hasRole('admin'))): ?>
                <a href="/manga/create" class="btn btn-primary">Додати мангу</a>
            <?php endif; ?>
        </div>
    </div>

    <!-- Популярна манга -->
    <section class="mb-5">
        <h2>Популярна манга</h2>
        <div class="row" id="popularMangaContainer">
            <?php foreach ($popularManga as $manga): ?>
                <div class="col-md-3 col-sm-6 mb-4">
                    <div class="card h-100">
                        <?php if ($manga['cover_image']): ?>
                            <img src="/public/uploads/manga/covers/<?= $manga['cover_image'] ?>"
                                 class="card-img-top" alt="<?= htmlspecialchars($manga['title']) ?>"
                                 style="height: 300px; object-fit: cover;">
                        <?php endif; ?>
                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title"><?= htmlspecialchars($manga['title']) ?></h5>
                            <p class="card-text flex-grow-1"><?= htmlspecialchars(substr($manga['description'], 0, 100)) ?>...</p>
                            <div class="mt-auto">
                                <small class="text-muted">Переглядів: <?= $manga['views'] ?></small>
                                <a href="/manga/<?= $manga['id'] ?>" class="btn btn-primary btn-sm float-end">Читати</a>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </section>

    <!-- Нова манга -->
    <section>
        <h2>Нова манга</h2>
        <div class="row" id="recentMangaContainer">
            <?php foreach ($recentManga as $manga): ?>
                <div class="col-md-3 col-sm-6 mb-4">
                    <div class="card h-100">
                        <?php if ($manga['cover_image']): ?>
                            <img src="/public/uploads/manga/covers/<?= $manga['cover_image'] ?>"
                                 class="card-img-top" alt="<?= htmlspecialchars($manga['title']) ?>"
                                 style="height: 300px; object-fit: cover;">
                        <?php endif; ?>
                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title"><?= htmlspecialchars($manga['title']) ?></h5>
                            <p class="card-text flex-grow-1"><?= htmlspecialchars(substr($manga['description'], 0, 100)) ?>...</p>
                            <div class="mt-auto">
                                <small class="text-muted"><?= date('d.m.Y', strtotime($manga['created_at'])) ?></small>
                                <a href="/manga/<?= $manga['id'] ?>" class="btn btn-primary btn-sm float-end">Читати</a>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </section>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            function renderMangaCard(manga) {
                return `
        <div class="col-md-3 col-sm-6 mb-4">
            <div class="card h-100">
                ${manga.cover_image ? `<img src="/public/uploads/manga/covers/${manga.cover_image}"
                    class="card-img-top" style="height: 300px; object-fit: cover;" alt="${manga.title}">` : ''}
                <div class="card-body d-flex flex-column">
                    <h5 class="card-title">${manga.title}</h5>
                    <p class="card-text flex-grow-1">${manga.description.slice(0, 100)}...</p>
                    <div class="mt-auto">
                        <small class="text-muted">Переглядів: ${manga.views}</small>
                        <small class="text-muted">${manga.created_at ? new Date(manga.created_at).toLocaleDateString() : '—'}</small>
                        <a href="/manga/${manga.id}" class="btn btn-primary btn-sm float-end">Читати</a>
                    </div>
                </div>
            </div>
        </div>`;
            }

            function updatePopularManga() {
                fetch('/api/manga/popular')
                    .then(res => res.json())
                    .then(data => {
                        document.getElementById('popularMangaContainer').innerHTML =
                            data.map(renderMangaCard).join('');
                    })
                    .catch(console.error);
            }

            function updateRecentManga() {
                fetch('/api/manga/recent')
                    .then(res => res.json())
                    .then(data => {
                        document.getElementById('recentMangaContainer').innerHTML =
                            data.map(renderMangaCard).join('');
                    })
                    .catch(console.error);
            }

            updatePopularManga();
            updateRecentManga();

            setInterval(() => {
                updatePopularManga();
                updateRecentManga();
            }, 30000);
        });
    </script>
<?php $content = ob_get_clean(); ?>
<?php include __DIR__ . '/../layouts/main.php'; ?>