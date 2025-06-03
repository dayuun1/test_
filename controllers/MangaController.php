<?php
class MangaController extends Controller {
    private $mangaModel;

    public function __construct() {
        parent::__construct();
        $this->mangaModel = new Manga($this->db);
    }

    public function index() {
        $manga = $this->mangaModel->findAll();

        if (isset($_GET['ajax'])) {
            $this->renderJSON(['manga' => $manga]);
        }

        $this->render('manga/index', [
            'manga' => $manga,
            'title' => 'Каталог манги'
        ]);
    }

    public function show() {
        $id = isset($_GET['id']) ? $_GET['id'] : 0;
        $manga = $this->mangaModel->findWithGenres($id);

        if (!$manga) {
            http_response_code(404);
            $this->render('errors/404');
            return;
        }

        if (isset($_GET['ajax'])) {
            $this->renderJSON(['manga' => $manga]);
        }

        $this->render('manga/show', [
            'manga' => $manga,
            'title' => $manga['title']
        ]);
    }

    public function search() {
        $query = isset($_GET['q']) ? $_GET['q'] : '';
        $results = $this->mangaModel->search($query);

        if (isset($_GET['ajax'])) {
            $this->renderJSON(['results' => $results]);
        }

        $this->render('manga/search', [
            'results' => $results,
            'query' => $query,
            'title' => 'Пошук: ' . $query
        ]);
    }
}