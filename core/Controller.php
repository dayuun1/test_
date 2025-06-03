<?php
abstract class Controller {
    protected $db;

    public function __construct() {
        $database = new Database();
        $this->db = $database->connect();
        session_start();
    }

    protected function render($view, $data = []) {
        extract($data);

        ob_start();
        require_once "views/{$view}.php";
        $content = ob_get_clean();

        // Буферизація залежно від статус-кодів
        $status = http_response_code();
        if ($status === 200) {
            // Кешуємо успішні відповіді
            $cacheKey = md5($_SERVER['REQUEST_URI']);
            file_put_contents("cache/{$cacheKey}.html", $content);
        }

        echo $content;
    }

    protected function renderJSON($data, $status = 200) {
        http_response_code($status);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
        exit;
    }

    protected function isAdmin() {
        return isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin';
    }

    protected function requireAuth() {
        if (!isset($_SESSION['user_id'])) {
            header('Location: /login');
            exit;
        }
    }

    protected function requireAdmin() {
        if (!$this->isAdmin()) {
            http_response_code(403);
            $this->render('errors/403');
            exit;
        }
    }
}