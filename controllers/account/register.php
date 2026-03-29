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

        $email = isset($this->request->post['email']) ? trim(strtolower($this->request->post['email'])) : '';
        $password = isset($this->request->post['password']) ? $this->request->post['password'] : '';

        if ($email === '') {
            $this->notification->set('error', 'Email is required');
            $this->response->redirect('account/register');
            return;
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->notification->set('error', 'Invalid email address');
            $this->response->redirect('account/register');
            return;
        }

        if ($password === '') {
            $this->notification->set('error', 'Password is required');
            $this->response->redirect('account/register');
            return;
        }

        if (strlen($password) < 10) {
            $this->notification->set('error', 'Password must be at least 10 characters long');
            $this->response->redirect('account/register');
            return;
        }

        $this->loadModel('account/user');

        $existing_user = $this->model_account_user->getUserByEmail($email);

        if (!$existing_user) {
            $user_id = $this->user->register($email, $password);

            $token = bin2hex(random_bytes(32));
            $token_hash = hash('sha256', $token);
            $expires_at = date('Y-m-d H:i:s', time() + 3600 * 24);

            $this->model_account_user->setVerificationToken($user_id, $token_hash, $expires_at);

            $verify_link = $this->url->link('account/register/verify/' . $token, true);

            $this->mail->send(
                $email,
                'Verify your AuthX account',
                'Hello. Click the following link to verify your account: ' . $verify_link
            );

            $this->audit->add('USER_REGISTER', $email, $user_id);
        } else {
            if (!$existing_user['is_verified']) {
                $token = bin2hex(random_bytes(32));
                $token_hash = hash('sha256', $token);
                $expires_at = date('Y-m-d H:i:s', time() + 3600 * 24);

                $this->model_account_user->setVerificationToken($existing_user['user_id'], $token_hash, $expires_at);

                $verify_link = $this->url->link('account/register/verify/' . $token, true);

                $this->mail->send(
                    $email,
                    'Verify your AuthX account',
                    'Hello. Click the following link to verify your account: ' . $verify_link
                );
            } else {
                $this->mail->send(
                    $email,
                    'Your AuthX account',
                    'Hello. You already have a verified account. If you want to reset your password, click the following link: ' . $this->url->link('account/forgot', true)
                );
            }

            $this->audit->add('USER_REGISTER', $email, 0);
        }

        $this->notification->set('success', 'Before logging in, check your email to verify your account.');
        $this->response->redirect('account/login');
    }

    public function verify($setting)
    {
        if (empty($setting[0])) {
            $this->notification->set('error', 'Invalid verification link');
            $this->response->redirect('account/login');
            return;
        }

        $token = $setting[0];
        $token_hash = hash('sha256', $token);

        $this->loadModel('account/user');

        $user = $this->model_account_user->getUserByVerificationToken($token_hash);

        if (!$user) {
            $this->notification->set('error', 'Invalid or expired verification link');
            $this->response->redirect('account/login');
            return;
        }

        $this->model_account_user->verifyUser($user['user_id']);

        $this->audit->add('USER_VERIFY', $user['email'], $user['user_id']);
        $this->notification->set('success', 'Account verified. You can now log in.');
        $this->response->redirect('account/login');
    }
}