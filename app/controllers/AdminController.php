<?php
class AdminController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->requireAuth();
        $this->requireRole('admin');
    }

    public function dashboard()
    {
        $stats = [
            'total_manga' => (new Manga())->countAll('manga'),
            'total_users' => (new User())->countAll('users'),
            'total_chapters' => (new Chapter())->countAll('chapters'),
            'total_news' => (new News())->countAll('news'),
            'total_characters' => (new Character())->countAll('characters'),
            'recent_users' => (new User())->findAll([], 5, 0),
            'total_teams' => (new Team())->countAll('teams')
        ];

        echo $this->render('admin/dashboard', [
            'stats' => $stats,
            'title' => 'Панель адміністратора'
        ]);
    }

    public function users()
    {
        $users = (new User())->findAll();

        echo $this->render('admin/users', [
            'users' => $users,
            'title' => 'Управління користувачами'
        ]);
    }

    public function updateUserRole($userId)
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $userModel = new User();
            $userModel->update($userId, ['role' => $_POST['role']]);
            $this->redirect('/admin/users');
        }
    }

    public function deleteUser($userId)
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $userModel = new User();
            $userModel->delete($userId);
            $this->redirect('/admin/users');
        }
    }

    public function deleteManga($mangaId)
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $mangaModel = new Manga();
            $mangaModel->delete($mangaId);
            $this->redirect('/admin/manga');
        }
    }

    public function manga()
    {
        $mangaModel = new Manga();
        $chapterModel = new Chapter();

        $mangaList = $mangaModel->findAllWithGenres();
        $chapterCounts = $chapterModel->countAllByManga();

        foreach ($mangaList as &$manga) {
            $manga['chapters_count'] = $chapterCounts[$manga['id']] ?? 0;
        }

        echo $this->render('admin/manga', [
            'mangaList' => $mangaList,
            'title' => 'Керування мангою'
        ]);
    }

    public function news()
    {
        $newsModel = new News();
        $newsList = $newsModel->getAllWithAuthor();

        echo $this->render('admin/news', [
            'newsList' => $newsList,
            'title' => 'Керування новинами'
        ]);
    }

    public function deleteNews($newsId)
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $newsModel = new News();
            $newsModel->delete($newsId);
            $this->redirect('/admin/news');
        }
    }

    public function characters()
    {
        $characterModel = new Character();
        $charactersList = $characterModel->getAllWithManga();

        echo $this->render('admin/characters', [
            'charactersList' => $charactersList,
            'title' => 'Керування персонажами'
        ]);
    }

    public function deleteCharacter($characterId)
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $characterModel = new Character();
            $characterModel->delete($characterId);
            $this->redirect('/admin/characters');
        }
    }

    public function teams()
    {
        $teamModel = new Team();
        $teamsList = $teamModel->getAllWithMembers();

        echo $this->render('admin/teams', [
            'teamsList' => $teamsList,
            'title' => 'Керування командами'
        ]);
    }

    public function deleteTeam($teamId)
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $teamModel = new Team();
            $teamModel->delete($teamId);
            $this->redirect('/admin/teams');
        }
    }
    public function genres()
    {
        $genreModel = new Genre();
        $genresList = $genreModel->getAll();

        echo $this->render('admin/genres', [
            'genresList' => $genresList,
            'title' => 'Керування жанрами'
        ]);
    }

    public function deleteGenre($genreId)
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $teamModel = new Genre();
            $teamModel->delete($genreId);
            $this->redirect('/admin/genres');
        }
    }

}