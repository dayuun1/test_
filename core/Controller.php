<?php

abstract class Controller {

    protected $view;

    public function __construct() {
        $this->view = new View();
    }

    public function render($template, $data = []) {
        extract($data);
        ob_start();
        require __DIR__ . '/../app/views/' . $template . '.php';
        return ob_get_clean();
    }

    protected function redirect($url, $statusCode = 302) {
        header_remove('Cache-Control');
        http_response_code($statusCode);
        HttpHelper::applyCacheHeaders($statusCode);
        header("Location: $url");
        exit;
    }

    protected function json($data, $statusCode = 200) {
        http_response_code($statusCode);
        header_remove('Cache-Control');
        HttpHelper::applyCacheHeaders($statusCode);
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }

    protected function requireAuth() {
        if (!Auth::check()) {
            $this->redirect('/login');
        }
    }

    protected function requireRole($roles) {
        if (is_string($roles)) {
            $roles = [$roles];
        }

        foreach ($roles as $role) {
            if (Auth::hasRole($role)) {
                return;
            }
        }

        http_response_code(403);
        echo $this->render('errors/403');
    }
}
