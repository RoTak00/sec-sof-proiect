<?php

class CommonHomeController extends BaseController
{

    public function index()
    {
        if (!$this->user->loggedIn()) {
            $this->response->redirect('account/login');
            return;
        }
        $data = [];

        $data['email'] = $this->user->email;

        $data['ticket_action'] = $this->url->link('tickets/ticket/submit');


        $data['navbar'] = $this->loadController('common/navbar', ['active' => 'home']);
        $data['notification'] = $this->loadController('common/notification');
        $data['footer'] = $this->loadController('common/footer');
        $head_settings = ['page_title' => 'Home'];
        $data['head'] = $this->loadController('common/head', $head_settings);
        return $this->loadView('common/home.php', $data);
    }
}