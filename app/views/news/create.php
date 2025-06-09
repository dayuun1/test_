<?php ob_start(); ?>

    <div class="row">
        <div class="col-12 col-lg-8 mx-auto">
            <h1 class="mb-4">Додати новину</h1>

            <form method="POST" action="/news/create" enctype="multipart/form-data">
                <div class="mb-3">
                    <label for="title" class="form-label">Заголовок новини</label>
                    <input type="text"
                           class="form-control"
                           id="title"
                           name="title"
                           required
                           maxlength="255"
                           value="<?= isset($_POST['title']) ? htmlspecialchars($_POST['title']) : '' ?>">
                </div>

                <div class="mb-3">
                    <label for="image" class="form-label">Зображення</label>
                    <input type="file"
                           class="form-control"
                           id="image"
                           name="image"
                           accept=".jpg,.jpeg,.png,.webp">
                    <div class="form-text">Дозволені формати: JPG, JPEG, PNG, WebP. Максимальний розмір: 5MB</div>
                </div>

                <div class="mb-3">
                    <label for="content" class="form-label">Зміст новини</label>
                    <textarea class="form-control"
                              id="content"
                              name="content"
                              rows="15"
                              required
                              placeholder="Введіть текст новини..."><?= isset($_POST['content']) ? htmlspecialchars($_POST['content']) : '' ?></textarea>
                </div>

                <div class="mb-3">
                    <div class="form-check">
                        <input class="form-check-input"
                               type="checkbox"
                               id="is_published"
                               name="is_published"
                               value="1"
                            <?= (isset($_POST['is_published']) && $_POST['is_published']) ? 'checked' : '' ?>>
                        <label class="form-check-label" for="is_published">
                            Опублікувати одразу
                        </label>
                    </div>
                    <div class="form-text">Якщо не вибрано, новина буде збережена як чернетка</div>
                </div>

                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Зберегти новину
                    </button>
                    <a href="/news" class="btn btn-secondary">
                        <i class="fas fa-times"></i> Скасувати
                    </a>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Автоматичне збільшення висоти текстового поля
        document.getElementById('content').addEventListener('input', function() {
            this.style.height = 'auto';
            this.style.height = (this.scrollHeight + 2) + 'px';
        });

        // Попередній перегляд зображення
        document.getElementById('image').addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    // Видаляємо попередній preview якщо є
                    const existingPreview = document.getElementById('image-preview');
                    if (existingPreview) {
                        existingPreview.remove();
                    }

                    // Створюємо новий preview
                    const preview = document.createElement('div');
                    preview.id = 'image-preview';
                    preview.className = 'mt-2';
                    preview.innerHTML = `
                <img src="${e.target.result}"
                     class="img-thumbnail"
                     style="max-width: 300px; max-height: 200px; object-fit: cover;"
                     alt="Попередній перегляд">
            `;

                    document.getElementById('image').parentNode.appendChild(preview);
                };
                reader.readAsDataURL(file);
            }
        });
    </script>

<?php $content = ob_get_clean(); ?>
<?php include __DIR__ . '/../layouts/main.php'; ?>