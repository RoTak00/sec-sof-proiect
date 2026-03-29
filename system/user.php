<?php

class User
{
    private $registry = [];

    private $user_id;
    public $email;
    public $role;
    private $db = null;

    public function loggedIn()
    {
        return $this->user_id;
    }

    public function __construct($registry)
    {
        $this->registry = $registry;
        $this->db = $this->registry->db;
        if (!empty($this->session->data['user_id'])) {
            $user = $this->getUser($this->session->data['user_id']);

            $this->user_id = $user['user_id'];
            $this->email = $user['email'];
            $this->role = $user['role'];

        }
    }

    public function isAdmin()
    {
        return $this->role == 'admin';
    }

    public function isAnalyst()
    {
        return $this->role == 'analyst';
    }

    public function register($email, $password)
    {
        $password_hash = password_hash($password, PASSWORD_BCRYPT);

        $result = $this->db->query(
            "INSERT INTO users (email, password) VALUES (?, ?)",
            'ss',
            [$email, $password_hash]
        );

        return $result->insert_id;
    }

    public function login($email, $password)
    {
        $sql = "SELECT * FROM users WHERE email = ?";
        $result = $this->db->query($sql, 's', [$email]);

        if ($result->num_rows > 0) {
            $user = $result->row;

            if (password_verify($password, $user['password'])) {

                if (!$user['is_verified']) {
                    return false;
                }
                $this->session->data['user_id'] = $user['user_id'];
                $this->user_id = $user['user_id'];
                $this->email = $user['email'];
                $this->role = $user['role'];
                return true;
            }
        }

        return false;
    }

    public function logout()
    {
        $this->session->data['user_id'] = null;
        $this->user_id = null;
        $this->email = null;
    }


    public function __get($name)
    {
        // Fetch from the registry if it exists
        if (isset($this->registry->registry[$name])) {
            return $this->registry->registry[$name];
        }
        return null; // Or throw an error if you want strict behavior
    }

    public function __destruct()
    {
        //session_destroy();
    }

    private function getUser($id)
    {
        $sql = "SELECT * FROM users WHERE user_id = ?";
        $result = $this->db->query($sql, 'i', [$id]);
        return $result->row;
    }

}
