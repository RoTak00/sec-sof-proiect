<?php


class Url
{
    private $registry = [];
    public function __construct($registry)
    {

        $this->registry = $registry;
    }


    public function __get($name)
    {
        if (isset($this->registry->registry[$name])) {
            return $this->registry->registry[$name];
        }
        return null; // Or throw an error if you want strict behavior
    }

    public function link($controller)
    {

        return BASE_LOCATION . $controller;
    }

}