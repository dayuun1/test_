<?php ob_start(); ?>

    <h1>Додати жанр</h1>

    <form method="POST" action="/genres/create">
        <div class="mb-3">
            <label for="name" class="form-label">Назва жанру</label>
            <input type="text" class="form-control" id="name" name="name" required>
        </div>

        <div class="mb-3">
            <label for="description" class="form-label">Опис (необов'язково)</label>
            <textarea class="form-control" id="description" name="description" rows="4"></textarea>
        </div>

        <button type="submit" class="btn btn-primary">Зберегти</button>
        <a href="/genres" class="btn btn-secondary">Скасувати</a>
    </form>

<?php $content = ob_get_clean(); ?>
<?php include __DIR__ . '/../layouts/main.php'; ?>