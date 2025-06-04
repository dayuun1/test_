<?php
class AdminController extends Controller {
    public function __construct() {
        parent::__construct();
        $this->requireAuth();
        $this->requireRole('admin');
    }

    public function dashboard() {
        $stats = [
            'total_manga' => (new Manga())->count(),
            'total_users' => (new User())->count(),
            'total_chapters' => (new Chapter())->count(),
            'recent_users' => (new User())->findAll([], 5, 0)
        ];

        echo $this->render('admin/dashboard', [
            'stats' => $stats,
            'title' => 'Панель адміністратора'
        ]);
    }

    public function users() {
        $users = (new User())->findAll();

        echo $this->render('admin/users', [
            'users' => $users,
            'title' => 'Управління користувачами'
        ]);
    }

    public function updateUserRole($userId) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $userModel = new User();
            $userModel->update($userId, ['role' => $_POST['role']]);
            $this->redirect('/admin/users');
        }
    }

    public function clearCache() {
        $cache = new PageCache();
        $cache->clear();

        $this->json(['success' => true, 'message' => 'Кеш очищено']);
    }
}