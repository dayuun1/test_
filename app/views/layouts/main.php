
<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title = isset($title) ? $title : 'MangaSite' ?></title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="/public/css/style.css" rel="stylesheet">
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container">
        <a class="navbar-brand" href="/">MangaSite</a>

        <div class="navbar-nav me-auto">
            <a class="nav-link" href="/manga">Манга</a>
            <a class="nav-link" href="/genres">Жанри</a>
            <a class="nav-link" href="/characters">Персонажі</a>
            <a class="nav-link" href="/news">Новини</a>
            <a class="nav-link" href="/teams">Команди</a>
        </div>
        <form class="d-flex me-3" method="GET" action="/search">
            <input class="form-control me-2" type="search" name="q" placeholder="Пошук манги..." aria-label="Пошук">
            <button class="btn btn-outline-light" type="submit">Пошук</button>
        </form>
        <div class="navbar-nav">
            <?php if (Auth::check()): ?>
                <div class="dropdown">
                    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                        <?= Auth::user()['username'] ?>
                    </a>
                    <ul class="dropdown-menu">
                        <?php if (Auth::hasRole('admin')): ?>
                            <li><a class="dropdown-item" href="/admin">Адмін панель</a></li>
                        <?php endif; ?>
                        <?php if (Auth::hasRole('translator') || Auth::hasRole('admin')): ?>
                            <li><a class="dropdown-item" href="/manga/create">Додати мангу</a></li>
                        <?php endif; ?>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <form method="POST" action="/logout" class="d-inline">
                                <button type="submit" class="dropdown-item">Вийти</button>
                            </form>
                        </li>
                    </ul>
                </div>
            <?php else: ?>
                <a class="nav-link" href="/login">Увійти</a>
                <a class="nav-link" href="/register">Реєстрація</a>
            <?php endif; ?>
        </div>
    </div>
</nav>

<main class="container mt-4">
    <?= $content ?>
</main>

<footer class="bg-dark text-light mt-5 py-4">
    <div class="container">
        <div class="row">
            <div class="col-md-6">
                <h5>MangaSite</h5>
                <p>Найкращий сайт для читання манги українською мовою</p>
            </div>
            <div class="col-md-6">
                <h5>Корисні посилання</h5>
                <ul class="list-unstyled">
                    <li><a href="/about" class="text-light">Про нас</a></li>
                    <li><a href="/contact" class="text-light">Контакти</a></li>
                    <li><a href="/rules" class="text-light">Правила</a></li>
                </ul>
            </div>
        </div>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="/public/js/app.js"></script>
</body>
</html>