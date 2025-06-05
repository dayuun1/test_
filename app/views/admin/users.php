<?php ob_start(); ?>

    <h1>Управління користувачами</h1>

    <table class="table table-bordered table-striped mt-4">
        <thead class="table-light">
        <tr>
            <th>ID</th>
            <th>Ім’я</th>
            <th>Email</th>
            <th>Роль</th>
            <th>Дата реєстрації</th>
            <th>Дії</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($users as $user): ?>
            <tr>
                <td><?= $user['id'] ?></td>
                <td><?= htmlspecialchars($user['username']) ?></td>
                <td><?= htmlspecialchars($user['email']) ?></td>
                <td>
                    <form method="post" action="/admin/users/<?= $user['id'] ?>/role" class="d-flex align-items-center gap-2">
                        <select name="role" class="form-select form-select-sm w-auto">
                            <option value="user" <?= $user['role'] === 'user' ? 'selected' : '' ?>>user</option>
                            <option value="translator" <?= $user['role'] === 'translator' ? 'selected' : '' ?>>translator</option>
                            <option value="admin" <?= $user['role'] === 'admin' ? 'selected' : '' ?>>admin</option>
                        </select>
                        <button type="submit" class="btn btn-sm btn-outline-primary">Змінити</button>
                    </form>
                </td>
                <td><?= date('d.m.Y H:i', strtotime($user['created_at'])) ?></td>
                <td>
                    <form method="post" action="/users/<?= $user['id'] ?>/delete" onsubmit="return confirm('Безповоротно видалити цього користувача?')" class="d-inline">
                        <button type="submit" class="btn btn-sm btn-outline-danger">Видалити</button>
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>

<?php $content = ob_get_clean(); ?>
<?php include __DIR__ . '/../layouts/main.php'; ?>