<?php ob_start(); ?>

    <h1>Керування мангою</h1>

    <a href="/manga/create" class="btn btn-success mb-3">Додати нову мангу</a>

    <table class="table table-bordered table-striped align-middle">
        <thead class="table-light">
        <tr>
            <th>ID</th>
            <th>Обкладинка</th>
            <th>Назва</th>
            <th>Автор</th>
            <th>Статус</th>
            <th>Жанри</th>
            <th>Переглядів</th>
            <th>Розділи</th>
            <th>Дата створення</th>
            <th>Дії</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($mangaList as $manga): ?>
            <tr>
                <td><?= $manga['id'] ?></td>
                <td>
                    <?php if ($manga['cover_image']): ?>
                        <img src="/public/uploads/manga/covers/<?= $manga['cover_image'] ?>"
                             style="width: 70px;">
                    <?php endif; ?>
                </td>
                <td><?= htmlspecialchars($manga['title']) ?></td>
                <td><?= htmlspecialchars($manga['author']) ?></td>
                <td><?= ucfirst($manga['status']) ?></td>
                <td><?= htmlspecialchars($manga['genres'] ?? '—') ?></td>
                <td><?= $manga['views'] ?></td>
                <td><?= $manga['chapters_count'] ?></td>
                <td><?= date('d.m.Y H:i', strtotime($manga['created_at'])) ?></td>
                <td class="d-flex gap-1 flex-wrap">
                    <a href="/manga/<?= $manga['id'] ?>" class="btn btn-sm btn-outline-primary">Переглянути</a>
                    <a href="/manga/edit/<?= $manga['id'] ?>" class="btn btn-sm btn-outline-warning">Редагувати</a>
                    <form method="post" action="/manga/<?= $manga['id'] ?>/delete"
                          onsubmit="return confirm('Видалити цю мангу?')" class="d-inline">
                        <button type="submit" class="btn btn-sm btn-outline-danger">Видалити</button>
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>

<?php $content = ob_get_clean(); ?>
<?php include __DIR__ . '/../layouts/main.php'; ?>