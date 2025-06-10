<?php ob_start(); ?>
<div class="row">
    <div class="col-md-4">
        <?php if ($manga['cover_image']): ?>
            <img src="/public/uploads/manga/covers/<?= $manga['cover_image'] ?>"
                 class="img-fluid rounded" alt="<?= htmlspecialchars($manga['title']) ?>">
        <?php endif; ?>
    </div>
    <div class="col-md-8">
        <h1><?= htmlspecialchars($manga['title']) ?></h1>

        <div class="mb-3">
            <span class="badge bg-primary"><?= ucfirst($manga['status']) ?></span>
            <span class="badge bg-secondary">Переглядів: <?= $manga['views'] ?></span>
            <?php if ($manga['rating'] > 0): ?>
                <span class="badge bg-warning">Рейтинг: <?= $manga['rating'] ?>/5</span>
            <?php endif; ?>
        </div>

        <p><strong>Автор:</strong> <?= htmlspecialchars($manga['author']) ?></p>
        <p><strong>Художник:</strong> <?= htmlspecialchars($manga['artist']) ?></p>

        <?php if (!empty($genres)): ?>
            <p><strong>Жанри:</strong>
                <?php foreach ($genres as $genre): ?>
                    <a href="/genres/<?= $genre['id'] ?>" class="badge bg-info text-decoration-none"><?= htmlspecialchars($genre['name']) ?></a>
                <?php endforeach; ?>
            </p>
        <?php endif; ?>

        <div class="mb-4">
            <h5>Опис</h5>
            <p><?= nl2br(htmlspecialchars($manga['description'])) ?></p>
        </div>

        <div class="mt-4">
            <h5>Рейтинг</h5>
            <p>Середній рейтинг: <?= round($ratingStats['average_rating'], 1) ?>/5 (<?= $ratingStats['total_ratings'] ?> оцінок)</p>

            <?php if (Auth::check()): ?>
                <form id="rating-form" method="post" action="/manga/<?= $manga['id'] ?>/set-rating">
                    <label for="rating">Ваша оцінка:</label>
                    <select name="rating" id="rating" class="form-select w-auto d-inline-block">
                        <?php for ($i = 1; $i <= 5; $i++): ?>
                            <option value="<?= $i ?>" <?= ($userRating == $i ? 'selected' : '') ?>><?= $i ?></option>
                        <?php endfor; ?>
                    </select>
                    <button type="submit" class="btn btn-primary btn-sm">Оцінити</button>
                </form>
            <?php else: ?>
                <p><a href="/login">Увійдіть</a>, щоб оцінити цю мангу.</p>
            <?php endif; ?>
        </div>

        <?php if (!empty($externalRatings)): ?>
            <div class="mt-3">
                <h6>Оцінки з інших джерел</h6>
                <ul>
                    <?php foreach ($externalRatings as $ext): ?>
                        <li>
                            <?= htmlspecialchars($ext['source_name']) ?>: <?= $ext['rating'] ?>/<?= $ext['max_rating'] ?>
                            <?php if ($ext['source_url']): ?>
                                – <a href="<?= htmlspecialchars($ext['source_url']) ?>" target="_blank">Джерело</a>
                            <?php endif; ?>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

    </div>
</div>

<?php if (!empty($characters)): ?>
    <div class="mt-5">
        <h3>Персонажі</h3>
        <div class="row">
            <?php foreach ($characters as $character): ?>
                <div class="col-md-2 mb-2">
                    <div class="card h-100">
                        <?php if (!empty($character['image'])): ?>
                            <img src="/public/uploads/characters/<?= htmlspecialchars($character['image']) ?>" style="height: 200px; object-fit: cover;" class="card-img-top" alt="<?= htmlspecialchars($character['name']) ?>">
                        <?php endif; ?>
                        <div class="card-body">
                            <h5 class="card-title"><?= htmlspecialchars($character['name']) ?></h5>
                            <?php if (!empty($character['description'])): ?>
                                <p class="card-text"><?= htmlspecialchars(mb_strimwidth($character['description'], 0, 20, '...')) ?></p>
                            <?php endif; ?>
                            <a href="/characters/<?= $character['id'] ?>" class="btn btn-sm btn-outline-primary">Детальніше</a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
<?php endif; ?>

<?php $teamModel = new Team();
if (Auth::check() && (Auth::hasRole('translator') && $teamModel->userHasAccessToManga(Auth::user()['id'], $manga['id']) || Auth::hasRole('admin'))): ?>
    <a href="/manga/<?= $manga['id'] ?>/upload" class="btn btn-success">Завантажити розділ</a>
<?php endif; ?>

<div class="mt-5">
    <h3>Розділи</h3>
    <?php if (empty($chapters)): ?>
        <p>Розділи ще не додані.</p>
    <?php else: ?>
        <div class="list-group">
            <?php foreach ($chapters as $chapter): ?>
                <a href="/manga/<?= $manga['id'] ?>/chapter/<?= $chapter['chapter_number'] ?>"
                   class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="mb-1">Розділ <?= $chapter['chapter_number'] ?></h6>
                        <?php if ($chapter['title']): ?>
                            <p class="mb-1"><?= htmlspecialchars($chapter['title']) ?></p>
                        <?php endif; ?>
                        <small>Сторінок: <?= $chapter['pages_count'] ?> | Переглядів: <?= $chapter['views'] ?></small>
                    </div>
                    <small class="text-muted"><?= date('d.m.Y', strtotime($chapter['created_at'])) ?></small>
                </a>
            <?php endforeach; ?>
        </div>

        <?php if ($totalPages > 1): ?>
            <nav class="mt-3">
                <ul class="pagination">
                    <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                        <li class="page-item <?= $i == $currentPage ? 'active' : '' ?>">
                            <a class="page-link" href="?page=<?= $i ?>#chapters"><?= $i ?></a>
                        </li>
                    <?php endfor; ?>
                </ul>
            </nav>
        <?php endif; ?>
    <?php endif; ?>
</div>

<div class="mt-5">
    <h3>Коментарі</h3>

    <?php if (Auth::check()): ?>
        <form id="comment-form" method="post" action="/manga/<?= $manga['id'] ?>/add-comment">
            <div class="mb-3">
                <textarea name="content" class="form-control" rows="3" placeholder="Залишити коментар..."></textarea>
            </div>
            <button type="submit" class="btn btn-secondary">Надіслати</button>
        </form>
    <?php else: ?>
        <p><a href="/login">Увійдіть</a>, щоб залишити коментар.</p>
    <?php endif; ?>

   <?php foreach ($comments as $comment): ?>
    <div class="mt-3 border-bottom pb-2">
        <strong>
            <?php
            $username = 'Невідомий користувач';
            foreach ($users as $user) {
                if($user['id'] == Auth::user()['id']) {
                    $username = 'Ви';
                }
                else if ($user['id'] == $comment['user_id']) {
                    $username = $user['username'];
                    break;
                }
            }
            echo htmlspecialchars($username);
            ?>
        </strong>
        <small class="text-muted"><?= $comment['created_at'] ?></small>
        <p><?= nl2br(htmlspecialchars($comment['content'])) ?></p>
    </div>
            <?php if (!empty($comment['replies'])): ?>
                <div class="ms-4">
                    <?php foreach ($comment['replies'] as $reply): ?>
                        <div class="border-start ps-2 mb-2">
                            <strong><?= htmlspecialchars($reply['username']) ?></strong> <small class="text-muted"><?= $reply['created_at'] ?></small>
                            <p><?= nl2br(htmlspecialchars($reply['content'])) ?></p>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    <?php endforeach; ?>

    <?php if ($totalCommentsPages > 1): ?>
        <nav class="mt-3">
            <ul class="pagination">
                <?php for ($i = 1; $i <= $totalCommentsPages; $i++): ?>
                    <li class="page-item <?= $i == $commentsPage ? 'active' : '' ?>">
                        <a class="page-link" href="?comments_page=<?= $i ?>#comments"><?= $i ?></a>
                    </li>
                <?php endfor; ?>
            </ul>
        </nav>
    <?php endif; ?>
</div>
<script>
    document.addEventListener("DOMContentLoaded", () => {
        const ratingForm = document.getElementById("rating-form");
        if (ratingForm) {
            ratingForm.addEventListener("submit", async (e) => {
                e.preventDefault();
                const formData = new FormData(ratingForm);
                const response = await fetch(ratingForm.action, {
                    method: "POST",
                    body: formData,
                    headers: { "X-Requested-With": "XMLHttpRequest" }
                });

                const result = await response.json();
                if (result.success) {
                    alert(`Рейтинг збережено: ${result.averageRating}/5`);
                    location.reload();
                } else {
                    alert(result.error);
                }
            });
        }

        const commentForm = document.getElementById("comment-form");
        if (commentForm) {
            commentForm.addEventListener("submit", async (e) => {
                e.preventDefault();
                const formData = new FormData(commentForm);
                const response = await fetch(commentForm.action, {
                    method: "POST",
                    body: formData,
                    headers: { "X-Requested-With": "XMLHttpRequest" }
                });

                const result = await response.json();

                if (result.success) {
                    const textarea = commentForm.querySelector("textarea");
                    const userName = "Ви";
                    const now = new Date().toLocaleString('uk-UA');
                    const newComment = document.createElement("div");

                    newComment.className = "mt-3 border-bottom pb-2";
                    newComment.innerHTML = `
                    <strong>${userName}</strong> <small class="text-muted">${now}</small>
                    <p>${textarea.value.replace(/\n/g, '<br>')}</p>
                `;

                    const commentsContainer = commentForm.parentElement;
                    commentsContainer.appendChild(newComment);
                    textarea.value = "";
                } else {
                    alert(result.error);
                }
            });
        }
    });
</script>
<?php $content = ob_get_clean(); ?>
<?php include __DIR__ . '/../layouts/main.php'; ?>
