<?php

class TicketsTicketController extends BaseController
{
    public function index()
    {
        if (!$this->user->loggedIn()) {
            $this->response->redirect('account/login');
            return;
        }

        $this->loadModel('tickets/ticket');

        if ($this->user->isAdmin() || $this->user->isAnalyst()) {
            $tickets = $this->model_tickets_ticket->getTickets();
        } else {
            $tickets = $this->model_tickets_ticket->getTicketsByOwnerId($this->user->loggedIn());
        }

        $this->audit->add('TICKET_LIST', 'ticket', 0);

        $data = [];
        $data['tickets'] = $tickets;

        $data['navbar'] = $this->loadController('common/navbar', ['active' => 'tickets']);
        $data['notification'] = $this->loadController('common/notification');
        $data['footer'] = $this->loadController('common/footer');
        $head_settings = ['page_title' => 'Tickets'];
        $data['head'] = $this->loadController('common/head', $head_settings);

        return $this->loadView('ticket/list.php', $data);
    }
    public function view($setting)
    {
        if (!$this->user->loggedIn()) {
            $this->response->redirect('account/login');
            return;
        }


        if (!count($setting)) {
            $this->notification->set('error', 'Ticket not found');
            $this->response->redirect('common/home');
        }

        $ticket_id = (int) $setting[0];

        $this->loadModel('tickets/ticket');

        $ticket = $this->model_tickets_ticket->getTicketById($ticket_id);

        if (!$ticket) {
            $this->notification->set('error', 'Ticket not found');
            $this->response->redirect('common/home');
            return;
        }

        if ($ticket['owner_id'] != $this->user->loggedIn() && !($this->user->isAdmin() || $this->user->isAnalyst())) {
            $this->notification->set('error', 'Unauthorized');
            $this->response->redirect('common/home');
            return;
        }

        $this->audit->add('TICKET_VIEW', 'ticket', $ticket_id);

        $data = [];
        $data['ticket'] = $ticket;

        if ($this->user->isAdmin() || $this->user->isAnalyst()) {
            $data['ticket_edit_action'] = $this->url->link('tickets/ticket/update/' . $ticket_id);
        }

        $data['navbar'] = $this->loadController('common/navbar', ['active' => 'tickets']);
        $data['notification'] = $this->loadController('common/notification');
        $data['footer'] = $this->loadController('common/footer');
        $head_settings = ['page_title' => 'Tickets'];
        $data['head'] = $this->loadController('common/head', $head_settings);

        return $this->loadView('ticket/view.php', $data);
    }

    public function submit()
    {
        if ($this->request->server['REQUEST_METHOD'] != 'POST') {
            $this->response->redirect('common/home');
            return;
        }

        if (!$this->user->loggedIn()) {
            $this->notification->set('error', 'You must be logged in to create a ticket');
            $this->response->redirect('account/login');
            return;
        }

        if (empty($this->request->post['title'])) {
            $this->notification->set('error', 'Title is required');
            $this->response->redirect('common/home');
            return;
        }

        if (empty($this->request->post['description'])) {
            $this->notification->set('error', 'Description is required');
            $this->response->redirect('common/home');
            return;
        }

        if (empty($this->request->post['severity'])) {
            $this->notification->set('error', 'Severity is required');
            $this->response->redirect('common/home');
            return;
        }

        $allowed_severities = ['low', 'medium', 'high'];

        if (!in_array($this->request->post['severity'], $allowed_severities, true)) {
            $this->notification->set('error', 'Invalid severity');
            $this->response->redirect('common/home');
            return;
        }

        $this->loadModel('tickets/ticket');

        $ticket_id = $this->model_tickets_ticket->add(
            $this->request->post['title'],
            $this->request->post['description'],
            $this->request->post['severity']
        );

        $this->audit->add('TICKET_CREATE', 'ticket', $ticket_id);

        $this->notification->set('success', 'Ticket created successfully');

        $this->response->redirect('tickets/ticket/view/' . (int) $ticket_id);
    }

    public function update($setting)
    {
        if (!$this->user->loggedIn()) {
            $this->response->redirect('account/login');
            return;
        }

        if (!$this->user->isAdmin() || !$this->user->isAnalyst()) {
            $this->notification->set('error', 'Unauthorized');
            $this->response->redirect('common/home');
            return;
        }

        if ($this->request->server['REQUEST_METHOD'] != 'POST') {
            $this->response->redirect('tickets/ticket');
            return;
        }

        if (!count($setting)) {
            $this->notification->set('error', 'Ticket not found');
            $this->response->redirect('tickets/ticket');
            return;
        }

        $ticket_id = (int) $setting[0];

        $allowed_statuses = ['open', 'in_progress', 'resolved'];

        if (empty($this->request->post['status']) || !in_array($this->request->post['status'], $allowed_statuses, true)) {
            $this->notification->set('error', 'Invalid status');
            $this->response->redirect('tickets/ticket/view/' . $ticket_id);
            return;
        }

        $status = $this->request->post['status'];
        $message = isset($this->request->post['message']) ? trim($this->request->post['message']) : '';

        $this->loadModel('tickets/ticket');
        $ticket = $this->model_tickets_ticket->getTicketById($ticket_id);

        if (!$ticket) {
            $this->notification->set('error', 'Ticket not found');
            $this->response->redirect('tickets/ticket');
            return;
        }

        $this->model_tickets_ticket->updateStatus($ticket_id, $status);

        $this->loadModel('account/user');
        $ticket_owner = $this->model_account_user->getUserById($ticket['owner_id']);

        if ($message !== '' && !empty($ticket_owner['email'])) {
            $subject = 'Update for your ticket: ' . $ticket['title'];

            $body = "Hello,\n\n";
            $body .= "Your ticket status has been updated to: " . $status . "\n\n";
            $body .= $message . "\n\n";
            $body .= "Ticket ID: " . $ticket_id . "\n";
            $body .= "Title: " . $ticket['title'] . "\n";

            $this->mail->send($ticket_owner['email'], $subject, $body);
        }

        $this->audit->add('TICKET_UPDATE', 'ticket', $ticket_id);

        $this->notification->set('success', 'Ticket updated successfully');

        $this->response->redirect('tickets/ticket/view/' . $ticket_id);
    }
}