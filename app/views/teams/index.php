<?php ob_start(); ?>

    <div class="row mb-4">
        <div class="col">
            <h1>Команди перекладачів</h1>
        </div>
        <div class="col-auto">
            <?php if (Auth::check() && Auth::hasRole('admin')): ?>
                <a href="/teams/create" class="btn btn-primary">Створити команду</a>
            <?php endif; ?>
        </div>
    </div>

    <div class="row">
        <?php foreach ($teams as $team): ?>
            <div class="col-md-4 col-sm-6 mb-3">
                <div class="card">
                    <?php if (!empty($team['image'])): ?>
                        <img src="/public/uploads/teams/<?= htmlspecialchars($team['image']) ?>" class="card-img-top">
                    <?php endif; ?>
                    <div class="card-body">
                        <h5 class="card-title"><?= htmlspecialchars($team['name']) ?></h5>
                        <p class="card-text"><?= htmlspecialchars(substr($team['description'], 0, 100)) ?>...</p>
                        <a href="/teams/<?= $team['id'] ?>" class="btn btn-primary btn-sm">Переглянути</a>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

<?php $content = ob_get_clean(); ?>
<?php include __DIR__ . '/../layouts/main.php'; ?>