<?php ob_start(); ?>

    <h1>Керування командами</h1>

    <a href="/teams/create" class="btn btn-success mb-3">Створити нову команду</a>

    <table class="table table-bordered table-striped align-middle">
        <thead class="table-light">
        <tr>
            <th>ID</th>
            <th>Зображення</th>
            <th>Назва</th>
            <th>Опис</th>
            <th>Кількість учасників</th>
            <th>Дата створення</th>
            <th>Дії</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($teamsList as $team): ?>
            <tr>
                <td><?= $team['id'] ?></td>
                <td>
                    <?php if ($team['image']): ?>
                        <img src="/public/uploads/teams/<?= $team['image'] ?>"
                             alt="<?= htmlspecialchars($team['name']) ?>"
                             style="width: 70px; height: 50px; object-fit: cover;">
                    <?php else: ?>
                        <div class="bg-light d-flex align-items-center justify-content-center"
                             style="width: 70px; height: 50px;">
                            <i class="fas fa-users text-muted"></i>
                        </div>
                    <?php endif; ?>
                </td>
                <td>
                    <strong><?= htmlspecialchars($team['name']) ?></strong>
                </td>
                <td>
                    <div style="max-width: 250px;">
                        <?php if ($team['description']): ?>
                            <?= htmlspecialchars(mb_substr($team['description'], 0, 120)) ?>
                            <?php if (mb_strlen($team['description']) > 120): ?>
                                <span class="text-muted">...</span>
                            <?php endif; ?>
                        <?php else: ?>
                            <span class="text-muted">Без опису</span>
                        <?php endif; ?>
                    </div>
                </td>
                <td>
                    <span class="badge bg-info"><?= $team['member_count'] ?? 0 ?> учасників</span>
                </td>
                <td><?= date('d.m.Y H:i', strtotime($team['created_at'])) ?></td>
                <td class="d-flex gap-1 flex-wrap">
                    <a href="/teams/<?= $team['id'] ?>" class="btn btn-sm btn-outline-primary">Переглянути</a>
                    <a href="/teams/edit/<?= $team['id'] ?>" class="btn btn-sm btn-outline-warning">Редагувати</a>
                    <form method="post" action="/admin/teams/<?= $team['id'] ?>/delete"
                          onsubmit="return confirm('Видалити цю команду? Це також видалить всі зв\'язки з учасниками та мангою.')" class="d-inline">
                        <button type="submit" class="btn btn-sm btn-outline-danger">Видалити</button>
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>

<?php if (empty($teamsList)): ?>
    <div class="alert alert-info">
        <h5>Команди відсутні</h5>
        <p>Поки що немає створених команд. <a href="/teams/create">Створити першу команду</a></p>
    </div>
<?php endif; ?>

<?php $content = ob_get_clean(); ?>
<?php include __DIR__ . '/../layouts/main.php'; ?>