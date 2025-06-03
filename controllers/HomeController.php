<?php
class HomeController extends Controller {
    public function index() {
        $mangaModel = new Manga($this->db);
        $latestManga = array_slice($mangaModel->findAll(), 0, 12);

        $this->render('home/index', [
            'latestManga' => $latestManga,
            'title' => 'Головна сторінка'
        ]);
    }
}