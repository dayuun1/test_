<?php ob_start(); ?>

    <div class="container">
        <h1>Додати мангу до команди "<?= htmlspecialchars($team['name']) ?>"</h1>

        <form method="POST" action="/teams/<?= $team['id'] ?>/add-manga">
            <div class="mb-3">
                <label for="manga_id" class="form-label">Оберіть мангу</label>
                <select name="manga_id" id="manga_id" class="form-select" required>
                    <option value="">-- Оберіть мангу --</option>
                    <?php foreach ($mangaList as $manga): ?>
                        <option value="<?= $manga['id'] ?>">
                            <?= htmlspecialchars($manga['title']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <button type="submit" class="btn btn-primary">Додати</button>
            <a href="/teams/<?= $team['id'] ?>" class="btn btn-secondary">Назад</a>
        </form>
    </div>

<?php $content = ob_get_clean(); ?>
<?php include __DIR__ . '/../layouts/main.php'; ?>