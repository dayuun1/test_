<?php
class MangaController extends Controller {
    private $mangaModel;
    private $chapterModel;

    public function __construct() {
        parent::__construct();
        $this->mangaModel = new Manga();
        $this->chapterModel = new Chapter();
    }

    // Головна сторінка з мангою
    public function index() {
        $popularManga = $this->mangaModel->getPopular(12);
        $recentManga = $this->mangaModel->findAll([], 12, 0);

        echo $this->render('manga/index', [
            'popularManga' => $popularManga,
            'recentManga' => $recentManga,
            'title' => 'Каталог манги'
        ]);
    }

    // Перегляд конкретної манги
    public function show($slug) {
        $manga = $this->mangaModel->findBySlug($slug);

        if (!$manga) {
            http_response_code(404);
            echo $this->render('errors/404');
            return;
        }

        $chapters = $this->chapterModel->findAll(['manga_id' => $manga['id']]);
        $this->mangaModel->incrementViews($manga['id']);

        echo $this->render('manga/show', [
            'manga' => $manga,
            'chapters' => $chapters,
            'title' => $manga['title']
        ]);
    }

    // Створення нової манги (тільки для перекладачів та адмінів)
    public function create() {
        $this->requireAuth();
        $this->requireRole('translator');

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'title' => $_POST['title'],
                'slug' => $this->generateSlug($_POST['title']),
                'description' => $_POST['description'],
                'author' => $_POST['author'],
                'artist' => $_POST['artist'],
                'status' => $_POST['status'],
                'created_by' => Auth::user()['id']
            ];

            // Обробка завантаження обкладинки
            if (isset($_FILES['cover']) && $_FILES['cover']['error'] === 0) {
                $data['cover_image'] = $this->uploadCover($_FILES['cover']);
            }

            $mangaId = $this->mangaModel->create($data);

            // Додавання жанрів
            if (isset($_POST['genres'])) {
                $this->addGenresToManga($mangaId, $_POST['genres']);
            }

            $this->redirect('/manga/' . $data['slug']);
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

    private function addGenresToManga($mangaId, $genreIds) {
        foreach ($genreIds as $genreId) {
            $stmt = $this->db->prepare("INSERT INTO manga_genres (manga_id, genre_id) VALUES (?, ?)");
            $stmt->execute([$mangaId, $genreId]);
        }
    }
}