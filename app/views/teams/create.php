<?php ob_start(); ?>

    <h1>Створити команду</h1>

    <form method="post" enctype="multipart/form-data">
        <div class="mb-3">
            <label for="name" class="form-label">Назва команди</label>
            <input type="text" name="name" id="name" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="description" class="form-label">Опис</label>
            <textarea name="description" id="description" class="form-control" rows="4"></textarea>
        </div>

        <div class="mb-3">
            <label for="image" class="form-label">Зображення</label>
            <input type="file" name="image" id="image" class="form-control">
        </div>

        <button type="submit" class="btn btn-success">Створити</button>
    </form>

<?php $content = ob_get_clean(); ?>
<?php include __DIR__ . '/../layouts/main.php'; ?>