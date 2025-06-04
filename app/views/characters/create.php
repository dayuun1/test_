<?php ob_start(); ?>

<h1>Додати персонажа</h1>

<form method="POST" action="/characters/create" enctype="multipart/form-data">
    <div class="mb-3">
        <label for="name" class="form-label">Ім'я персонажа</label>
        <input type="text" class="form-control" id="name" name="name" required>
    </div>

    <div class="mb-3">
        <label for="description" class="form-label">Опис</label>
        <textarea class="form-control" id="description" name="description" rows="4"></textarea>
    </div>

    <div class="mb-3">
        <label for="manga_id" class="form-label">Манга (опціонально)</label>
        <select class="form-select" id="manga_id" name="manga_id">
            <option value="">-- Без манги --</option>
            <?php foreach ($allManga as $manga): ?>
                <option value="<?= htmlspecialchars($manga['id']) ?>">
                    <?= htmlspecialchars($manga['title']) ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>

    <div class="mb-3">
        <label for="image" class="form-label">Зображення персонажа</label>
        <input type="file" class="form-control" id="image" name="image" accept=".jpg,.jpeg,.png,.webp">
    </div>

    <button type="submit" class="btn btn-primary">Зберегти</button>
    <a href="/characters" class="btn btn-secondary">Скасувати</a>
</form>

<?php $content = ob_get_clean(); ?>
<?php include __DIR__ . '/../layouts/main.php'; ?>
