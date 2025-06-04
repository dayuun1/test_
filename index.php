<?php

session_start();

require_once 'core/Autoloader.php';
Autoloader::register();

$router = new Router();

require_once 'config/routes.php';

try {
    $router->dispatch();
} catch (Exception $e) {
    http_response_code(500);
    echo "Internal Server Error: " . $e->getMessage();
}