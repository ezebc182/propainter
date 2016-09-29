<?php

/**
 * Created by PhpStorm.
 * User: Ezequiel
 * Date: 14/09/2016
 * Time: 08:05 PM
 */
class LoginController extends AppController
{
    public function index(){
        $this->title="Login";
        View::template("login");
    }
}