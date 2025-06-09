<?php ob_start(); ?>

    <h1>Керування персонажами</h1>

    <a href="/characters/create" class="btn btn-success mb-3">Додати нового персонажа</a>

    <table class="table table-bordered table-striped align-middle">
        <thead class="table-light">
        <tr>
            <th>ID</th>
            <th>Зображення</th>
            <th>Ім'я</th>
            <th>Манга</th>
            <th>Опис</th>
            <th>Дата створення</th>
            <th>Дії</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($charactersList as $character): ?>
            <tr>
                <td><?= $character['id'] ?></td>
                <td>
                    <?php if ($character['image']): ?>
                        <img src="/public/uploads/characters/<?= $character['image'] ?>"
                             alt="<?= htmlspecialchars($character['name']) ?>"
                             style="width: 70px; height: 70px; object-fit: cover; border-radius: 50%;">
                    <?php else: ?>
                        <div class="bg-light d-flex align-items-center justify-content-center rounded-circle"
                             style="width: 70px; height: 70px;">
                            <small class="text-muted">Без фото</small>
                        </div>
                    <?php endif; ?>
                </td>
                <td>
                    <strong><?= htmlspecialchars($character['name']) ?></strong>
                </td>
                <td>
                    <?php if ($character['manga_title']): ?>
                        <a href="/manga/<?= $character['manga_id'] ?>" class="text-decoration-none">
                            <?= htmlspecialchars($character['manga_title']) ?>
                        </a>
                    <?php else: ?>
                        <span class="text-muted">Без манги</span>
                    <?php endif; ?>
                </td>
                <td>
                    <div style="max-width: 200px;">
                        <?php if ($character['description']): ?>
                            <?= htmlspecialchars(mb_substr($character['description'], 0, 30)) ?>
                            <?php if (mb_strlen($character['description']) > 30): ?>
                                <span class="text-muted">...</span>
                            <?php endif; ?>
                        <?php else: ?>
                            <span class="text-muted">Без опису</span>
                        <?php endif; ?>
                    </div>
                </td>
                <td><?= date('d.m.Y H:i', strtotime($character['created_at'])) ?></td>
                <td class="d-flex gap-1 flex-wrap">
                    <a href="/characters/<?= $character['id'] ?>" class="btn btn-sm btn-outline-primary">Переглянути</a>
                    <a href="/characters/edit/<?= $character['id'] ?>" class="btn btn-sm btn-outline-warning">Редагувати</a>
                    <form method="post" action="/admin/characters/<?= $character['id'] ?>/delete"
                          onsubmit="return confirm('Видалити цього персонажа?')" class="d-inline">
                        <button type="submit" class="btn btn-sm btn-outline-danger">Видалити</button>
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>

<?php if (empty($charactersList)): ?>
    <div class="alert alert-info">
        <h5>Персонажі відсутні</h5>
        <p>Поки що немає створених персонажів. <a href="/characters/create">Створити першого персонажа</a></p>
    </div>
<?php endif; ?>

<?php $content = ob_get_clean(); ?>
<?php include __DIR__ . '/../layouts/main.php'; ?>