<?php

class AccountRegisterController extends BaseController
{
    public function index()
    {
        $data = [];

        $data['action'] = $this->url->link('account/register/submit');
        $data['login'] = $this->url->link('account/login');

        $head_settings = ['page_title' => 'Register'];
        $data['head'] = $this->loadController('common/head', $head_settings);
        $data['footer'] = $this->loadController('common/footer');
        $data['notification'] = $this->loadController('common/notification');
        return $this->loadView('account/register.php', $data);
    }

    public function submit()
    {
        $this->notification->set('success', 'Not implemented');
        $this->response->redirect('account/register');
    }
}