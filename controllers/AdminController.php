<?php
class AdminController extends Controller {
    public function index() {
        $this->requireAdmin();

        $this->render('admin/index', [
            'title' => 'Панель адміністратора'
        ]);
    }

    public function manageManga() {
        $this->requireAdmin();

        $mangaModel = new Manga($this->db);
        $manga = $mangaModel->findAll();

        $this->render('admin/manga', [
            'manga' => $manga,
            'title' => 'Управління мангою'
        ]);
    }

    public function createManga() {
        $this->requireAdmin();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'title' => $_POST['title'],
                'description' => $_POST['description'],
                'author' => $_POST['author'],
                'status' => $_POST['status'],
                'cover_image' => isset($_POST['cover_image']) ? $_POST['cover_image'] : '',
                'created_at' => date('Y-m-d H:i:s')
            ];

            $mangaModel = new Manga($this->db);
            if ($mangaModel->create($data)) {
                if (isset($_POST['ajax'])) {
                    $this->renderJSON(['success' => true, 'message' => 'Мангу додано']);
                }
                header('Location: /admin/manga');
                exit;
            }
        }

        $this->render('admin/manga_form', [
            'title' => 'Додати мангу',
            'action' => 'create'
        ]);
    }
}