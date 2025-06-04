<?php

session_start();

require_once 'core/Autoloader.php';
Autoloader::register();

$router = new Router();

// Підключаємо маршрути
require_once 'config/routes.php';

$router = new Router();

$router->addRoute('GET', '/', 'HomeController', 'index');
$router->addRoute('GET', '/manga', 'MangaController', 'index');
$router->addRoute('GET', '/manga/show', 'MangaController', 'show');
$router->addRoute('GET', '/search', 'MangaController', 'search');

$router->addRoute('GET', '/admin', 'AdminController', 'index');
$router->addRoute('GET', '/admin/manga', 'AdminController', 'manageManga');
$router->addRoute('GET', '/admin/manga/create', 'AdminController', 'createManga');
$router->addRoute('POST', '/admin/manga/create', 'AdminController', 'createManga');

try {
    $router->dispatch();
} catch (Exception $e) {
    http_response_code(500);
    echo "Internal Server Error: " . $e->getMessage();
}
?>