<?php

/**
 * Created by PhpStorm.
 * User: Ezequiel
 * Date: 02/09/2016
 * Time: 07:31 PM
 */
class ContattoController extends AppController
{
    public function index()
    {
        $this->title = "Contatto";
        $this->tags = (new Tags)->getTags();
        $this->menu = "contatto";
    }

    public function enviar()
    {


        if (Input::hasPost("contacto")) {

            try {

                if ((new Correo())->enviar(Input::post("contacto"))) {
                    Flash::valid("L'email è stata inviata, grazie mille!");
                } else {
                    Flash::warning("L'email non è stato possibile inviare. Si prega di riprovare più tardi.");
                }


            } catch (KumbiaException $e) {
                Flash::error("Si è verificato un errore durante l'invio di e-mail. " . $e->getMessage());
            }

            return Redirect::to("contatto");
        }
    }

    protected function after_filter()
    {
        if (Input::isAjax()) {
            Input::delete("contacto");
            View::template(null); //si es ajax solo mostramos la vista

        }
    }


}


