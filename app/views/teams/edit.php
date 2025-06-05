<?php ob_start(); ?>

<h1>Редагувати команду: <?= htmlspecialchars($team['name']) ?></h1>

<form method="post" enctype="multipart/form-data">
    <div class="mb-3">
        <label for="name" class="form-label">Назва</label>
        <input type="text" name="name" id="name" class="form-control" value="<?= htmlspecialchars($team['name']) ?>" required>
    </div>

    <div class="mb-3">
        <label for="description" class="form-label">Опис</label>
        <textarea name="description" id="description" class="form-control" rows="4"><?= htmlspecialchars($team['description']) ?></textarea>
    </div>

    <div class="mb-3">
        <label for="image" class="form-label">Нове зображення</label>
        <input type="file" name="image" id="image" class="form-control">
        <?php if (!empty($team['image'])): ?>
            <img src="/public/uploads/teams/<?= htmlspecialchars($team['image']) ?>" alt="Зображення команди" class="img-thumbnail mt-2" style="max-width: 150px;">
        <?php endif; ?>
    </div>

    <button type="submit" class="btn btn-primary">Оновити</button>
</form>

<?php $content = ob_get_clean(); ?>
<?php include __DIR__ . '/../layouts/main.php'; ?>
