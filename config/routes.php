<?php

$router->get('/', 'HomeController@index');

// Манга
$router->get('/manga', 'MangaController@index');
$router->get('/manga/create', 'MangaController@create');
$router->post('/manga/create', 'MangaController@create');
$router->get('/manga/{slug}', 'MangaController@show');

// Розділи
$router->get('/manga/{slug}/chapter/{number}', 'ChapterController@show');
$router->get('/manga/{slug}/chapter/{number}/pdf', 'ChapterController@servePdf');
$router->get('/manga/{id}/upload', 'ChapterController@upload');
$router->post('/manga/{id}/upload', 'ChapterController@upload');

// Жанри
$router->get('/genres', 'GenreController@index');
$router->get('/genres/{slug}', 'GenreController@show');

// Персонажі
$router->get('/characters', 'CharacterController@index');
$router->get('/characters/{id}', 'CharacterController@show');

// Новини
$router->get('/news', 'NewsController@index');
$router->get('/news/{slug}', 'NewsController@show');

// Форум
$router->get('/forum', 'ForumController@index');
$router->get('/forum/category/{id}', 'ForumController@category');
$router->get('/forum/topic/{id}', 'ForumController@topic');
$router->get('/forum/category/{id}/create-topic', 'ForumController@createTopic');
$router->post('/forum/category/{id}/create-topic', 'ForumController@createTopic');

// Аутентифікація
$router->get('/login', 'AuthController@showLogin');
$router->post('/login', 'AuthController@login');
$router->get('/register', 'AuthController@showRegister');
$router->post('/register', 'AuthController@register');
$router->post('/logout', 'AuthController@logout');

// Адміністрування
$router->get('/admin', 'AdminController@dashboard');
$router->get('/admin/users', 'AdminController@users');
$router->post('/admin/users/{id}/role', 'AdminController@updateUserRole');
$router->post('/admin/cache/clear', 'AdminController@clearCache');