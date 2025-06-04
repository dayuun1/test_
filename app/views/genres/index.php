<?php ob_start(); ?>

    <div class="row mb-4">
        <div class="col">
            <h1>Жанри манги</h1>
        </div>
        <div class="col-auto">
            <?php if (Auth::check() && Auth::hasRole('admin')): ?>
                <a href="/genres/create" class="btn btn-primary">Додати жанр</a>
            <?php endif; ?>
        </div>
    </div>

    <div class="row">
        <?php foreach ($genres as $genre): ?>
            <div class="col-md-4 col-sm-6 mb-3">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title"><?= htmlspecialchars($genre['name']) ?></h5>
                        <p class="card-text"><?= htmlspecialchars(substr($genre['description'], 0, 100)) ?>...</p>
                        <a href="/genres/<?= $genre['slug'] ?>" class="btn btn-primary btn-sm">Переглянути</a>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

<?php $content = ob_get_clean(); ?>
<?php include __DIR__ . '/../layouts/main.php'; ?>