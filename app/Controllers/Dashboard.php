<?php

namespace Controllers;

class Dashboard
{
    public function login()
    {
        require_once 'view/login.php';
    }

    public function home()
    {
        require_once 'view/home.php';
    }
}
