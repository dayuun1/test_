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

        ob_start();
        if (isset($this->routes[$method][$path])) {
            $result = $this->executeCallback($this->routes[$method][$path]);
            $content = ob_get_clean();

            if (is_string($result) && strlen($result) > 0) {
                $content = $result;
            }

            $statusCode = http_response_code();
            header_remove('Cache-Control');
            HttpHelper::applyCacheHeaders($statusCode);

            echo $content;
            return null;
        }

        if (isset($this->routes[$method])) {
            foreach ($this->routes[$method] as $route => $callback) {
                $pattern = $this->convertToRegex($route);
                if (preg_match($pattern, $path, $matches)) {
                    array_shift($matches);

                    $result = $this->executeCallback($callback, $matches);
                    $content = ob_get_clean();

                    if (is_string($result) && strlen($result) > 0) {
                        $content = $result;
                    }

                    $statusCode = http_response_code();
                    header_remove('Cache-Control');
                    HttpHelper::applyCacheHeaders($statusCode);
                    echo $content;
                    return null;
                }
            }
        }

        ob_end_clean();

        $statusCode = 404;
        http_response_code($statusCode);

        header_remove('Cache-Control');
        HttpHelper::applyCacheHeaders($statusCode);
        $con = new ChapterController();
        $content = $con->render('errors/404');
        echo $content;
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