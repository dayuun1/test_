<?php ob_start(); ?>

<h1><?= htmlspecialchars($character['name']) ?></h1>

<?php if (!empty($character['image'])): ?>
    <img src="/uploads/characters/<?= htmlspecialchars($character['image']) ?>" class="img-fluid mb-3" alt="<?= htmlspecialchars($character['name']) ?>">
<?php endif; ?>

<?php if (!empty($manga)): ?>
    <p><strong>Манга:</strong> <a href="/manga/<?= $manga['id'] ?>"><?= htmlspecialchars($manga['title']) ?></a></p>
<?php endif; ?>

<p><strong>Опис:</strong></p>
<p><?= nl2br(htmlspecialchars($character['description'])) ?></p>

<a href="/characters" class="btn btn-secondary">Назад до списку</a>

<?php $content = ob_get_clean(); ?>
<?php include __DIR__ . '/../layouts/main.php'; ?>
