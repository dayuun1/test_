<?php
ob_start(); ?>

    <div class="row justify-content-center">
        <div class="col-md-6 text-center">
            <div class="error-template">
                <h1 class="display-1 text-muted">401</h1>
                <h2>Сторінку не знайдено!</h2>
                <div class="error-details mb-4">
                    Вибачте, але сторінка, яку ви шукаєте, не існує.
                </div>
                <div class="error-actions">
                    <a href="/" class="btn btn-primary btn-lg">
                        <i class="icon-home"></i>
                        На головну
                    </a>
                    <a href="/manga" class="btn btn-outline-primary btn-lg">
                        Каталог манги
                    </a>
                </div>
            </div>
        </div>
    </div>

    <style>
        .error-template {
            padding: 40px 15px;
            text-align: center;
        }
        .error-actions {
            margin-top: 15px;
            margin-bottom: 15px;
        }
        .error-actions .btn {
            margin-right: 10px;
        }
    </style>

<?php $content = ob_get_clean(); ?>
<?php $title = 'Сторінку не знайдено - 404'; ?>
<?php include __DIR__ . '/../layouts/main.php'; ?>