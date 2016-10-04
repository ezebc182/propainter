<?php

/**
 * Created by PhpStorm.
 * User: Ezequiel
 * Date: 04/10/2016
 * Time: 01:30 AM
 */
class AuthController extends AppController
{
    public function login()
    {
        // Si se loguea se redirecciona al módulo de cliente
        if ((new Utenti())->login()) {
            View::template("admin");
            Redirect::to("admin/dashboard");

        }else{
            Redirect::to("login");

        }
    }

    public function logout()
    {
        // Termina la sesion
        (new Utenti())->logout();
        Redirect::to("login");
    }

}