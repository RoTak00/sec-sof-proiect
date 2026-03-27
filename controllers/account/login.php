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
        if ($this->request->server['REQUEST_METHOD'] != 'POST') {
            $this->response->redirect('account/login');
            return;
        }

        // Validate
        if (empty($this->request->post['email'])) {
            $this->notification->set('error', 'Email is required');
            $this->response->redirect('account/login');
            return;
        }

        if (empty($this->request->post['password'])) {
            $this->notification->set('error', 'Password is required');
            $this->response->redirect('account/login');
            return;
        }

        $this->loadModel('account/user');

        if (!$this->user->login($this->request->post['email'], $this->request->post['password'])) {
            $this->notification->set('error', 'Invalid email or password');
            $this->response->redirect('account/login');
            return;
        }

        $this->notification->set('success', 'Logged in!');

        $this->response->redirect('common/home');
    }
}