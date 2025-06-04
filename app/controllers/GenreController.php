<?php
class GenreController extends Controller {
    private $genreModel;
    private $mangaModel;

    public function __construct() {
        parent::__construct();
        $this->genreModel = new Genre();
        $this->mangaModel = new Manga();
    }

    public function index() {
        $genres = $this->genreModel->findAll();

        echo $this->render('genres/index', [
            'genres' => $genres,
            'title' => 'Жанри манги'
        ]);
    }

    public function show($id) {
        $genre = $this->genreModel->findById($id);

        if (!$genre) {
            http_response_code(404);
            echo $this->render('errors/404');
            return;
        }

        $manga = $this->genreModel->getMangaByGenre($genre['id']);

        echo $this->render('genres/show', [
            'genre' => $genre,
            'manga' => $manga,
            'title' => 'Жанр: ' . $genre['name']
        ]);
    }

    public function create() {
        $this->requireAuth();
        $this->requireRole('admin');

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'name' => $_POST['name'],
                'slug' => $this->generateSlug($_POST['name']),
                'description' => $_POST['description'] ?? ''
            ];

            $this->genreModel->create($data);
            $this->redirect('/genres');
        }

        echo $this->render('genres/create', [
            'title' => 'Додати жанр'
        ]);
    }

    public function getMangaByGenre($genreId) {
        $stmt = $this->db->prepare("
        SELECT m.* FROM manga m
        JOIN manga_genres mg ON m.id = mg.manga_id
        WHERE mg.genre_id = ?
    ");
        $stmt->execute([$genreId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    private function generateSlug($name) {
        return strtolower(preg_replace('/[^a-zA-Z0-9-]/', '-', trim($name)));
    }
}