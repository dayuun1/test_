<?php
class CharacterController extends Controller {
    private $characterModel;
    private $mangaModel;

    public function __construct() {
        parent::__construct();
        $this->characterModel = new Character();
        $this->mangaModel = new Manga();
    }

    public function index() {
        $characters = $this->characterModel->getAllWithManga();

        echo $this->render('characters/index', [
            'characters' => $characters,
            'title' => 'Персонажі манги'
        ]);
    }

    public function show($id) {
        $character = $this->characterModel->find($id);

        if (!$character) {
            http_response_code(404);
            echo $this->render('errors/404');
            return;
        }

        $manga = null;
        if ($character['manga_id']) {
            $manga = $this->mangaModel->find($character['manga_id']);
        }

        echo $this->render('characters/show', [
            'character' => $character,
            'manga' => $manga,
            'title' => $character['name']
        ]);
    }

    public function create() {
        $this->requireAuth();
        $this->requireRole('translator');

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'name' => $_POST['name'],
                'description' => $_POST['description'] ?? '',
                'manga_id' => $_POST['manga_id'] ?? null
            ];

            // Обробка завантаження зображення
            if (isset($_FILES['image']) && $_FILES['image']['error'] === 0) {
                $data['image'] = $this->uploadImage($_FILES['image']);
            }

            $this->characterModel->create($data);
            $this->redirect('/characters');
        }

        $allManga = $this->mangaModel->findAll();
        echo $this->render('characters/create', [
            'allManga' => $allManga,
            'title' => 'Додати персонажа'
        ]);
    }

    private function uploadImage($file) {
        $uploadDir = 'public/uploads/characters/';
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