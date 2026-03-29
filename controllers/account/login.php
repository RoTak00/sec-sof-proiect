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

        $ip = $this->request->server['REMOTE_ADDR'];

        if ($this->isLoginBlockedByIp($ip)) {
            $this->notification->set('error', 'Too many failed login attempts. Try again later.');
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
            $this->notification->set('error', 'Invalid email or password, or the account is not verified yet');
            $this->audit->add('USER_LOGIN_FAILED', $this->request->post['email'], 0);
            $this->response->redirect('account/login');
            return;
        }

        $this->audit->add('USER_LOGIN', $this->request->post['email'], $this->user->loggedIn());

        $this->notification->set('success', 'Logged in!');

        $this->response->redirect('common/home');
    }

    private function isLoginBlockedByIp($ip)
    {
        $result = $this->db->query(
            "SELECT COUNT(*) AS total
         FROM audit_logs
         WHERE action = ?
           AND ip_address = ?
           AND timestamp >= DATE_SUB(NOW(), INTERVAL 10 MINUTE)",
            'ss',
            ['USER_LOGIN_FAILED', $ip]
        );

        return (int) $result->row['total'] >= 5;
    }
}