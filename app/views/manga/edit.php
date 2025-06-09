<?php ob_start(); ?>
<div class="row">
    <div class="col-12 col-lg-8 mx-auto">
        <h1 class="mb-4">Редагувати мангу</h1>
        <form method="POST" enctype="multipart/form-data">
            <div class="mb-3">
                <label class="form-label">Назва</label>
                <input type="text" name="title" class="form-control" required maxlength="255"
                       value="<?= htmlspecialchars($manga['title']) ?>">
            </div>

            <div class="mb-3">
                <label class="form-label">Опис</label>
                <textarea name="description" class="form-control" rows="5"><?= htmlspecialchars($manga['description']) ?></textarea>
            </div>

            <div class="mb-3">
                <label class="form-label">Автор</label>
                <input type="text" name="author" class="form-control"
                       value="<?= htmlspecialchars($manga['author']) ?>">
            </div>

            <div class="mb-3">
                <label class="form-label">Художник</label>
                <input type="text" name="artist" class="form-control"
                       value="<?= htmlspecialchars($manga['artist']) ?>">
            </div>

            <div class="mb-3">
                <label class="form-label">Статус</label>
                <select name="status" class="form-select">
                    <option value="ongoing" <?= $manga['status'] === 'ongoing' ? 'selected' : '' ?>>В процесі</option>
                    <option value="completed" <?= $manga['status'] === 'completed' ? 'selected' : '' ?>>Завершено</option>
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label">Обкладинка</label>
                <?php if (!empty($manga['cover_image'])): ?>
                    <div class="mb-2">
                        <img src="<?= htmlspecialchars($manga['cover_image']) ?>" class="img-thumbnail"
                             style="max-width:200px; max-height:200px; object-fit:cover">
                    </div>
                <?php endif; ?>
                <input type="file" name="cover" class="form-control" accept=".jpg,.jpeg,.png,.webp">
                <div class="form-text">Залишіть порожнім, щоб зберегти поточну обкладинку</div>
            </div>

            <div class="mb-3">
                <label class="form-label">Жанри</label><br>
                <?php foreach ($allGenres as $genre): ?>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="checkbox" name="genres[]" value="<?= $genre['id'] ?>"
                            <?= in_array($genre['id'], $selectedGenres) ? 'checked' : '' ?>>
                        <label class="form-check-label"><?= htmlspecialchars($genre['name']) ?></label>
                    </div>
                <?php endforeach; ?>
            </div>

            <div class="d-flex gap-2">
                <button class="btn btn-primary">Зберегти</button>
                <a href="/manga/<?= $manga['id'] ?>" class="btn btn-secondary">Скасувати</a>
                <a href="/manga" class="btn btn-outline-secondary">До списку</a>
            </div>
        </form>
    </div>
</div>
<?php $content = ob_get_clean(); ?>
<?php include __DIR__ . '/../layouts/main.php'; ?>
