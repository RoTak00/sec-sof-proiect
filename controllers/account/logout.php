<?php

class AccountLogoutController extends BaseController
{
    public function index()
    {
        $this->user->logout();
        $this->notification->set('success', 'You have been logged out');
        $this->response->redirect('account/login');
    }


}