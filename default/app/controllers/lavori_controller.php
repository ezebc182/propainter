<?php

/**
 * Created by PhpStorm.
 * User: Ezequiel
 * Date: 22/09/2016
 * Time: 07:15 PM
 */
class LavoriController extends AppController
{
    public function index()
    {
        $this->menu = "lavori";
        $this->title = "I Nostri lavori";
        $this->tags = null;
        $this->lavori = (new Lavori())->find();
        if (Input::isAjax()) {
            echo json_encode(array("lavori" => $this->lavori));
            View::select(null, null);
        }

    }

    public function aggiungere()
    {
        $this->menu = "aggiungere";
        $this->title = "Aggiungere un nuovo lavoro";
        $this->tags = null;
        $this->breadcrumbs = array(array("link" => "admin/dashboard",
            "text" => "Dashboard "),
            array("link" => "lavori/mostrare", "text" => "lavori"),
            array("link" => "lavori/aggiungere","text"=>"Aggiungere", "active" => true)
        );
        View::template("admin");


        if (Input::hasPost("lavori")) {
            try {
                $lavoro = (new Lavori());
                //$lavoro->servizi_tags = Input::post("lavori.servizi_tags");
                $lavoro->nome = Utils::normalizar_oracion(Input::post("lavori.nome"));
                $lavoro->descrizione = Utils::normalizar_oracion(Input::post("lavori.descrizione"));
                if ($lavoro->create()) {
                    Flash::valid("Il lavoro <b>" . $lavoro->nome . "</b> è stata inserita correttamente.");
                    Input::delete("lavori");
                } else {
                    Flash::warning("Impossibile aggiungere il lavoro");
                }

            } catch (KumbiaException $e) {
                Flash::error("è verificato un errore aggiungere il lavoro: " . $e->getMessage());
            }
            Redirect::to("lavori/mostrare");
        }

    }

    public function mostrare()
    {
        $this->menu = "mostrare";
        $this->title = "Lavori";
        $this->tags = null;
        $this->lavori = (new Lavori())->find();
        View::template("admin");
        $this->breadcrumbs = array(array("link" => "admin/dashboard",
            "text" => "Dashboard "),
            array("link" => "lavori/mostrare", "text" => "lavori","active"=>true),
        );
    }

    public function modificare($id)
    {
        View::template("admin");
        $this->menu = "modificare";
        $this->lavori = (new Lavori())->find($id);

        $this->title = "Modificare il lavoro: <b>".$this->lavori->nome."</b>";
        $this->tags = null;

        $this->breadcrumbs = array(array("link" => "admin/dashboard",
            "text" => "Dashboard "),
            array("link" => "lavori/mostrare", "text" => "lavori"),
            array("link" => false, "text" => $this->lavori->nome),
            array("link" => "lavori/modificare/$id",
                "text" => "Modificare", "active" => true));
        if (Input::hasPost("lavori")) {
            try {
                $_old_lavori = (new Lavori())->find($id);
//                $this->lavori = (new Lavori());
//                $this->lavori->nome = Utils::normalizar_oracion(Input::post("lavori.nome"));
//                $this->lavori->descrizione = Utils::normalizar_oracion(Input::post("lavori.descrizione"));
                $this->lavori = (new Lavori(Input::post("lavori")));

                if ($this->lavori->update()) {
                    Flash::valid("Lavoro <b>" . $this->lavori->nome . "</b> aggiornato");
                    //Flash::info(var_dump(count($this->lavori->getImmagini($id))));

                    if (count($this->lavori->getImmagini($id)) > 0) {

                        if ($this->lavori->renombrarRutaImagenes($this->lavori->id, "img/lavori/" . $this->lavori->nome)) {
                            if ($this->lavori->renombrarCarpetaImagenes(ABSOLUTE_PATH . "/img/lavori/" . $_old_lavori->nome,
                                    ABSOLUTE_PATH . "/img/lavori/" . $this->lavori->nome) === TRUE
                            ) {
                                Flash::valid("Cartella di immagini del lavoro  <b>" .
                                    $this->lavori->nome . "</b> aggiornato");
                            } else {
                                Flash::warning("Impossibile rinominare la nuova cartella di immagini del lavoro  <b>" .
                                    $this->lavori->nome . "</b>");
                            }

                        } else {
                            Flash::warning("Impossibile rinominare il nuovo percorso immagini del lavoro  <b>" .
                                $this->lavori->nome . "</b>");
                        }


                    }


                } else {
                    Flash::warning("Impossibile aggiornare il lavoro  <b>" .
                        $this->lavori->nome . "</b>");

                }


            } catch (KumbiaException $e) {
                Flash::error("è verificato un errore aggiornare il lavoro <b>" .
                    $this->lavori->nome . "</b>: " . $e->getMessage());

            }

            Redirect::to("lavori/mostrare");

        }

    }

    public function eliminare($id)
    {
        $this->menu = "eliminare";
        $this->title = "Eliminare un lavoro";
        $this->tags = null;


        try {
            $this->lavori = (new lavori())->find($id);
            if ($this->lavori->eliminare($id, "img/lavori/")) {
                Flash::valid("Lavoro eliminato");

            } else {
                Flash::warning("Impossibile eliminare il lavoro");

            }

        } catch (KumbiaException $e) {
            Flash::error("è verificato un errore eliminare il lavoro: " . $e->getMessage());

        } View::template("admin");
        Redirect::to("lavori/mostrare");


    }

}