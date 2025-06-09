<?php
class SearchController extends Controller {
    private $mangaModel;

    public function __construct() {
        parent::__construct();
        $this->mangaModel = new Manga();
    }

    public function index() {
        $query = trim($_GET['q'] ?? '');
        $results = [];

        if (!empty($query)) {
            $results = $this->mangaModel->searchByTitle($query);
        }

        echo $this->render('search/index', [
            'query' => $query,
            'results' => $results,
            'title' => 'Результати пошуку: ' . htmlspecialchars($query)
        ]);
    }
}