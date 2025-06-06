<?php
class Auth {
    public static function attempt($username, $password) {
        $userModel = new User();
        $user = $userModel->findByUsername($username);

        if ($user && password_verify($password, $user['password_hash'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_role'] = $user['role'];
            return true;
        }
        return false;
    }

    public static function check() {
        return isset($_SESSION['user_id']);
    }

    public static function user() {
        if (self::check()) {
            $userModel = new User();
            return $userModel->find($_SESSION['user_id']);
        }
        return null;
    }

    public static function hasRole($role) {
        return isset($_SESSION['user_role']) && $_SESSION['user_role'] === $role;
    }

    public static function logout() {
        unset($_SESSION['user_id']);
        unset($_SESSION['user_role']);
        session_destroy();
    }
}