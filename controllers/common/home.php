<?php

class CommonHomeController extends BaseController
{


    public function index()
    {
        global $WEBSITE_NAME;
        $data = [];

        $data['footer'] = $this->loadController('common/footer');

        $head_settings = ['page_title' => $WEBSITE_NAME ?? ''];
        $data['head'] = $this->loadController('common/head', $head_settings);
        return $this->loadView('common/home.php', $data);
    }
}