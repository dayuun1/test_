<?php

require_once 'config/database.php';
require_once 'core/Router.php';
require_once 'core/Controller.php';
require_once 'core/Model.php';
require_once 'models/User.php';
require_once 'models/Manga.php';

$router = new Router();

$router->addRoute('GET', '/', 'HomeController', 'index');
$router->addRoute('GET', '/manga', 'MangaController', 'index');
$router->addRoute('GET', '/manga/show', 'MangaController', 'show');
$router->addRoute('GET', '/search', 'MangaController', 'search');

$router->addRoute('GET', '/admin', 'AdminController', 'index');
$router->addRoute('GET', '/admin/manga', 'AdminController', 'manageManga');
$router->addRoute('GET', '/admin/manga/create', 'AdminController', 'createManga');
$router->addRoute('POST', '/admin/manga/create', 'AdminController', 'createManga');

$router->dispatch();
