<?php

class AccountUserController extends BaseController
{
    public function index()
    {
        if (!$this->user->loggedIn()) {
            $this->response->redirect('account/login');
            return;
        }

        $this->loadModel('account/user');

        $users = $this->model_account_user->getUsers();

        $this->audit->add('USER_LIST', 'user', 0);

        $data = [];
        $data['users'] = array_map(function ($u) {
            $u['edit'] = $this->url->link('account/user/edit/' . $u['user_id']);
            return $u;
        }, $users);


        $data['navbar'] = $this->loadController('common/navbar', ['active' => 'users']);
        $data['notification'] = $this->loadController('common/notification');
        $data['footer'] = $this->loadController('common/footer');
        $head_settings = ['page_title' => 'Users'];
        $data['head'] = $this->loadController('common/head', $head_settings);

        return $this->loadView('user/user_list.php', $data);
    }

    public function edit($setting)
    {
        if (!$this->user->loggedIn()) {
            $this->response->redirect('account/login');
            return;
        }

        if (!count($setting)) {
            $this->notification->set('error', 'User not found');
            $this->response->redirect('account/user');
            return;
        }

        $user_id = (int) $setting[0];

        $this->loadModel('account/user');

        $edited_user = $this->model_account_user->getUserById($user_id);

        if (!$edited_user) {
            $this->notification->set('error', 'User not found');
            $this->response->redirect('account/user');
            return;
        }

        $data = [];
        $data['edited_user'] = $edited_user;
        $data['action'] = $this->url->link('account/user/update/' . $user_id);
        $data['navbar'] = $this->loadController('common/navbar', ['active' => 'users']);
        $data['notification'] = $this->loadController('common/notification');
        $data['footer'] = $this->loadController('common/footer');
        $head_settings = ['page_title' => 'Edit User'];
        $data['head'] = $this->loadController('common/head', $head_settings);

        return $this->loadView('user/user_edit.php', $data);
    }

    public function update($setting)
    {
        if (!$this->user->loggedIn()) {
            $this->response->redirect('account/login');
            return;
        }

        if ($this->request->server['REQUEST_METHOD'] !== 'POST') {
            $this->response->redirect('account/user');
            return;
        }

        if (!count($setting)) {
            $this->notification->set('error', 'User not found');
            $this->response->redirect('account/user');
            return;
        }

        $user_id = (int) $setting[0];

        $this->loadModel('account/user');

        $edited_user = $this->model_account_user->getUserById($user_id);

        if (!$edited_user) {
            $this->notification->set('error', 'User not found');
            $this->response->redirect('account/user');
            return;
        }

        $email = isset($this->request->post['email']) ? trim($this->request->post['email']) : '';
        $password = isset($this->request->post['password']) ? $this->request->post['password'] : '';
        $role = isset($this->request->post['role']) ? trim($this->request->post['role']) : '';

        if ($email === '') {
            $this->notification->set('error', 'Email is required');
            $this->response->redirect('account/user/edit/' . $user_id);
            return;
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->notification->set('error', 'Invalid email address');
            $this->response->redirect('account/user/edit/' . $user_id);
            return;
        }

        $allowed_roles = ['user', 'analyst', 'admin'];

        if (!in_array($role, $allowed_roles, true)) {
            $this->notification->set('error', 'Invalid role');
            $this->response->redirect('account/user/edit/' . $user_id);
            return;
        }

        $update_data = [
            'email' => $email,
            'role' => $role
        ];

        if ($password !== '') {
            $update_data['password'] = $password;

            if (strlen($password) < 10) {
                $this->notification->set('error', 'New password must be at least 10 characters long');
                $this->response->redirect('account/user/edit/' . $user_id);
                return;
            }
        }



        $this->model_account_user->edit($user_id, $update_data);

        $this->audit->add('USER_UPDATE', 'user', $user_id);
        $this->mail->send($edited_user['email'], 'User Updated', 'Hello. Your account has been updated.');

        $this->notification->set('success', 'User updated successfully');
        $this->response->redirect('account/user/edit/' . $user_id);
    }
}