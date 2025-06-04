<?php ob_start(); ?>

<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="/manga">Манга</a></li>
        <li class="breadcrumb-item"><a href="/manga/<?= $manga['id'] ?>"><?= htmlspecialchars($manga['title']) ?></a></li>
        <li class="breadcrumb-item active">Розділ <?= $chapter['chapter_number'] ?></li>
    </ol>
</nav>

<div class="row mb-4">
    <div class="col">
        <h1><?= htmlspecialchars($manga['title']) ?> - Розділ <?= $chapter['chapter_number'] ?></h1>
        <?php if ($chapter['title']): ?>
            <h2 class="h4 text-muted"><?= htmlspecialchars($chapter['title']) ?></h2>
        <?php endif; ?>
    </div>
</div>

<div class="row mb-4">
    <div class="col">
        <div class="btn-group" role="group">
            <a href="/manga/<?= $manga['id'] ?>" class="btn btn-outline-primary">Назад до манги</a>
            <a href="/manga/<?= $manga['id'] ?>/chapter/<?= $chapter['chapter_number'] ?>/pdf"
               class="btn btn-primary" target="_blank">Читати PDF</a>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <embed src="/manga/<?= $manga['id'] ?>/chapter/<?= $chapter['chapter_number'] ?>/pdf"
               type="application/pdf"
               width="100%"
               height="1600px">
    </div>
</div>

<div class="row mt-4">
    <div class="col">
        <p><small class="text-muted">
                Сторінок: <?= $chapter['pages_count'] ?> |
                Переглядів: <?= $chapter['views'] ?> |
                Додано: <?= date('d.m.Y H:i', strtotime($chapter['created_at'])) ?>
            </small></p>
    </div>
</div>

<?php $content = ob_get_clean(); ?>
<?php include __DIR__ . '/../layouts/main.php'; ?>
