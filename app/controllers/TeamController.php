<?php
class TeamController extends Controller {
    private $teamModel;

    public function __construct() {
        parent::__construct();
        $this->requireAuth();
        $this->teamModel = new Team();
    }

    public function index() {
        $teams = $this->teamModel->getAllWithMembers();
        echo $this->render('teams/index', ['teams' => $teams]);
    }

    public function show($id) {
        $team = $this->teamModel->find($id);
        $members = $this->teamModel->getMembers($id);
        $accessibleManga = $this->teamModel->getAccessibleManga($id);

        echo $this->render('teams/show', [
            'team' => $team,
            'members' => $members,
            'accessibleManga' => $accessibleManga
        ]);
    }

    public function create() {
        $this->requireRole(['admin', 'translator']);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'name' => $_POST['name'],
                'description' => $_POST['description'],
            ];

            if (isset($_FILES['image']) && $_FILES['image']['error'] === 0) {
                $data['image'] = $this->uploadImage($_FILES['image']);
            }

            $teamId = $this->teamModel->create($data);
            $this->teamModel->addMember($teamId, Auth::user()['id']);

            $this->redirect('/teams/' . $teamId);
        }

        echo $this->render('teams/create');
    }

    private function uploadImage($file) {
        $dir = 'public/uploads/teams/';
        if (!is_dir($dir)) mkdir($dir, 0755, true);

        $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
        $filename = uniqid() . '.' . $ext;

        move_uploaded_file($file['tmp_name'], $dir . $filename);
        return $filename;
    }

    public function addMangaForm($teamId) {
        $team = $this->teamModel->find($teamId);
        $mangaModel = new Manga();

        $existing = array_column($this->teamModel->getAccessibleManga($teamId), 'id');
        $allManga = $mangaModel->getAll();
        $availableManga = array_filter($allManga, fn($m) => !in_array($m['id'], $existing));

        echo $this->render('teams/add_manga', [
            'team' => $team,
            'mangaList' => $availableManga
        ]);
    }

    public function addManga($teamId) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['manga_id'])) {
            $this->teamModel->addAccessibleManga($teamId, $_POST['manga_id']);
        }

        $this->redirect('/teams/' . $teamId);
    }

}