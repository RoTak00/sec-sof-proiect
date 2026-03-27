<?php

class Audit
{
    private $registry = [];
    private $db = null;
    private $user = null;


    public function __construct($registry)
    {
        $this->registry = $registry;
        $this->db = $this->registry->db;
        $this->user = $this->registry->user;

    }

    public function add($action, $resource, $resource_id)
    {
        $ip_address = $_SERVER['REMOTE_ADDR'] ?? '';
        $user_id = $this->user->loggedIn();

        return $this->db->query(
            "INSERT INTO audit_logs (user_id, action, resource, resource_id, timestamp, ip_address)
         VALUES (?, ?, ?, ?, NOW(), ?)",
            "issis",
            [(int) $user_id ?: null, $action, $resource, (int) $resource_id, $ip_address]
        );
    }


}