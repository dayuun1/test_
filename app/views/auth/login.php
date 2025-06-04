<?php
ob_start(); ?>

<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h4 class="mb-0">Вхід до системи</h4>
            </div>
            <div class="card-body">
                <?php if (isset($error)): ?>
                    <div class="alert alert-danger"><?= $error ?></div>
                <?php endif; ?>

                <form method="POST" action="/login">
                    <div class="mb-3">
                        <label for="username" class="form-label">Ім'я користувача</label>
                        <input type="text" class="form-control" id="username" name="username" required>
                    </div>

                    <div class="mb-3">
                        <label for="password" class="form-label">Пароль</label>
                        <input type="password" class="form-control" id="password" name="password" required>
                    </div>

                    <button type="submit" class="btn btn-primary">Увійти</button>
                    <a href="/register" class="btn btn-link">Немає акаунту? Зареєструватися</a>
                </form>
            </div>
        </div>
    </div>
</div>

<?php $content = ob_get_clean(); ?>
<?php include 'layouts/main.php'; ?>
