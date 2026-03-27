<?php

class AccountLogoutController extends BaseController
{
    public function index()
    {
        $this->audit->add('USER_LOGOUT', '', $this->user->loggedIn());
        $this->user->logout();
        $this->notification->set('success', 'You have been logged out');
        $this->response->redirect('account/login');
    }


}