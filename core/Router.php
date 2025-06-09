<?php
class Router {
    private $routes = [];

    public function get($path, $callback) {
        $this->routes['GET'][$path] = $callback;
    }

    public function post($path, $callback) {
        $this->routes['POST'][$path] = $callback;
    }

    public function dispatch() {
        $method = $_SERVER['REQUEST_METHOD'];
        $path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

        if (isset($this->routes[$method][$path])) {
            return $this->executeCallback($this->routes[$method][$path]);
        }

        if (isset($this->routes[$method])) {
            foreach ($this->routes[$method] as $route => $callback) {
                $pattern = $this->convertToRegex($route);
                if (preg_match($pattern, $path, $matches)) {
                    array_shift($matches); // Видаляємо повний збіг
                    return $this->executeCallback($callback, $matches);
                }
            }
        }

        http_response_code(404);
        require '../app/views/errors/404.php';
    }

    private function convertToRegex($route) {
        $route = preg_replace('/\{([^}]+)\}/', '([^/]+)', $route);
        return '#^' . $route . '$#';
    }

    private function executeCallback($callback, $params = []) {
        if (is_string($callback)) {
            list($controller, $method) = explode('@', $callback);
            $controllerInstance = new $controller();
            return call_user_func_array([$controllerInstance, $method], $params);
        }

        return call_user_func_array($callback, $params);
    }
}