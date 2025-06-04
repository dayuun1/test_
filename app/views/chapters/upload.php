<?php ob_start(); ?>

<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h4 class="mb-0">Додати розділ для манги: <?= htmlspecialchars($manga['title']) ?></h4>
            </div>
            <div class="card-body">
                <form method="POST" action="" enctype="multipart/form-data">
                    <div class="mb-3">
                        <label for="chapter_number" class="form-label">Номер розділу *</label>
                        <input type="number" class="form-control" id="chapter_number" name="chapter_number" required>
                    </div>

                    <div class="mb-3">
                        <label for="title" class="form-label">Назва розділу</label>
                        <input type="text" class="form-control" id="title" name="title">
                    </div>

                    <div class="mb-3">
                        <label for="pdf" class="form-label">PDF файл розділу *</label>
                        <input type="file" class="form-control" id="pdf" name="pdf" accept="application/pdf" required>
                        <small class="form-text text-muted">Лише PDF файли</small>
                    </div>

                    <div class="d-flex justify-content-between">
                        <a href="/manga/<?= $manga['id'] ?>" class="btn btn-secondary">Назад до манги</a>
                        <button type="submit" class="btn btn-primary">Завантажити розділ</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php $content = ob_get_clean(); ?>
<?php include __DIR__ . '/../layouts/main.php'; ?>
