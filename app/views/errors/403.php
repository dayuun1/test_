<?php
ob_start(); ?>

    <div class="row justify-content-center">
        <div class="col-md-10 text-center">
            <div class="error-template">
                <img src="\public\errors\403.png" alt="403 Image" class="img-fluid mb-4" style="max-height: 500px;">
                <div class="error-details mb-4">
                    Вибачте, у вас немає доступу.
                </div>
                <div class="error-actions">
                    <a href="/" class="btn btn-primary btn-lg">
                        <i class="icon-home"></i>
                        На головну
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
<?php $title = 'Немає доступу - 403'; ?>
<?php include __DIR__ . '/../layouts/main.php'; ?>