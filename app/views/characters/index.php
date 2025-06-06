<?php ob_start(); ?>

<h1>Усі персонажі</h1>
<?php
$teamModel = new Team();
if (Auth::check() && (Auth::hasRole('translator') || Auth::hasRole('admin'))): ?>
<a href="/characters/create" class="btn btn-success mb-3"> Додати персонажа</a>
<?php endif; ?>

<?php if (empty($characters)): ?>
    <p>Персонажі відсутні.</p>
<?php else: ?>
    <div class="row">
        <?php foreach ($characters as $character): ?>
            <div class="col-md-4 mb-4">
                <div class="card h-100">
                    <?php if (!empty($character['image'])): ?>
                        <img src="/public/uploads/characters/<?= htmlspecialchars($character['image']) ?>" style="height: 400px; object-fit: cover;" class="card-img-top" alt="<?= htmlspecialchars($character['name']) ?>">
                    <?php endif; ?>
                    <div class="card-body">
                        <h5 class="card-title"><?= htmlspecialchars($character['name']) ?></h5>
                        <?php if (!empty($character['manga_title'])): ?>
                            <p class="card-text">Манга: <?= htmlspecialchars($character['manga_title']) ?></p>
                        <?php endif; ?>
                        <a href="/characters/<?= $character['id'] ?>" class="btn btn-primary">Детальніше</a>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<?php $content = ob_get_clean(); ?>
<?php include __DIR__ . '/../layouts/main.php'; ?>
