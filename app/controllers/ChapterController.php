<?php
class ChapterController extends Controller {
    private $chapterModel;
    private $mangaModel;

    public function __construct() {
        parent::__construct();
        $this->chapterModel = new Chapter();
        $this->mangaModel = new Manga();
    }

    // Перегляд розділу
    public function show($mangaSlug, $chapterNumber) {
        $manga = $this->mangaModel->findBySlug($mangaSlug);
        if (!$manga) {
            http_response_code(404);
            echo $this->render('errors/404');
            return;
        }

        $chapter = $this->chapterModel->findByMangaAndNumber($manga['id'], $chapterNumber);
        if (!$chapter) {
            http_response_code(404);
            echo $this->render('errors/404');
            return;
        }

        // Перевіряємо авторизацію для приватного контенту
        if (!Auth::check()) {
            $this->redirect('/login');
        }

        $this->chapterModel->incrementViews($chapter['id']);

        echo $this->render('chapters/show', [
            'manga' => $manga,
            'chapter' => $chapter,
            'title' => $manga['title'] . ' - Розділ ' . $chapterNumber
        ]);
    }

    // Завантаження PDF розділу
    public function upload($mangaId) {
        $this->requireAuth();
        $this->requireRole('translator');

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'manga_id' => $mangaId,
                'chapter_number' => $_POST['chapter_number'],
                'title' => $_POST['title'],
                'uploaded_by' => Auth::user()['id']
            ];

            if (isset($_FILES['pdf']) && $_FILES['pdf']['error'] === 0) {
                $uploader = new ChapterUpload();
                $data['pdf_path'] = $uploader->upload($mangaId, $_POST['chapter_number'], $_FILES['pdf']);
                $data['pages_count'] = $this->getPdfPageCount($data['pdf_path']);
            }

            $this->chapterModel->create($data);
            $manga = $this->mangaModel->find($mangaId);
            $this->redirect('/manga/' . $manga['slug']);
        }

        $manga = $this->mangaModel->find($mangaId);
        echo $this->render('chapters/upload', ['manga' => $manga]);
    }

    // Віддача PDF файлу з контролем доступу
    public function servePdf($mangaSlug, $chapterNumber) {
        if (!Auth::check()) {
            http_response_code(401);
            die('Unauthorized');
        }

        $manga = $this->mangaModel->findBySlug($mangaSlug);
        $chapter = $this->chapterModel->findByMangaAndNumber($manga['id'], $chapterNumber);

        if (!$chapter) {
            http_response_code(404);
            die('Chapter not found');
        }

        $filepath = $chapter['pdf_path'];
        if (!file_exists($filepath)) {
            http_response_code(404);
            die('File not found');
        }

        header('Content-Type: application/pdf');
        header('Content-Disposition: inline; filename="' . basename($filepath) . '"');
        header('Content-Length: ' . filesize($filepath));

        readfile($filepath);
    }

    private function getPdfPageCount($filepath) {
        // Простий спосіб підрахунку сторінок PDF
        $content = file_get_contents($filepath);
        $pageCount = preg_match_all('/\/Page\W/', $content);
        return $pageCount ?: 1;
    }
}
