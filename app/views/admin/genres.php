<?php ob_start(); ?>

    <h1>Керування жанрами</h1>

    <a href="/genres/create" class="btn btn-success mb-3">Додати новий жанр</a>

    <table class="table table-bordered table-striped align-middle">
        <thead class="table-light">
        <tr>
            <th>ID</th>
            <th>Назва</th>
            <th>Опис</th>
            <th>Дата створення</th>
            <th>Дії</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($genresList as $genre): ?>
            <tr>
                <td><?= $genre['id'] ?></td>
                <td>
                    <strong><?= htmlspecialchars($genre['name']) ?></strong>
                </td>
                <td>
                    <div style="max-width: 200px;">
                        <?php if ($genre['description']): ?>
                            <?= htmlspecialchars(mb_substr($genre['description'], 0, 100)) ?>
                            <?php if (mb_strlen($genre['description']) > 100): ?>
                                <span class="text-muted">...</span>
                            <?php endif; ?>
                        <?php else: ?>
                            <span class="text-muted">Без опису</span>
                        <?php endif; ?>
                    </div>
                </td>
                <td><?= date('d.m.Y H:i', strtotime($genre['created_at'])) ?></td>
                <td class="d-flex gap-1 flex-wrap">
                    <a href="/genres/<?= $genre['id'] ?>" class="btn btn-sm btn-outline-primary">Переглянути</a>
                    <a href="/genres/edit/<?= $genre['id'] ?>" class="btn btn-sm btn-outline-warning">Редагувати</a>
                    <form method="post" action="/admin/genres/<?= $genre['id'] ?>/delete"
                          onsubmit="return confirm('Видалити цього персонажа?')" class="d-inline">
                        <button type="submit" class="btn btn-sm btn-outline-danger">Видалити</button>
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>

<?php if (empty($genresList)): ?>
    <div class="alert alert-info">
        <h5>Жанри відсутні</h5>
        <p>Поки що немає створених жанрів. <a href="/genres/create">Створити першого персонажа</a></p>
    </div>
<?php endif; ?>

<?php $content = ob_get_clean(); ?>
<?php include __DIR__ . '/../layouts/main.php'; ?>