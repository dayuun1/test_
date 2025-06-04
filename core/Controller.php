<?php
abstract class Controller {
    protected $view;

    public function __construct() {
        $this->view = new View();
    }

    protected function render($template, $data = []) {
        return $this->view->render($template, $data);
    }

    protected function redirect($url) {
        header("Location: $url");
        exit;
    }

    protected function json($data, $statusCode = 200) {
        http_response_code($statusCode);
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }

    protected function requireAuth() {
        if (!Auth::check()) {
            $this->redirect('/login');
        }
    }

    protected function requireRole($role) {
        if (!Auth::hasRole($role)) {
            http_response_code(403);
            die('Access denied');
        }
    }
}
