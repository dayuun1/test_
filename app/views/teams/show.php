<?php ob_start(); ?>

<h1><?= htmlspecialchars($team['name']) ?></h1>

<?php if (!empty($team['image'])): ?>
    <img src="/public/uploads/teams/<?= htmlspecialchars($team['image']) ?>" style="width: 300px; height: auto;" class="img-fluid mb-3">
<?php endif; ?>

<p><?= nl2br(htmlspecialchars($team['description'])) ?></p>

<h3>Учасники команди</h3>
<ul>
    <?php foreach ($members as $member): ?>
        <li><?= htmlspecialchars($member['username']) ?> (<?= htmlspecialchars($member['role']) ?>)</li>
    <?php endforeach; ?>
</ul>


<h3>Манґа з доступом</h3>
<div class="row">
    <?php foreach ($accessibleManga as $manga): ?>
        <div class="col-md-3 col-sm-6 mb-4">
            <div class="card h-100">
                <?php if ($manga['cover_image']): ?>
                    <img src="/public/uploads/manga/covers/<?= $manga['cover_image'] ?>"
                         class="card-img-top" alt="<?= htmlspecialchars($manga['title']) ?>"
                         style="height: 300px; object-fit: cover;">
                <?php endif; ?>
                <div class="card-body d-flex flex-column">
                    <h5 class="card-title"><?= htmlspecialchars($manga['title']) ?></h5>
                    <p class="card-text flex-grow-1"><?= htmlspecialchars(substr($manga['description'], 0, 50)) ?>...</p>
                    <div class="mt-auto">
                        <small class="text-muted">Переглядів: <?= $manga['views'] ?></small>
                        <a href="/manga/<?= $manga['id'] ?>" class="btn btn-primary btn-sm float-end">Читати</a>
                    </div>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
</div>
<?php if (Auth::hasRole('admin') || Auth::user()['id'] == $team['created_by']): ?>
    <a href="/teams/<?= $team['id'] ?>/add-manga" class="btn btn-success">Додати мангу</a>
<?php endif; ?>
<?php
$teamModel = new Team();
if (Auth::hasRole('admin') || (Auth::user()['role'] === 'translator' && $teamModel->isMember($team['id'], Auth::user()['id']))): ?>
    <a href="/teams/<?= $team['id'] ?>/add-member" class="btn btn-secondary mt-2">Додати учасника</a>
<?php endif; ?>
<?php $content = ob_get_clean(); ?>
<?php include __DIR__ . '/../layouts/main.php'; ?>
