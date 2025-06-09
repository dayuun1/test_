<?php ob_start(); ?>

    <div class="row">
        <div class="col-12 col-lg-8 mx-auto">
            <h1 class="mb-4">Редагувати новину</h1>

            <form method="POST" action="/news/edit/<?= $article['id'] ?>" enctype="multipart/form-data">
                <div class="mb-3">
                    <label for="title" class="form-label">Заголовок новини</label>
                    <input type="text"
                           class="form-control"
                           id="title"
                           name="title"
                           required
                           maxlength="255"
                           value="<?= htmlspecialchars($article['title']) ?>">
                </div>

                <div class="mb-3">
                    <label for="image" class="form-label">Зображення</label>

                    <?php if (!empty($article['image'])): ?>
                        <div class="mb-2">
                            <p class="mb-1">Поточне зображення:</p>
                            <img src="/public/uploads/news/<?= htmlspecialchars($article['image']) ?>"
                                 class="img-thumbnail"
                                 style="max-width: 300px; max-height: 200px; object-fit: cover;"
                                 alt="Поточне зображення">
                        </div>
                    <?php endif; ?>

                    <input type="file"
                           class="form-control"
                           id="image"
                           name="image"
                           accept=".jpg,.jpeg,.png,.webp">
                    <div class="form-text">
                        Дозволені формати: JPG, JPEG, PNG, WebP. Максимальний розмір: 5MB
                        <?php if (!empty($article['image'])): ?>
                            <br>Залиште порожнім, щоб зберегти поточне зображення
                        <?php endif; ?>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="content" class="form-label">Зміст новини</label>
                    <textarea class="form-control"
                              id="content"
                              name="content"
                              rows="15"
                              required
                              placeholder="Введіть текст новини..."><?= htmlspecialchars($article['content']) ?></textarea>
                </div>

                <div class="mb-3">
                    <div class="form-check">
                        <input class="form-check-input"
                               type="checkbox"
                               id="is_published"
                               name="is_published"
                               value="1"
                            <?= $article['is_published'] ? 'checked' : '' ?>>
                        <label class="form-check-label" for="is_published">
                            Опублікована
                        </label>
                    </div>
                    <div class="form-text">Зніміть галочку, щоб сховати новину з публічного доступу</div>
                </div>

                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Зберегти зміни
                    </button>
                    <a href="/news/<?= htmlspecialchars($article['slug']) ?>" class="btn btn-secondary">
                        <i class="fas fa-times"></i> Скасувати
                    </a>
                    <a href="/news" class="btn btn-outline-secondary">
                        <i class="fas fa-list"></i> До списку новин
                    </a>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Автоматичне збільшення висоти текстового поля
        const textarea = document.getElementById('content');
        textarea.style.height = 'auto';
        textarea.style.height = (textarea.scrollHeight + 2) + 'px';

        textarea.addEventListener('input', function() {
            this.style.height = 'auto';
            this.style.height = (this.scrollHeight + 2) + 'px';
        });

        // Попередній перегляд нового зображення
        document.getElementById('image').addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    // Видаляємо попередній preview якщо є
                    const existingPreview = document.getElementById('new-image-preview');
                    if (existingPreview) {
                        existingPreview.remove();
                    }

                    // Створюємо новий preview
                    const preview = document.createElement('div');
                    preview.id = 'new-image-preview';
                    preview.className = 'mt-2';
                    preview.innerHTML = `
                <p class="mb-1"><strong>Нове зображення:</strong></p>
                <img src="${e.target.result}"
                     class="img-thumbnail"
                     style="max-width: 300px; max-height: 200px; object-fit: cover;"
                     alt="Попередній перегляд нового зображення">
            `;

                    document.getElementById('image').parentNode.appendChild(preview);
                };
                reader.readAsDataURL(file);
            }
        });
    </script>

<?php $content = ob_get_clean(); ?>
<?php include __DIR__ . '/../layouts/main.php'; ?>