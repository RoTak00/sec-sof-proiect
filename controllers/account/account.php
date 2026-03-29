<?php

class AccountAccountController extends BaseController
{
    public function index()
    {
        if (!$this->user->loggedIn()) {
            $this->response->redirect('account/login');
            return;
        }

        $this->loadModel('account/user');

        $user = $this->model_account_user->getUserById($this->user->loggedIn());

        if (!$user) {
            $this->notification->set('error', 'User not found');
            $this->response->redirect('common/home');
            return;
        }

        $data = [];
        $data['email'] = $user['email'];
        $data['action'] = $this->url->link('account/account/edit');

        $data['navbar'] = $this->loadController('common/navbar', ['active' => 'account']);
        $data['notification'] = $this->loadController('common/notification');
        $data['footer'] = $this->loadController('common/footer');
        $head_settings = ['page_title' => 'Account'];
        $data['head'] = $this->loadController('common/head', $head_settings);

        return $this->loadView('account/account.php', $data);
    }

    public function edit()
    {
        if (!$this->user->loggedIn()) {
            $this->response->redirect('account/login');
            return;
        }

        if ($this->request->server['REQUEST_METHOD'] !== 'POST') {
            $this->response->redirect('account/account');
            return;
        }

        $this->loadModel('account/user');

        $user = $this->model_account_user->getUserById($this->user->loggedIn());

        if (!$user) {
            $this->notification->set('error', 'User not found');
            $this->response->redirect('account/account');
            return;
        }

        $email = isset($this->request->post['email']) ? trim($this->request->post['email']) : '';
        $old_password = isset($this->request->post['old_password']) ? $this->request->post['old_password'] : '';
        $new_password = isset($this->request->post['new_password']) ? $this->request->post['new_password'] : '';

        if ($email === '') {
            $this->notification->set('error', 'Email is required');
            $this->response->redirect('account/account');
            return;
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->notification->set('error', 'Invalid email address');
            $this->response->redirect('account/account');
            return;
        }

        $change_password = ($old_password !== '' || $new_password !== '');

        if ($change_password) {
            if ($old_password === '') {
                $this->notification->set('error', 'Old password is required');
                $this->response->redirect('account/account');
                return;
            }

            if ($new_password === '') {
                $this->notification->set('error', 'New password is required');
                $this->response->redirect('account/account');
                return;
            }

            if ($old_password != $user['password']) {
                $this->notification->set('error', 'Old password is incorrect');
                $this->response->redirect('account/account');
                return;
            }
        }

        $update_data = [
            'email' => $email
        ];

        if ($change_password) {
            $update_data['password'] = $new_password;
        }

        $this->model_account_user->edit($this->user->loggedIn(), $update_data);

        $this->audit->add('ACCOUNT_UPDATE', 'user', $this->user->loggedIn());

        $this->notification->set('success', 'Account updated successfully');
        $this->response->redirect('account/account');
    }
}