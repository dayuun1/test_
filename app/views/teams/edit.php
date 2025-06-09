<?php ob_start(); ?>
<div class="row">
    <div class="col-12 col-lg-8 mx-auto">
        <h1 class="mb-4">Редагувати команду</h1>
        <form method="POST" action="/teams/edit/<?= $team['id'] ?>" enctype="multipart/form-data">
            <div class="mb-3">
                <label class="form-label">Назва команди</label>
                <input type="text" name="name" class="form-control" required maxlength="255"
                       value="<?= htmlspecialchars($team['name']) ?>">
            </div>
            <div class="mb-3">
                <label class="form-label">Опис</label>
                <textarea name="description" rows="5" class="form-control"><?= htmlspecialchars($team['description']) ?></textarea>
            </div>
            <div class="mb-3">
                <label class="form-label">Логотип / зображення команди</label>
                <?php if ($team['image']): ?>
                    <div class="mb-2">
                        <img src="/public/uploads/teams/<?= htmlspecialchars($team['image']) ?>"
                             class="img-thumbnail" style="max-width:200px;max-height:200px;object-fit:cover">
                    </div>
                <?php endif; ?>
                <input type="file" name="image" class="form-control">
                <div class="form-text">Залишіть порожнім, щоб зберегти поточне фото</div>
            </div>
            <div class="d-flex gap-2">
                <button class="btn btn-primary">Зберегти</button>
                <a href="/teams/<?= $team['id'] ?>" class="btn btn-secondary">Скасувати</a>
                <a href="/teams" class="btn btn-outline-secondary">До списку команд</a>
            </div>
        </form>
    </div>
</div>
<?php $content = ob_get_clean();
include __DIR__ . '/../layouts/main.php';
