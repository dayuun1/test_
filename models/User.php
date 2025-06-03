<?php
class User extends Model {
    protected $table = 'users';

    public function authenticate($username, $password) {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE username = ?");
        $stmt->execute([$username]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password'])) {
            return $user;
        }

        return false;
    }

    public function register($username, $email, $password) {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        return $this->create([
            'username' => $username,
            'email' => $email,
            'password' => $hashedPassword,
            'role' => 'user',
            'created_at' => date('Y-m-d H:i:s')
        ]);
    }
}