<?php
class NewsController extends Controller {
    private $newsModel;

    public function __construct() {
        parent::__construct();
        $this->newsModel = new News();
    }

    public function index() {
        $page = $_GET['page'] ?? 1;
        $limit = 12;
        $offset = ($page - 1) * $limit;

        $news = $this->newsModel->getPublished($limit, $offset);
        $totalNews = $this->newsModel->countPublished();
        $totalPages = ceil($totalNews / $limit);

        echo $this->render('news/index', [
            'news' => $news,
            'currentPage' => $page,
            'totalPages' => $totalPages,
            'title' => 'Новини'
        ]);
    }

    public function show($id) {
        $article = $this->newsModel->find($id);

        if (!$article || !$article['is_published']) {
            http_response_code(404);
            echo $this->render('errors/404');
            return;
        }

        $this->newsModel->incrementViews($article['id']);

        echo $this->render('news/show', [
            'article' => $article,
            'title' => $article['title']
        ]);
    }

    public function create() {
        $this->requireAuth();
        $this->requireRole('admin');

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'title' => $_POST['title'],
                'content' => $_POST['content'],
                'author_id' => Auth::user()['id'],
                'is_published' => isset($_POST['is_published']) ? 1 : 0
            ];

            if (isset($_FILES['image']) && $_FILES['image']['error'] === 0) {
                $data['image'] = $this->uploadImage($_FILES['image']);
            }

            $this->newsModel->create($data);
            $this->redirect('/news');
        }

        echo $this->render('news/create', [
            'title' => 'Додати новину'
        ]);
    }

    public function edit($id) {
        $this->requireAuth();
        $this->requireRole('admin');

        $article = $this->newsModel->find($id);
        if (!$article) {
            http_response_code(404);
            echo $this->render('errors/404');
            return;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'title' => $_POST['title'],
                'content' => $_POST['content'],
                'is_published' => isset($_POST['is_published']) ? 1 : 0
            ];

            // Обробка завантаження нового зображення
            if (isset($_FILES['image']) && $_FILES['image']['error'] === 0) {
                $data['image'] = $this->uploadImage($_FILES['image']);
            }

            $this->newsModel->update($id, $data);
            $this->redirect('/news/' . $id);
        }

        echo $this->render('news/edit', [
            'article' => $article,
            'title' => 'Редагувати новину'
        ]);
    }


    private function uploadImage($file) {
        $uploadDir = 'public/uploads/news/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        $allowedTypes = ['jpg', 'jpeg', 'png', 'webp'];
        $extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));

        if (!in_array($extension, $allowedTypes)) {
            throw new Exception('Дозволені тільки зображення: jpg, jpeg, png, webp');
        }

        $filename = uniqid() . '.' . $extension;
        $filepath = $uploadDir . $filename;

        if (move_uploaded_file($file['tmp_name'], $filepath)) {
            return $filename;
        }

        throw new Exception('Помилка завантаження зображення');
    }
}