<?php ob_start(); ?>

<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h4 class="mb-0">Додати нову мангу</h4>
            </div>
            <div class="card-body">
                <form method="POST" action="/manga/create" enctype="multipart/form-data">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="title" class="form-label">Назва манги *</label>
                                <input type="text" class="form-control" id="title" name="title" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="author" class="form-label">Автор</label>
                                <input type="text" class="form-control" id="author" name="author">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="artist" class="form-label">Художник</label>
                                <input type="text" class="form-control" id="artist" name="artist">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="status" class="form-label">Статус</label>
                                <select class="form-control" id="status" name="status">
                                    <option value="ongoing">Виходить</option>
                                    <option value="completed">Завершена</option>
                                    <option value="hiatus">Перерва</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="description" class="form-label">Опис</label>
                        <textarea class="form-control" id="description" name="description" rows="5"></textarea>
                    </div>

                    <div class="mb-3">
                        <label for="cover" class="form-label">Обкладинка</label>
                        <input type="file" class="form-control" id="cover" name="cover" accept="image/*">
                        <small class="form-text text-muted">Дозволені формати: JPG, PNG, WEBP</small>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Жанри</label>
                        <div class="row">
                            <?php foreach ($genres as $genre): ?>
                                <div class="col-md-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox"
                                               id="genre_<?= $genre['id'] ?>"
                                               name="genres[]"
                                               value="<?= $genre['id'] ?>">
                                        <label class="form-check-label" for="genre_<?= $genre['id'] ?>">
                                            <?= htmlspecialchars($genre['name']) ?>
                                        </label>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>

                    <div class="d-flex justify-content-between">
                        <a href="/manga" class="btn btn-secondary">Скасувати</a>
                        <button type="submit" class="btn btn-primary">Додати мангу</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php $content = ob_get_clean(); ?>
<?php include __DIR__ . '/../layouts/main.php'; ?>
