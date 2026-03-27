<?php

class AccountForgotController extends BaseController
{
    public function index()
    {
        $data = [];

        $data['action'] = $this->url->link('account/forgot/submit');
        $data['back'] = $this->url->link('account/login');

        $head_settings = ['page_title' => 'Forgotten Password'];
        $data['head'] = $this->loadController('common/head', $head_settings);
        $data['footer'] = $this->loadController('common/footer');
        $data['notification'] = $this->loadController('common/notification');
        return $this->loadView('account/forgot.php', $data);
    }

    public function submit()
    {
        $this->notification->set('success', 'Not implemented');
        $this->response->redirect('account/forgot');
    }
}