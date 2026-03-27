<?php

class AccountUserModel extends BaseModel
{
    public function getUserByEmail($email)
    {
        $result = $this->db->query("SELECT * FROM users WHERE email = ?", 's', [$email]);

        return $result->row;
    }
}