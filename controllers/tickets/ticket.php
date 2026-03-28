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

        $data['tickets_link'] = $this->url->link('tickets/ticket');

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

        $this->audit->add('TICKET_VIEW', 'ticket', $ticket_id);

        $data = [];
        $data['ticket'] = $ticket;

        $data['tickets'] = $this->url->link('tickets/ticket');

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
}