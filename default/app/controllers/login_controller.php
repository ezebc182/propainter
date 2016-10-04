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

        $auth = Auth2::factory("model");

        if($auth->isValid()){
           Redirect::to("admin/dashboard");
            $this->title="Dashboard";
            View::template("admin");
        }else{
            $this->title="Login";
            View::template("login");
        }

    }

    public function login(){

        $auth = Auth2::factory("usuarios");
        Redirect::to("admin/dashboard");
    }
}