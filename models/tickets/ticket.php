<?php

class TicketsTicketModel extends BaseModel
{
    public function add($title, $description, $severity)
    {
        $user_id = $this->user->loggedIn();
        $result = $this->db->query(
            "INSERT INTO tickets (owner_id, title, description, severity, created_at)
             VALUES (?, ?, ?, ?, NOW())",
            'isss',
            [$user_id, $title, $description, $severity]
        );

        return $result->insert_id;
    }

    public function updateStatus($ticket_id, $status)
    {
        $result = $this->db->query(
            "UPDATE tickets SET status = ?, updated_at = NOW() WHERE ticket_id = ?",
            'si',
            [$status, $ticket_id]
        );
    }

    public function getTicketById($ticket_id)
    {
        $result = $this->db->query(
            "SELECT * FROM tickets WHERE ticket_id = ?",
            'i',
            [$ticket_id]
        );

        return $result->row;
    }

    public function getTickets()
    {
        $result = $this->db->query(
            "SELECT * FROM tickets ORDER BY updated_at DESC, created_at DESC"
        );

        return $result->rows;
    }

    public function getTicketsByOwnerId($owner_id)
    {
        $result = $this->db->query(
            "SELECT * FROM tickets WHERE owner_id = ? ORDER BY updated_at DESC, created_at DESC",
            'i',
            [$owner_id]
        );

        return $result->rows;
    }
}