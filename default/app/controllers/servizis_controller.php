<?php

/**
 * Created by PhpStorm.
 * User: Ezequiel
 * Date: 22/09/2016
 * Time: 07:15 PM
 */
class ServizisController extends AppController
{
    public function index()
    {
        $this->menu = "servizi";
        $this->title = "Servizi";
        $this->tags = null;
        $this->servizi = (new Servizi())->find();
        if (Input::isAjax()) {
            echo json_encode(array("servizi" => $this->servizi));
            View::select(null, null);
        }

    }

    public function aggiungere()
    {
        $this->menu = "aggiungere";
        $this->title = "Aggiungere un nuovo servizio";
        $this->tags = null;

        if (Input::hasPost("servizi")) {
            try {
                $servizio = (new Servizi());
                $servizio->nome = Utils::normalizar_oracion(Input::post("servizi.nome"));
                $servizio->descrizione = Utils::normalizar_oracion(Input::post("servizi.descrizione"));
                if ($servizio->create()) {
                    Flash::valid("Il servizio <b>" . $servizio->nome . "</b> è stata inserita correttamente.");
                    Input::delete("servizis");
                } else {
                    Flash::warning("Impossibile aggiungere il servizio");
                }

            } catch (KumbiaException $e) {
                Flash::error("è verificato un errore aggiungere il servizio: " . $e->getMessage());
            }
            Redirect::to("servizis/mostrare");
        }
    }

    public function mostrare()
    {
        $this->menu = "mostrare";
        $this->title = "Servizi";
        $this->tags = null;
        $this->servizi = (new Servizi())->find();
        $this->breadcrumbs = array(array("link"=>"admin/dashboard",
            "text"=>"Dashboard"),array("link"=>"servizis/mostrare","text"=>"Servizi"));
    }

    public function modificare($id)
    {
        $this->menu = "modificare";
        $this->title = "Modificare un servizio";
        $this->tags = null;
        $this->servizi = (new Servizi())->find($id);
        if (Input::hasPost("servizi")) {
            try {
                $_old_servizi = (new Servizi())->find($id);
                $this->servizi = (new Servizi(Input::post("servizi")));

                if ($this->servizi->update()) {

                    if ($this->servizi->renombrarRutaImagenes($this->servizi->id, "/img/servizi/" . $this->servizi->nome)) {
                        if ($this->servizi->renombrarCarpetaImagenes(ABSOLUTE_PATH . "img/servizi/" .
                            Utils::slug($_old_servizi->nome),
                                ABSOLUTE_PATH . "img/servizi/" . Utils::slug($this->servizi->nome)) === TRUE
                        ) {
                            Flash::valid("Servizio  <b>" .
                                $this->servizi->nome . "</b> aggiornato");
                        } else {
                            Flash::warning("Impossibile rinominare la nuova cartella di immagini del servizio  <b>" .
                                $this->servizi->nome . "</b>");
                        }

                    } else {
                        Flash::warning("Impossibile rinominare il nuovo percorso immagini del servizio  <b>" .
                            $this->servizi->nome . "</b>");
                    }


                } else {
                    Flash::warning("Impossibile aggiornare il servizio  <b>" .
                        $this->servizi->nome . "</b>");

                }

            } catch (KumbiaException $e) {
                Flash::error("è verificato un errore aggiornare il servizio <b>" .
                    $this->servizi->nome . "</b>: " . $e->getMessage());
                return false;
            }
            Redirect::to("servizis/mostrare");


        }
    }

    public function eliminare($id)
    {
        $this->menu = "eliminare";
        $this->title = "Eliminare un servizio";
        $this->tags = null;


        try {
            $this->servizi = (new Servizi())->find($id);
            if ($this->servizi->eliminare($id, "img/servizi/")) {
                Flash::valid("Servizio eliminato");

            } else {
                Flash::warning("Impossibile eliminare il servizio");

            }

        } catch (KumbiaException $e) {
            Flash::error("è verificato un errore eliminare il servizio: " . $e->getMessage());

        }
        Redirect::to("servizis/mostrare");


    }

}