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

    public function show($id) {
        $manga = $this->mangaModel->find($id);
        $characters = (new Character())->getByMangaId($manga['id']);

        if (!$manga) {
            http_response_code(404);
            echo $this->render('errors/404');
            return;
        }

        $perPage = 4;
        $page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
        $offset = ($page - 1) * $perPage;

        $chapters = $this->chapterModel->getByMangaPaginated($manga['id'], $perPage, $offset);
        $totalChapters = $this->chapterModel->countByManga($manga['id']);
        $totalPages = ceil($totalChapters / $perPage);

        $this->mangaModel->incrementViews($manga['id']);
        $genres = $this->mangaModel->getGenres($manga['id']);

        echo $this->render('manga/show', [
            'manga' => $manga,
            'chapters' => $chapters,
            'genres' => $genres,
            'characters' => $characters,
            'title' => $manga['title'],
            'currentPage' => $page,
            'totalPages' => $totalPages
        ]);
    }

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

            if (isset($_FILES['cover']) && $_FILES['cover']['error'] === 0) {
                $data['cover_image'] = $this->uploadCover($_FILES['cover']);
            }

            $mangaId = $this->mangaModel->create($data);

            // Додавання жанрів
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




}