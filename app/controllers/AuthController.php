<?php
class AuthController extends Controller {
    private $userModel;

    public function __construct() {
        parent::__construct();
        $this->userModel = new User();
    }

    public function showLogin() {
        if (Auth::check()) {
            $this->redirect('/');
        }

        echo $this->render('auth/login', [
            'title' => 'Вхід до системи'
        ]);
    }

    public function login() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = isset($_POST['username']) ? $_POST['username'] : '';
            $password = isset($_POST['password']) ? $_POST['password'] : '';

            if (Auth::attempt($username, $password)) {
                $this->redirect('/');
            } else {
                echo $this->render('auth/login', [
                    'error' => 'Невірний логін або пароль',
                    'title' => 'Вхід до системи'
                ]);
            }
        } else {
            $this->showLogin();
        }
    }

    public function showRegister() {
        if (Auth::check()) {
            $this->redirect('/');
        }

        echo $this->render('auth/register', [
            'title' => 'Реєстрація'
        ]);
    }

    public function register() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = isset($_POST['username']) ? $_POST['username'] : '';
            $email = isset($_POST['email']) ? $_POST['email'] : '';
            $password = isset($_POST['password']) ? $_POST['password'] : '';
            $confirmPassword = isset($_POST['confirm_password']) ? $_POST['confirm_password'] : '';

            // Валідація
            $errors = [];

            if (empty($username) || strlen($username) < 3) {
                $errors[] = 'Ім\'я користувача повинно містити мінімум 3 символи';
            }

            if ($this->userModel->findByUsername($username)) {
                $errors[] = 'Користувач з таким іменем вже існує';
            }

            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $errors[] = 'Невірний формат email';
            }

            if ($this->userModel->findByEmail($email)) {
                $errors[] = 'Користувач з таким email вже існує';
            }

            if (strlen($password) < 6) {
                $errors[] = 'Пароль повинен містити мінімум 6 символів';
            }

            if ($password !== $confirmPassword) {
                $errors[] = 'Паролі не співпадають';
            }

            if (empty($errors)) {
                $userData = [
                    'username' => $username,
                    'email' => $email,
                    'password' => $password,
                    'role' => 'reader'
                ];

                $userId = $this->userModel->createUser($userData);

                Auth::attempt($username, $password);
                $this->redirect('/');
            } else {
                echo $this->render('auth/register', [
                    'errors' => $errors,
                    'title' => 'Реєстрація'
                ]);
            }
        } else {
            $this->showRegister();
        }
    }

    public function logout() {
        Auth::logout();
        $this->redirect('/');
    }
}