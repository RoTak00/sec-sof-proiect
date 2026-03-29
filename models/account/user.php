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

        if (isset($data['is_verified'])) {
            $fields[] = "is_verified = '" . $this->db->escape($data['is_verified']) . "'";

            if ($data['is_verified'] === '1') {
                $fields[] = "verification_token_hash = NULL";
                $fields[] = "verification_token_expires_at = NULL";
            }

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

    public function add($email, $password)
    {
        $password_hash = password_hash($password, PASSWORD_BCRYPT);

        $result = $this->db->query(
            "INSERT INTO users (email, password, role, is_verified) VALUES (?, ?, ?, 0)",
            'sss',
            [$email, $password_hash, 'user']
        );

        return $result->insert_id;
    }

    public function setVerificationToken($user_id, $token_hash, $expires_at)
    {
        $this->db->query(
            "UPDATE users
         SET verification_token_hash = ?, verification_token_expires_at = ?
         WHERE user_id = ?",
            'ssi',
            [$token_hash, $expires_at, $user_id]
        );
    }

    public function getUserByVerificationToken($token_hash)
    {
        $result = $this->db->query(
            "SELECT *
         FROM users
         WHERE verification_token_hash = ?
           AND verification_token_expires_at >= NOW()
           AND is_verified = 0
         LIMIT 1",
            's',
            [$token_hash]
        );

        return $result->row;
    }

    public function verifyUser($user_id)
    {
        $this->db->query(
            "UPDATE users
         SET is_verified = 1,
             verification_token_hash = NULL,
             verification_token_expires_at = NULL
         WHERE user_id = ?",
            'i',
            [$user_id]
        );
    }
}