<?php
class ForumController extends Controller {
    private $categoryModel;
    private $topicModel;
    private $postModel;

    public function __construct() {
        parent::__construct();
        $this->categoryModel = new ForumCategory();
        $this->topicModel = new ForumTopic();
        $this->postModel = new ForumPost();
    }

    public function index() {
        $categories = $this->categoryModel->findAll();

        echo $this->render('forum/index', [
            'categories' => $categories,
            'title' => 'Форум'
        ]);
    }

    public function category($id) {
        $category = $this->categoryModel->find($id);
        if (!$category) {
            http_response_code(404);
            echo $this->render('errors/404');
            return;
        }

        $topics = $this->topicModel->findAll(['category_id' => $id]);

        echo $this->render('forum/category', [
            'category' => $category,
            'topics' => $topics,
            'title' => $category['name']
        ]);
    }

    public function topic($id) {
        $topic = $this->topicModel->find($id);
        if (!$topic) {
            http_response_code(404);
            echo $this->render('errors/404');
            return;
        }

        $posts = $this->postModel->findAll(['topic_id' => $id]);
        $this->topicModel->incrementViews($id);

        echo $this->render('forum/topic', [
            'topic' => $topic,
            'posts' => $posts,
            'title' => $topic['title']
        ]);
    }

    public function createTopic($categoryId) {
        $this->requireAuth();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $topicData = [
                'category_id' => $categoryId,
                'title' => $_POST['title'],
                'author_id' => Auth::user()['id']
            ];

            $topicId = $this->topicModel->create($topicData);

            $postData = [
                'topic_id' => $topicId,
                'author_id' => Auth::user()['id'],
                'content' => $_POST['content']
            ];

            $this->postModel->create($postData);
            $this->redirect('/forum/topic/' . $topicId);
        }

        $category = $this->categoryModel->find($categoryId);
        echo $this->render('forum/create-topic', ['category' => $category]);
    }
}


