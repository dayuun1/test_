<?php
class ChapterController extends Controller {
    private $chapterModel;
    private $mangaModel;

    public function __construct() {
        parent::__construct();
        $this->chapterModel = new Chapter();
        $this->mangaModel = new Manga();
    }

    public function show($mangaId, $chapterNumber) {
        $manga = $this->mangaModel->find($mangaId);
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

    public function upload($mangaId) {
        $user = Auth::user();
        $teamModel = new Team();

        if (!(Auth::hasRole('translator') && $teamModel->userHasAccessToManga($user['id'], $mangaId)) && !Auth::hasRole('admin')) {
            http_response_code(403);
            echo $this->render('errors/403');
            return;
        }

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
            $this->redirect('/manga/' . $manga['id']);
        }

        $manga = $this->mangaModel->find($mangaId);
        echo $this->render('chapters/upload', ['manga' => $manga]);
    }

    public function servePdf($mangaId, $chapterNumber) {
        if (!Auth::check()) {
            http_response_code(401);
            echo $this->render('errors/401');
        }

        $manga = $this->mangaModel->find($mangaId);
        if (!$manga) {
            http_response_code(404);
            echo $this->render('errors/404');
        }

        $chapter = $this->chapterModel->findByMangaAndNumber($manga['id'], $chapterNumber);
        if (!$chapter) {
            http_response_code(404);
            echo $this->render('errors/404');
        }

        $filepath = $chapter['pdf_path'];
        if (!file_exists($filepath)) {
            http_response_code(404);
            echo $this->render('errors/404');
        }

        header('Content-Type: application/pdf');
        header('Content-Disposition: inline; filename="' . basename($filepath) . '"');
        header('Content-Length: ' . filesize($filepath));

        readfile($filepath);
    }
    private function getPdfPageCount($filepath) {
        $content = file_get_contents($filepath);
        $pageCount = preg_match_all('/\/Page\W/', $content);
        return $pageCount ?: 1;
    }

    public function edit($id)
    {
        $this->requireAuth();
        $this->requireRole(['translator', 'admin']);

        $chapter = $this->chapterModel->find($id);
        if (!$chapter) {
            http_response_code(404);
            echo $this->render('errors/404');
            return;
        }

        $user = Auth::user();
        $teamModel = new Team();
        if (!(Auth::hasRole('admin') ||
            (Auth::hasRole('translator') && $teamModel->userHasAccessToManga($user['id'], $chapter['manga_id'])))) {
            http_response_code(403);
            echo $this->render('errors/403');
            return;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'chapter_number' => $_POST['chapter_number'],
                'title' => $_POST['title'],
            ];

            if (isset($_FILES['pdf']) && $_FILES['pdf']['error'] === 0) {
                $uploader = new ChapterUpload();
                $data['pdf_path'] = $uploader->upload($chapter['manga_id'], $_POST['chapter_number'], $_FILES['pdf']);
                $data['pages_count'] = $this->getPdfPageCount($data['pdf_path']);
            }

            $this->chapterModel->update($id, $data);
            $this->redirect('/manga/' . $chapter['manga_id']);
        }

        echo $this->render('chapters/edit', [
            'chapter' => $chapter,
            'title' => 'Редагувати розділ'
        ]);
    }
}
