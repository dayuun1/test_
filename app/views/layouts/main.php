
<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title = isset($title) ? $title : 'MangaUA' ?></title>

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <link href="/css/style.css" rel="stylesheet">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container">
        <a class="navbar-brand" href="/">
            <i class="fas fa-book-open me-2"></i>MangaUA
        </a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarNav">
            <div class="navbar-nav me-auto">
                <a class="nav-link" href="/manga">
                    <i class="fas fa-books me-1"></i>Манга
                </a>
                <a class="nav-link" href="/genres">
                    <i class="fas fa-tags me-1"></i>Жанри
                </a>
                <a class="nav-link" href="/characters">
                    <i class="fas fa-users me-1"></i>Персонажі
                </a>
                <a class="nav-link" href="/news">
                    <i class="fas fa-newspaper me-1"></i>Новини
                </a>
                <a class="nav-link" href="/teams">
                    <i class="fas fa-user-friends me-1"></i>Команди
                </a>
            </div>

            <form class="d-flex me-3" method="GET" action="/search">
                <div class="input-group">
                    <input class="form-control" type="search" name="q" placeholder="Пошук манги..." aria-label="Пошук">
                    <button class="btn btn-outline-light" type="submit">
                        <i class="fas fa-search"></i>
                    </button>
                </div>
            </form>

            <div class="navbar-nav">
                <?php if (Auth::check()): ?>
                    <div class="dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                            <i class="fas fa-user-circle me-1"></i><?= Auth::user()['username'] ?>
                        </a>
                        <ul class="dropdown-menu">
                            <?php if (Auth::hasRole('admin')): ?>
                                <li><a class="dropdown-item" href="/admin">
                                        <i class="fas fa-cog me-2"></i>Адмін панель
                                    </a></li>
                            <?php endif; ?>
                            <?php if (Auth::hasRole('translator') || Auth::hasRole('admin')): ?>
                                <li><a class="dropdown-item" href="/manga/create">
                                        <i class="fas fa-plus me-2"></i>Додати мангу
                                    </a></li>
                            <?php endif; ?>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <form method="POST" action="/logout" class="d-inline">
                                    <button type="submit" class="dropdown-item">
                                        <i class="fas fa-sign-out-alt me-2"></i>Вийти
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </div>
                <?php else: ?>
                    <a class="nav-link" href="/login">
                        <i class="fas fa-sign-in-alt me-1"></i>Увійти
                    </a>
                    <a class="nav-link" href="/register">
                        <i class="fas fa-user-plus me-1"></i>Реєстрація
                    </a>
                <?php endif; ?>
            </div>
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
                <h5><i class="fas fa-book-open me-2"></i>MangaUA</h5>
                <p>Найкращий сайт для читання манги українською мовою</p>
                <div class="social-links">
                    <a href="#" class="text-light me-3"><i class="fab fa-telegram-plane"></i></a>
                    <a href="#" class="text-light me-3"><i class="fab fa-discord"></i></a>
                    <a href="#" class="text-light me-3"><i class="fab fa-facebook"></i></a>
                </div>
            </div>
            <div class="col-md-6">
                <h5>Корисні посилання</h5>
                <ul class="list-unstyled">
                    <li><a href="/about" class="text-light">
                            <i class="fas fa-info-circle me-2"></i>Про нас
                        </a></li>
                    <li><a href="/contact" class="text-light">
                            <i class="fas fa-envelope me-2"></i>Контакти
                        </a></li>
                    <li><a href="/rules" class="text-light">
                            <i class="fas fa-file-alt me-2"></i>Правила
                        </a></li>
                </ul>
            </div>
        </div>
        <hr class="my-4">
        <div class="row align-items-center">
            <div class="col-md-6">
                <p class="mb-0">&copy; 2024 MangaUA. Всі права захищені.</p>
            </div>
            <div class="col-md-6 text-md-end">
                <small class="text-muted">Створено з ❤️ для українських читачів манги</small>
            </div>
        </div>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="/public/js/app.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const cards = document.querySelectorAll('.card');
        cards.forEach((card, index) => {
            card.style.animationDelay = `${index * 0.1}s`;
        });

        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });

        document.querySelectorAll('.btn').forEach(button => {
            button.addEventListener('mouseenter', function() {
                this.style.transform = 'translateY(-2px)';
            });

            button.addEventListener('mouseleave', function() {
                this.style.transform = 'translateY(0)';
            });
        });
    });
</script>
</body>
</html>