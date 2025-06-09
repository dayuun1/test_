<?php ob_start(); ?>
    <div class="row">
        <div class="col-12 col-lg-8 mx-auto">
            <h1 class="mb-4">Редагувати розділ</h1>
            <form method="POST" enctype="multipart/form-data">
                <div class="mb-3">
                    <label class="form-label">Номер розділу</label>
                    <input type="number" name="chapter_number" class="form-control" required min="1"
                           value="<?= htmlspecialchars($chapter['chapter_number']) ?>">
                </div>

                <div class="mb-3">
                    <label class="form-label">Назва</label>
                    <input type="text" name="title" class="form-control" required maxlength="255"
                           value="<?= htmlspecialchars($chapter['title']) ?>">
                </div>

                <div class="mb-3">
                    <label class="form-label">PDF файл</label>
                    <?php if (!empty($chapter['pdf_path'])): ?>
                        <div class="mb-2">
                            <a href="/manga/<?= $chapter['manga_id'] ?>/chapter/<?= $chapter['chapter_number'] ?>/pdf"
                               target="_blank" class="btn btn-sm btn-outline-primary">Переглянути поточний PDF</a>
                        </div>
                    <?php endif; ?>
                    <input type="file" name="pdf" class="form-control" accept=".pdf">
                    <div class="form-text">Залишіть порожнім, щоб зберегти поточний файл</div>
                </div>

                <div class="d-flex gap-2">
                    <button class="btn btn-primary">Зберегти</button>
                    <a href="/manga/<?= $chapter['manga_id'] ?>" class="btn btn-secondary">Скасувати</a>
                    <a href="/manga" class="btn btn-outline-secondary">До списку манґ</a>
                </div>
            </form>
        </div>
    </div>
<?php $content = ob_get_clean(); ?>
<?php include __DIR__ . '/../layouts/main.php'; ?>