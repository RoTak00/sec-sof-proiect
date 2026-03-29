<?php

class CommonNavbarController extends BaseController
{
    public function index($setting = [])
    {
        $active = isset($setting['active']) ? $setting['active'] : '';

        $data = [];

        $data['active'] = $active;

        $data['home_link'] = '/';
        $data['account_link'] = $this->url->link('account/account');
        $data['tickets_link'] = $this->url->link('tickets/ticket');
        $data['users_link'] = $this->url->link('account/user');
        $data['logout'] = $this->url->link('account/logout');

        return $this->loadView('common/navbar.php', $data);
    }
}