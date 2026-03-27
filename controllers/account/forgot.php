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


        $this->loadModel('account/user');

        $existing_user = $this->model_account_user->getUserByEmail($this->request->post['email']);

        if (!$existing_user) {

            $this->audit->add('USER_FORGOTTEN', $this->request->post['email'], 0);
            $this->notification->set('error', 'User does not exist');
            $this->response->redirect('account/forgot');
            return;
        }

        $this->audit->add('USER_FORGOTTEN', $this->request->post['email'], $existing_user['user_id']);
        $this->mail->send($this->request->post['email'], 'Forgotten Password', 'Hello. Click the following link to reset your password: ' . $this->url->link('account/forgot/reset/' . $existing_user['user_id'], true));
        $this->response->redirect('account/forgot/sent');
    }

    public function sent()
    {
        $data = [];

        $data['back'] = $this->url->link('account/login');

        $head_settings = ['page_title' => 'Mail sent'];
        $data['head'] = $this->loadController('common/head', $head_settings);
        $data['footer'] = $this->loadController('common/footer');
        $data['notification'] = $this->loadController('common/notification');
        return $this->loadView('account/forgot_sent.php', $data);

    }

    public function reset($setting)
    {
        if ($this->request->server['REQUEST_METHOD'] != 'GET') {
            $this->response->redirect('account/login');
            return;
        }

        $user_id = (int) $setting[0];

        $this->loadModel('account/user');

        $user = $this->model_account_user->getUserById($user_id);

        if (!$user) {
            $this->response->redirect('account/login');
            return;
        }

        $data = [];

        $data['action'] = $this->url->link('account/forgot/reset_submit/' . $user_id);
        $data['back'] = $this->url->link('account/login');

        $data['email'] = $user['email'];
        $head_settings = ['page_title' => 'Reset Password'];
        $data['head'] = $this->loadController('common/head', $head_settings);
        $data['footer'] = $this->loadController('common/footer');
        $data['notification'] = $this->loadController('common/notification');
        return $this->loadView('account/reset.php', $data);
    }

    public function reset_submit($setting)
    {
        if ($this->request->server['REQUEST_METHOD'] != 'POST') {
            $this->response->redirect('account/login');
            return;
        }

        $user_id = (int) $setting[0];

        $this->loadModel('account/user');

        $user = $this->model_account_user->getUserById($user_id);

        if (!$user) {
            $this->response->redirect('account/login');
            return;
        }

        $this->audit->add('USER_RESET_PASSWORD', $user['email'], $user['user_id']);
        $this->model_account_user->updatePassword($user['user_id'], $this->request->post['password']);
        $this->response->redirect('account/login');
    }
}