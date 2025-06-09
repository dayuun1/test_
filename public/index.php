<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();

require_once __DIR__ . '/../core/Autoloader.php';
Autoloader::register();

$router = new Router();

$router->get('/', 'MangaController@index');

// Манга
$router->get('/manga', 'MangaController@index');
$router->get('/manga/create', 'MangaController@create');
$router->post('/manga/create', 'MangaController@create');
$router->get('/manga/{id}', 'MangaController@show');
$router->get('/manga/edit/{id}', 'MangaController@edit');
$router->post('/manga/edit/{id}', 'MangaController@edit');

$router->get('/api/manga/popular', 'MangaController@apiPopular');
$router->get('/api/manga/recent', 'MangaController@apiRecent');
// Розділи
$router->get('/manga/{mangaId}/chapter/{number}', 'ChapterController@show');
$router->get('/manga/{mangaId}/chapter/{number}/pdf', 'ChapterController@servePdf');
$router->get('/manga/{id}/upload', 'ChapterController@upload');
$router->post('/manga/{id}/upload', 'ChapterController@upload');
$router->get('/manga/{mangaId}/chapter/edit/{id}', 'ChapterController@edit');
$router->post('/manga/{mangaId}/chapter/edit/{id}', 'ChapterController@edit');

// Встановлення рейтингу (POST запит)
$router->post('/manga/{mangaId}/set-rating', 'MangaController@setRating');
$router->post('/manga/{mangaId}/add-comment', 'MangaController@addComment');
$router->post('/comments/{commentId}/delete', 'MangaController@deleteComment');

$router->get('/manga/{mangaId}/external-rating', 'MangaController@addExternalRating');
$router->post('/manga/{mangaId}/external-rating', 'MangaController@addExternalRating');

// Жанри
$router->get('/genres', 'GenreController@index');
$router->get('/genres/{id}', 'GenreController@show');
$router->get('/genres/edit/{id}', 'GenreController@edit');
$router->post('/genres/edit/{id}', 'GenreController@edit');
$router->get('/genres/create', 'GenreController@create');
$router->post('/genres/create', 'GenreController@create');
// Персонажі
$router->get('/characters', 'CharacterController@index');
$router->get('/characters/{id}', 'CharacterController@show');
$router->get('/characters/create', 'CharacterController@create');
$router->post('/characters/create', 'CharacterController@create');
$router->get('/characters/edit/{id}', 'CharacterController@edit');
$router->post('/characters/edit/{id}', 'CharacterController@edit');

// Новини
$router->get('/news', 'NewsController@index');
$router->get('/news/{id}', 'NewsController@show');
$router->get('/news/create', 'NewsController@create');
$router->post('/news/create', 'NewsController@create');
$router->get('/news/edit/{id}', 'NewsController@edit');
$router->post('/news/edit/{id}', 'NewsController@edit');

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
$router->get('/admin/manga', 'AdminController@manga');
$router->post('/manga/{id}/delete', 'AdminController@deleteManga');
$router->post('/users/{id}/delete', 'AdminController@deleteUser');
$router->get('/admin/news', 'AdminController@news');
$router->post('/admin/news/{id}/delete', 'AdminController@deleteNews');
$router->post('/admin/teams/{id}/delete', 'AdminController@deleteTeam');
$router->get('/admin/teams', 'AdminController@teams');
$router->get('/admin/characters', 'AdminController@characters');
$router->post('/admin/characters/{id}/delete', 'AdminController@deleteCharacter');
$router->get('/admin/genres', 'AdminController@genres');
$router->post('/admin/genres/{id}/delete', 'AdminController@deleteGenre');

$router->get('/teams', 'TeamController@index');
$router->get('/teams/create', 'TeamController@create');
$router->post('/teams/create', 'TeamController@create');
$router->get('/teams/{id}', 'TeamController@show');
$router->get('/teams/{id}/add-manga', 'TeamController@addMangaForm');
$router->post('/teams/{id}/add-manga', 'TeamController@addManga');
$router->get('/teams/{id}/add-member', 'TeamController@addMemberForm');
$router->post('/teams/{id}/add-member', 'TeamController@addMember');
$router->get('/teams/edit/{id}', 'TeamController@edit');
$router->post('/teams/edit/{id}', 'TeamController@edit');

$router->get('/search', 'SearchController@index');

try {
    $router->dispatch();
} catch (Exception $e) {
    http_response_code(500);
    echo "Internal Server Error: " . $e->getMessage();
}