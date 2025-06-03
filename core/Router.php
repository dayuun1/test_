<?php
class Router {
    private $routes = [];

    public function addRoute($method, $path, $controller, $action) {
        $this->routes[] = [
            'method' => $method,
            'path' => $path,
            'controller' => $controller,
            'action' => $action
        ];
    }

    public function dispatch() {
        $method = $_SERVER['REQUEST_METHOD'];
        $path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $path = rtrim($path, '/') ?: '/';

        foreach ($this->routes as $route) {
            if ($route['method'] === $method && $this->matchPath($route['path'], $path)) {
                $controllerName = $route['controller'];
                $action = $route['action'];

                require_once "controllers/{$controllerName}.php";
                $controller = new $controllerName();

                if (method_exists($controller, $action)) {
                    $controller->$action();
                    return;
                }
            }
        }

        http_response_code(404);
        echo "Page not found";
    }

    private function matchPath($routePath, $requestPath) {
        return $routePath === $requestPath ||
            preg_match('#^' . str_replace('*', '.*', $routePath) . '$#', $requestPath);
    }
}