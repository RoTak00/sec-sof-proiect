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
        $this->db->query("UPDATE users SET password = ? WHERE user_id = ?", 'si', [password_hash($password, PASSWORD_BCRYPT), $id]);
    }

    public function edit($user_id, $data)
    {
        $fields = [];

        if (isset($data['email'])) {
            $fields[] = "email = '" . $this->db->escape($data['email']) . "'";
        }

        if (isset($data['password'])) {
            $fields[] = "password = '" . $this->db->escape(password_hash($data['password'], PASSWORD_BCRYPT)) . "'";
        }

        if (isset($data['role'])) {
            $fields[] = "role = '" . $this->db->escape($data['role']) . "'";
        }

        if (!$fields) {
            return;
        }

        $sql = "UPDATE users SET " . implode(', ', $fields) . " WHERE user_id = '" . (int) $user_id . "'";

        $this->db->query($sql);
    }

    public function getUsers()
    {
        $query = $this->db->query("SELECT * FROM users ORDER BY user_id ASC");
        return $query->rows;
    }
}