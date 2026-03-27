<?php

class AccountLoginController extends BaseController
{
    public function index()
    {
        $data = [];

        $data['action'] = $this->url->link('account/login/submit');
        $data['register'] = $this->url->link('account/register');
        $data['forgot'] = $this->url->link('account/forgot');


        $head_settings = ['page_title' => 'Login'];
        $data['head'] = $this->loadController('common/head', $head_settings);
        $data['footer'] = $this->loadController('common/footer');
        $data['notification'] = $this->loadController('common/notification');
        return $this->loadView('account/login.php', $data);
    }

    public function submit()
    {
        $this->notification->set('success', 'Not implemented');
        $this->response->redirect('account/login');
    }
}