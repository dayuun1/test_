<?php
class MangaController extends Controller {
    private $mangaModel;
    private $chapterModel;
    private $ratingModel;
    private $commentModel;
    private $externalRatingModel;

    public function __construct() {
        parent::__construct();
        $this->mangaModel = new Manga();
        $this->chapterModel = new Chapter();
        $this->ratingModel = new MangaRating();
        $this->commentModel = new MangaComment();
        $this->externalRatingModel = new ExternalRating();
    }

    public function index() {
        $popularManga = $this->mangaModel->getPopular(12);
        $recentManga = $this->mangaModel->findAll([], 12, 0);

        echo $this->render('manga/index', [
            'popularManga' => $popularManga,
            'recentManga' => $recentManga,
            'title' => 'Каталог манги'
        ]);
    }

    public function show($id) {
        $manga = $this->mangaModel->find($id);
        if (!$manga) {
            http_response_code(404);
            echo $this->render('errors/404');
            return;
        }

        $characters = (new Character())->getByMangaId($manga['id']);

        // Пагінація розділів
        $perPage = 4;
        $page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
        $offset = ($page - 1) * $perPage;

        $chapters = $this->chapterModel->getByMangaPaginated($manga['id'], $perPage, $offset);
        $totalChapters = $this->chapterModel->countByManga($manga['id']);
        $totalPages = ceil($totalChapters / $perPage);

        // Збільшуємо лічильник переглядів
        $this->mangaModel->incrementViews($manga['id']);

        // Отримуємо жанри та рейтинги
        $genres = $this->mangaModel->getGenres($manga['id']);
        $ratingStats = $this->ratingModel->getMangaRatingStats($manga['id']);
        $externalRatings = $this->externalRatingModel->getByManga($manga['id']);

        // Рейтинг поточного користувача
        $userRating = null;
        if (Auth::check()) {
            $userRating = $this->ratingModel->getUserRating($manga['id'], Auth::user()['id']);
        }

        // Коментарі
        $commentsPerPage = 10;
        $commentsPage = isset($_GET['comments_page']) ? max(1, (int)$_GET['comments_page']) : 1;
        $commentsOffset = ($commentsPage - 1) * $commentsPerPage;

        $comments = $this->commentModel->getByManga($manga['id'], $commentsPerPage, $commentsOffset);
        $totalComments = $this->commentModel->countByManga($manga['id']);
        $totalCommentsPages = ceil($totalComments / $commentsPerPage);

        foreach ($comments as &$comment) {
            $comment['replies'] = $this->commentModel->getReplies($comment['id']);
        }

        echo $this->render('manga/show', [
            'manga' => $manga,
            'chapters' => $chapters,
            'genres' => $genres,
            'characters' => $characters,
            'ratingStats' => $ratingStats,
            'externalRatings' => $externalRatings,
            'userRating' => $userRating,
            'comments' => $comments,
            'title' => $manga['title'],
            'currentPage' => $page,
            'totalPages' => $totalPages,
            'commentsPage' => $commentsPage,
            'totalCommentsPages' => $totalCommentsPages,
        ]);
    }

    // AJAX метод для встановлення рейтингу
    public function setRating($mangaId) {
        if (!Auth::check()) {
            http_response_code(401);
            return $this->json(['error' => 'Необхідно увійти в систему']);
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            return $this->json(['error' => 'Метод не дозволено']);
        }

        $rating = floatval($_POST['rating'] ?? 0);
        if ($rating < 1 || $rating > 5) {
            return $this->json(['error' => 'Рейтинг повинен бути від 1 до 5']);
        }

        $manga = $this->mangaModel->find($mangaId);
        if (!$manga) {
            http_response_code(404);
            return $this->json(['error' => 'Манга не знайдена']);
        }

        $result = $this->ratingModel->setRating($mangaId, Auth::user()['id'], $rating);

        if ($result) {
            $newStats = $this->ratingModel->getMangaRatingStats($mangaId);
            return $this->json([
                'success' => true,
                'message' => 'Рейтинг збережено',
                'newRating' => $rating,
                'averageRating' => round($newStats['average_rating'], 1),
                'totalRatings' => $newStats['total_ratings']
            ]);
        } else {
            return $this->json(['error' => 'Помилка збереження рейтингу']);
        }
    }

    // Додавання коментаря
    public function addComment($mangaId) {
        if (!Auth::check()) {
            http_response_code(401);
            return $this->json(['error' => 'Необхідно увійти в систему']);
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            return $this->json(['error' => 'Метод не дозволено']);
        }

        $content = trim($_POST['content'] ?? '');
        $parentId = !empty($_POST['parent_id']) ? intval($_POST['parent_id']) : null;

        if (empty($content)) {
            return $this->json(['error' => 'Коментар не може бути порожнім']);
        }

        if (strlen($content) > 1000) {
            return $this->json(['error' => 'Коментар занадто довгий (максимум 1000 символів)']);
        }

        $manga = $this->mangaModel->find($mangaId);
        if (!$manga) {
            http_response_code(404);
            return $this->json(['error' => 'Манга не знайдена']);
        }

        $commentData = [
            'manga_id' => $mangaId,
            'user_id' => Auth::user()['id'],
            'content' => $content,
            'parent_id' => $parentId
        ];

        $commentId = $this->commentModel->create($commentData);

        if ($commentId) {
            return $this->json([
                'success' => true,
                'message' => 'Коментар додано',
                'commentId' => $commentId
            ]);
        } else {
            return $this->json(['error' => 'Помилка додавання коментаря']);
        }
    }

    // Видалення коментаря
    public function deleteComment($commentId) {
        if (!Auth::check()) {
            http_response_code(401);
            return $this->json(['error' => 'Необхідно увійти в систему']);
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            return $this->json(['error' => 'Метод не дозволено']);
        }

        $result = $this->commentModel->deleteComment($commentId, Auth::user()['id']);

        if ($result) {
            return $this->json(['success' => true, 'message' => 'Коментар видалено']);
        } else {
            return $this->json(['error' => 'Помилка видалення коментаря']);
        }
    }

    // Адмін функція для додавання зовнішніх рейтингів
    public function addExternalRating($mangaId) {
        $this->requireAuth();
        $this->requireRole('admin');

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $sourceName = $_POST['source_name'] ?? '';
            $rating = floatval($_POST['rating'] ?? 0);
            $maxRating = floatval($_POST['max_rating'] ?? 10);
            $votesCount = intval($_POST['votes_count'] ?? 0);
            $sourceUrl = $_POST['source_url'] ?? null;

            if (empty($sourceName) || $rating <= 0) {
                return $this->json(['error' => 'Невірні дані']);
            }

            $result = $this->externalRatingModel->updateRating($mangaId, $sourceName, $rating, $maxRating, $votesCount, $sourceUrl);

            if ($result) {
                return $this->json(['success' => true, 'message' => 'Зовнішній рейтинг додано']);
            } else {
                return $this->json(['error' => 'Помилка додавання рейтингу']);
            }
        }

        $manga = $this->mangaModel->find($mangaId);
        $availableSources = $this->externalRatingModel->getAllSources();

        echo $this->render('admin/external-rating', [
            'manga' => $manga,
            'availableSources' => $availableSources,
            'title' => 'Додати зовнішній рейтинг'
        ]);
    }

    // Інші методи залишаються без змін...
    public function create() {
        $this->requireAuth();
        $this->requireRole(['translator', 'admin']);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'title' => $_POST['title'],
                'description' => $_POST['description'],
                'author' => $_POST['author'],
                'artist' => $_POST['artist'],
                'status' => $_POST['status'],
                'created_by' => Auth::user()['id']
            ];

            if (isset($_FILES['cover']) && $_FILES['cover']['error'] === 0) {
                $data['cover_image'] = $this->uploadCover($_FILES['cover']);
            }

            $mangaId = $this->mangaModel->create($data);

            if (isset($_POST['genres'])) {
                $this->mangaModel->addGenres($mangaId, $_POST['genres']);
            }

            $this->redirect('/manga/' . $mangaId);
        }

        $genres = (new Genre())->findAll();
        echo $this->render('manga/create', ['genres' => $genres]);
    }

    private function generateSlug($title) {
        return strtolower(preg_replace('/[^a-zA-Z0-9-]/', '-', trim($title)));
    }

    private function uploadCover($file) {
        $uploadDir = 'public/uploads/manga/covers/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
        $filename = uniqid() . '.' . $extension;
        $filepath = $uploadDir . $filename;

        if (move_uploaded_file($file['tmp_name'], $filepath)) {
            return $filename;
        }

        throw new Exception('Помилка завантаження обкладинки');
    }

    public function apiPopular() {
        $popular = $this->mangaModel->getPopular(12);
        return $this->json($popular);
    }

    public function apiRecent() {
        $recent = $this->mangaModel->findAll([], 12, 0);
        return $this->json($recent);
    }
}