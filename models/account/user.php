<?php

class AccountUserModel extends BaseModel
{
    public function getUserByEmail($email)
    {
        $result = $this->db->query("SELECT * FROM users WHERE email = ?", 's', [$email]);

        return $result->row;
    }

    public function getUserById($id)
    {
        $result = $this->db->query("SELECT * FROM users WHERE user_id = ?", 'i', [$id]);

        return $result->row;
    }

    public function updatePassword($id, $password)
    {
        $this->db->query("UPDATE users SET password = ? WHERE user_id = ?", 'si', [$password, $id]);
    }
}