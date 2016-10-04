<?php

/**
 * Created by PhpStorm.
 * User: Ezequiel
 * Date: 03/10/2016
 * Time: 11:41 PM
 */
class Utenti extends ActiveRecord
{
    public function getUtenti(){
        return $this->find_by_sql("SELECT * FROM utenti");
    }
    /**
     * Iniciar sesion
     *
     */
    public function login()
    {
        // Obtiene el adaptador
        $auth = Auth2::factory("model");
        // Modelo que utilizará para consultar

        $auth->setModel("utenti");

        $auth->setLogin("email");
        $auth->setPass("password");
        $auth->setAlgos("md5");


        if($auth->identify()) return true;
        
        Flash::warning(" Utenti / Password non correcta.");
        return false;
    }

    /**
     * Terminar sesion
     *
     */
    public function logout()
    {
        Auth2::factory("model")->logout();
    }

    /**
     * Verifica si el usuario esta autenticado
     *
     * @return boolean
     */
    public function logged()
    {
        return Auth2::factory("model")->isValid();
    }


}