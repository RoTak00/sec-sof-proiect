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
            $this->response->redirect('account/forgot/sent');
            return;
        }

        $token = bin2hex(random_bytes(32));
        $token_hash = hash('sha256', $token);
        $expires_at = date('Y-m-d H:i:s', time() + 3600);

        $this->db->query(
            "DELETE FROM user_reset_tokens WHERE user_id = ?",
            'i',
            [$existing_user['user_id']]
        );

        $this->db->query(
            "INSERT INTO user_reset_tokens (user_id, token_hash, expires_at, created_at) VALUES (?, ?, ?, NOW())",
            'iss',
            [$existing_user['user_id'], $token_hash, $expires_at]
        );

        $reset_link = $this->url->link('account/forgot/reset/' . $token, true);

        $this->audit->add('USER_FORGOTTEN', $this->request->post['email'], $existing_user['user_id']);
        $this->mail->send(
            $this->request->post['email'],
            'Forgotten Password',
            'Hello. Click the following link to reset your password: ' . $reset_link
        );

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

        if (empty($setting[0])) {
            $this->response->redirect('account/login');
            return;
        }

        $token = $setting[0];
        $token_hash = hash('sha256', $token);

        $reset_token = $this->db->query(
            "SELECT * FROM user_reset_tokens WHERE token_hash = ? AND used_at IS NULL AND expires_at >= NOW() LIMIT 1",
            's',
            [$token_hash]
        )->row;

        if (!$reset_token) {
            $this->notification->set('error', 'Invalid or expired reset link');
            $this->response->redirect('account/forgot');
            return;
        }

        $this->loadModel('account/user');

        $user = $this->model_account_user->getUserById($reset_token['user_id']);

        if (!$user) {
            $this->notification->set('error', 'Invalid or expired reset link');
            $this->response->redirect('account/login');
            return;
        }

        $data = [];
        $data['action'] = $this->url->link('account/forgot/reset_submit/' . $token);
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

        if (empty($setting[0])) {
            $this->response->redirect('account/login');
            return;
        }

        if (empty($this->request->post['password'])) {
            $this->notification->set('error', 'Password is required');
            $this->response->redirect('account/login');
            return;
        }

        if (strlen($this->request->post['password']) < 10) {
            $this->notification->set('error', 'Password must be at least 10 characters long');
            $this->response->redirect('account/login');
            return;
        }

        $token = $setting[0];
        $token_hash = hash('sha256', $token);

        $reset_token = $this->db->query(
            "SELECT * FROM user_reset_tokens WHERE token_hash = ? AND used_at IS NULL AND expires_at >= NOW() LIMIT 1",
            's',
            [$token_hash]
        )->row;

        if (!$reset_token) {
            $this->notification->set('error', 'Invalid or expired reset link');
            $this->response->redirect('account/forgot');
            return;
        }

        $this->loadModel('account/user');

        $user = $this->model_account_user->getUserById($reset_token['user_id']);

        if (!$user) {
            $this->response->redirect('account/login');
            return;
        }

        $this->model_account_user->updatePassword($user['user_id'], $this->request->post['password']);

        $this->db->query(
            "UPDATE user_reset_tokens SET used_at = NOW() WHERE reset_token_id = ?",
            'i',
            [$reset_token['reset_token_id']]
        );

        $this->audit->add('USER_RESET_PASSWORD', $user['email'], $user['user_id']);
        $this->notification->set('success', 'Password updated successfully');

        $this->response->redirect('account/login');
    }
}