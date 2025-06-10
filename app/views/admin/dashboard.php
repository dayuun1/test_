<?php
ob_start(); ?>

    <h1>Панель адміністратора</h1>

    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card text-white bg-primary mb-3">
                <div class="card-body">
                    <h5 class="card-title">Манга</h5>
                    <p class="card-text fs-4"><?= $stats['total_manga'] ?></p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-success mb-3">
                <div class="card-body">
                    <h5 class="card-title">Користувачі</h5>
                    <p class="card-text fs-4"><?= $stats['total_users'] ?></p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-warning mb-3">
                <div class="card-body">
                    <h5 class="card-title">Розділи</h5>
                    <p class="card-text fs-4"><?= $stats['total_chapters'] ?></p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-info mb-3">
                <div class="card-body">
                    <h5 class="card-title">Новини</h5>
                    <p class="card-text fs-4"><?= $stats['total_news'] ?? 0 ?></p>
                </div>
            </div>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card text-white bg-info mb-3">
                <div class="card-body">
                    <h5 class="card-title">Персонажі</h5>
                    <p class="card-text fs-4"><?= $stats['total_characters'] ?? 0 ?></p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-warning mb-3">
                <div class="card-body">
                    <h5 class="card-title">Команди</h5>
                    <p class="card-text fs-4"><?= $stats['total_teams'] ?? 0 ?></p>
                </div>
            </div>
        </div>
    </div>

    <div class="mb-4">
        <h3>Швидкі дії</h3>
        <div class="row">
            <div class="col-md-6">
                <div class="d-flex gap-2 flex-wrap">
                    <a href="/manga/create" class="btn btn-success">Додати мангу</a>
                    <a href="/characters/create" class="btn btn-info">Додати персонажа</a>
                    <a href="/genres/create" class="btn btn-warning">Додати жанр</a>
                </div>
            </div>
            <div class="col-md-6">
                <div class="d-flex gap-2 flex-wrap">
                    <a href="/news/create" class="btn btn-primary">Додати новину</a>
                    <a href="/teams/create" class="btn btn-secondary">Створити команду</a>
                </div>
            </div>
        </div>
    </div>

    <div class="mb-4">
        <h3>Керування контентом</h3>
        <div class="row">
            <div class="col-md-6">
                <div class="list-group">
                    <a href="/admin/manga" class="list-group-item list-group-item-action">
                        <i class="fas fa-book me-2"></i>Керування мангою
                    </a>
                    <a href="/admin/users" class="list-group-item list-group-item-action">
                        <i class="fas fa-users me-2"></i>Керування користувачами
                    </a>
                    <a href="/admin/news" class="list-group-item list-group-item-action">
                        <i class="fas fa-newspaper me-2"></i>Керування новинами
                    </a>
                </div>
            </div>
            <div class="col-md-6">
                <div class="list-group">
                    <a href="/admin/characters" class="list-group-item list-group-item-action">
                        <i class="fas fa-user-friends me-2"></i>Керування персонажами
                    </a>
                    <a href="/admin/teams" class="list-group-item list-group-item-action">
                        <i class="fas fa-users-cog me-2"></i>Керування командами
                    </a>
                    <a href="/admin/genres" class="list-group-item list-group-item-action">
                        <i class="fas fa-users-cog me-2"></i>Керування жанрами
                    </a>
                </div>
            </div>
        </div>
    </div>

    <h3>Нещодавно зареєстровані користувачі</h3>
    <table class="table table-bordered table-striped">
        <thead>
        <tr>
            <th>ID</th>
            <th>Ім'я</th>
            <th>Email</th>
            <th>Роль</th>
            <th>Дата створення</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($stats['recent_users'] as $user): ?>
            <tr>
                <td><?= $user['id'] ?></td>
                <td><?= htmlspecialchars($user['username']) ?></td>
                <td><?= htmlspecialchars($user['email']) ?></td>
                <td>
                    <span class="badge bg-<?= $user['role'] === 'admin' ? 'danger' : ($user['role'] === 'translator' ? 'warning' : 'secondary') ?>">
                        <?= htmlspecialchars($user['role']) ?>
                    </span>
                </td>
                <td><?= date('d.m.Y H:i', strtotime($user['created_at'])) ?></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>

<?php $content = ob_get_clean(); ?>
<?php include __DIR__ . '/../layouts/main.php'; ?>