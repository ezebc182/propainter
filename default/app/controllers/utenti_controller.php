<?php

/**
 * Created by PhpStorm.
 * User: Ezequiel
 * Date: 04/10/2016
 * Time: 12:48 PM
 */
class UtentiController extends AppController
{
    public function index()
    {
    }

    public function check_current_password($id)
    {
        if (Input::isAjax()) {
            try {
                $this->utenti = (new Utenti())->find($id);
                if ($this->utenti->password != md5(Input::post("current_password"))) {
                    //Flash::warning("La contraseña actual no es correcta.");
                    //Flash::info(md5(Input::post("current_password")));
                    $mensaje = 'La contraseña actual no es correcta.';
                    echo json_encode(array("code"=>182,"mensaje"=>$mensaje));

                }
            } catch (KumbiaException $e) {
                Flash::error("Error: " . $e->getMessage());
            }

            View::select(null, null);
        }
    }

    public function checkSecurityPassword()
    {
        $pwd = Input::post("pwd");
        if(Input::isAjax()) {


            $error = "";
            if (strlen($pwd) < 8) {
                $error .= "Password too short! 
";
            }

            if (strlen($pwd) > 20) {
                $error .= "Password too long! 
";
            }

            if (strlen($pwd) < 8) {
                $error .= "Password too short! 
";
            }

            if (!preg_match("#[0-9]+#", $pwd)) {
                $error .= "Password must include at least one number! 
";
            }


            if (!preg_match("#[a-z]+#", $pwd)) {
                $error .= "Password must include at least one letter! 
";
            }


            if (!preg_match("#[A-Z]+#", $pwd)) {
                $error .= "Password must include at least one CAPS! 
";
            }


            if (!preg_match("#\W+#", $pwd)) {
                $error .= "Password must include at least one symbol! 
";
            }


            if ($error) {
                Flash::warning("Password validation failure(your choise is weak): $error");
            } else {
                Flash::valid("Your password is strong.");
            }
        View::select(null,null);
        }

    }

    public function modificare_password($id)
    {
        $this->utenti = (new Utenti())->find($id);
        if (Input::isAjax()) {
            try {
                $this->utenti->password = md5(Input::post("new_password"));
                if ($this->utenti->update()) {
                    Flash::valid("Password aggiornato.");

                } else {
                    Flash::warning("Imposibile aggiornar password.");
                }
            } catch (KumbiaException $e) {
                Flash::error("Error: " . $e->getMessage());
            }

            View::select(null, null);
        }
    }

    public function modificare($id)
    {
        View::template("admin");
        $this->utenti = (new Utenti())->find($id);
        $this->title = "Modificare utenti: <b>" . $this->utenti->nome . "</b>";
        $this->breadcrumbs = array(
            array("link" => "admin/dashboard", "text" => "Dashboard"),
            array("link" => "utenti/mostrare", "text" => "Utenti"),
            array("link" => "utenti/modificare/$id", "text" => "Modificare"),
            array("link" => "", "text" => $this->utenti->cognome . ", " .
                $this->utenti->nome, "active" => true)
        );
        if (Input::hasPost("utenti")) {

            try {
                $this->utenti = (new Utenti(Input::post("utenti")));

                if ($this->utenti->update()) {
                    Flash::valid("Utente aggiornato");

                } else {
                    Flash::warning("Utente aggiornato");
                }
            } catch (KumbiaException $e) {
                Flash::error("Errore: " . $e->getMessage());
            }
        }
    }
}