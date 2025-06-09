<?php ob_start(); ?>
    <div class="row">
        <div class="col-12 col-lg-8 mx-auto">
            <h1 class="mb-4">Редагувати жанр</h1>
            <form method="POST" action="/genres/edit/<?= $genre['id'] ?>">
                <div class="mb-3">
                    <label for="name" class="form-label">Назва жанру</label>
                    <input type="text" name="name" id="name" class="form-control" required maxlength="255"
                           value="<?= htmlspecialchars($genre['name']) ?>">
                </div>
                <div class="mb-3">
                    <label for="description" class="form-label">Опис жанру</label>
                    <textarea name="description" id="description" rows="5" class="form-control"><?= htmlspecialchars($genre['description']) ?></textarea>
                </div>
                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary">Зберегти зміни</button>
                    <a href="/genres/<?= $genre['id'] ?>" class="btn btn-secondary">Скасувати</a>
                    <a href="/genres" class="btn btn-outline-secondary">До списку жанрів</a>
                </div>
            </form>
        </div>
    </div>
<?php $content = ob_get_clean(); ?>
<?php include __DIR__ . '/../layouts/main.php'; ?>