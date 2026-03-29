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
        if ($this->request->server['REQUEST_METHOD'] != 'POST') {
            $this->response->redirect('account/register');
            return;
        }

        // Validate
        if (empty($this->request->post['email'])) {
            $this->notification->set('error', 'Email is required');
            $this->response->redirect('account/register');
            return;
        }

        if (empty($this->request->post['password'])) {
            $this->notification->set('error', 'Password is required');
            $this->response->redirect('account/register');
            return;
        }

        if (strlen($this->request->post['password']) < 10) {
            $this->notification->set('error', 'Password must be at least 10 characters long');
            $this->response->redirect('account/register');
            return;
        }

        $this->loadModel('account/user');

        $existing_user = $this->model_account_user->getUserByEmail($this->request->post['email']);

        if ($existing_user) {

            $this->audit->add('USER_REGISTER', $this->request->post['email'], 0);
            $this->notification->set('error', 'User already exists');
            $this->response->redirect('account/register');
            return;
        }


        $this->user->register($this->request->post['email'], $this->request->post['password']);
        $this->audit->add('USER_REGISTER', $this->request->post['email'], $this->user->loggedIn());
        $this->mail->send($this->request->post['email'], 'Welcome to AuthX', 'Welcome to AuthX!');
        $this->notification->set('success', 'Account created! Logged in!');

        $this->response->redirect('common/home');
    }
}