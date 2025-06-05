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
                <td><?= htmlspecialchars($user['name']) ?></td>
                <td><?= htmlspecialchars($user['email']) ?></td>
                <td><?= htmlspecialchars($user['role']) ?></td>
                <td><?= date('d.m.Y H:i', strtotime($user['created_at'])) ?></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>

<?php $content = ob_get_clean(); ?>
<?php include __DIR__ . '/../layouts/main.php'; ?>