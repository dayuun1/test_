<?php ob_start(); ?>

    <h1>Керування новинами</h1>

    <a href="/news/create" class="btn btn-success mb-3">Додати нову новину</a>

    <table class="table table-bordered table-striped align-middle">
        <thead class="table-light">
        <tr>
            <th>ID</th>
            <th>Зображення</th>
            <th>Заголовок</th>
            <th>Автор</th>
            <th>Статус</th>
            <th>Переглядів</th>
            <th>Дата створення</th>
            <th>Дії</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($newsList as $news): ?>
            <tr>
                <td><?= $news['id'] ?></td>
                <td>
                    <?php if ($news['image']): ?>
                        <img src="/public/uploads/news/<?= $news['image'] ?>"
                             alt="<?= htmlspecialchars($news['title']) ?>"
                             style="width: 70px; height: 50px; object-fit: cover;">
                    <?php else: ?>
                        <div class="bg-light d-flex align-items-center justify-content-center"
                             style="width: 70px; height: 50px;">
                            <small class="text-muted">Без фото</small>
                        </div>
                    <?php endif; ?>
                </td>
                <td>
                    <div style="max-width: 200px;">
                        <?= htmlspecialchars($news['title']) ?>
                    </div>
                </td>
                <td><?= htmlspecialchars($news['author_name'] ?? 'Невідомо') ?></td>
                <td>
                    <?php if ($news['is_published']): ?>
                        <span class="badge bg-success">Опубліковано</span>
                    <?php else: ?>
                        <span class="badge bg-warning">Чернетка</span>
                    <?php endif; ?>
                </td>
                <td><?= $news['views'] ?? 0 ?></td>
                <td><?= date('d.m.Y H:i', strtotime($news['created_at'])) ?></td>
                <td class="d-flex gap-1 flex-wrap">
                    <a href="/news/<?= $news['id'] ?>" class="btn btn-sm btn-outline-primary">Переглянути</a>
                    <a href="/news/edit/<?= $news['id'] ?>" class="btn btn-sm btn-outline-warning">Редагувати</a>
                    <form method="post" action="/admin/news/<?= $news['id'] ?>/delete"
                          onsubmit="return confirm('Видалити цю новину?')" class="d-inline">
                        <button type="submit" class="btn btn-sm btn-outline-danger">Видалити</button>
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>

<?php if (empty($newsList)): ?>
    <div class="alert alert-info">
        <h5>Новини відсутні</h5>
        <p>Поки що немає створених новин. <a href="/news/create">Створити першу новину</a></p>
    </div>
<?php endif; ?>

<?php $content = ob_get_clean(); ?>
<?php include __DIR__ . '/../layouts/main.php'; ?>