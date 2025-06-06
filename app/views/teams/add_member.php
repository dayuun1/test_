<?php ob_start(); ?>
<h1>Додати учасника до команди: <?= htmlspecialchars($team['name']) ?></h1>

<form method="post" action="/teams/<?= $team['id'] ?>/add-member">
    <div class="mb-3">
        <label for="user_id" class="form-label">Користувач</label>
        <select name="user_id" id="user_id" class="form-select">
            <?php foreach ($users as $user): ?>
                <option value="<?= $user['id'] ?>"><?= htmlspecialchars($user['username']) ?> (<?= $user['role'] ?>)</option>
            <?php endforeach; ?>
        </select>
    </div>
    <button type="submit" class="btn btn-primary">Додати</button>
</form>
<?php $content = ob_get_clean(); ?>
<?php include __DIR__ . '/../layouts/main.php'; ?>
