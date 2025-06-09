<?php ob_start(); ?>
    <div class="row">
        <div class="col-12 col-lg-8 mx-auto">
            <h1 class="mb-4">Редагувати персонажа</h1>
            <form method="POST" action="/characters/edit/<?= $character['id'] ?>" enctype="multipart/form-data">
                <div class="mb-3">
                    <label class="form-label">Ім’я</label>
                    <input type="text" name="name" class="form-control" required maxlength="255"
                           value="<?= htmlspecialchars($character['name']) ?>">
                </div>
                <div class="mb-3">
                    <label class="form-label">Прив’язка до манги</label>
                    <select name="manga_id" class="form-select">
                        <option value="">— Без манги</option>
                        <?php foreach ($allManga as $m): ?>
                            <option value="<?= $m['id'] ?>" <?= $character['manga_id']==$m['id']?'selected':'' ?>>
                                <?= htmlspecialchars($m['title']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label">Опис</label>
                    <textarea name="description" rows="5" class="form-control"><?= htmlspecialchars($character['description']) ?></textarea>
                </div>
                <div class="mb-3">
                    <label class="form-label">Зображення</label>
                    <?php if ($character['image']): ?>
                        <div class="mb-2">
                            <img src="/public/uploads/characters/<?= htmlspecialchars($character['image']) ?>"
                                 class="img-thumbnail" style="max-width:200px;max-height:200px;object-fit:cover">
                        </div>
                    <?php endif; ?>
                    <input type="file" name="image" class="form-control" accept=".jpg,.jpeg,.png,.webp">
                    <div class="form-text">Залишіть порожнім, щоб зберегти старе</div>
                </div>
                <div class="d-flex gap-2">
                    <button class="btn btn-primary">Зберегти</button>
                    <a href="/characters/<?= $character['id'] ?>" class="btn btn-secondary">Скасувати</a>
                    <a href="/characters" class="btn btn-outline-secondary">До списку</a>
                </div>
            </form>
        </div>
    </div>
<?php $content = ob_get_clean(); ?>
<?php include __DIR__ . '/../layouts/main.php'; ?>
