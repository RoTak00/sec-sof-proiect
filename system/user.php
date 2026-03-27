<?php

class User
{
    private $registry = [];

    private $user_id;
    private $email;
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

        }
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
        $query = $this->db->query($sql, [$id]);
        return $query->row_array();
    }
}