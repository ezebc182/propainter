<?php

/**
 * Created by PhpStorm.
 * User: Ezequiel
 * Date: 23/09/2016
 * Time: 12:54 PM
 */
class ImmaginiServiziController extends AppController
{
    public function index()
    {
        Redirect::toAction("aggiungere");

    }

    public function aggiungere($id)
    {

        $this->title = "Aggiungere immagine per il servizio. ";
        $this->menu = "aggiungere_immagini";
        $this->tags = null;
        $this->breadcrumbs = array(array("link"=>"immagini_servizi/mostrare/$id",
            "text"=>"Mostrare"),array("link"=>"immagini_servizi/aggiungere/$id",
            "text"=>"Aggiungere"));

        $this->servizio = (new Servizi())->find($id);

        $this->cantidad = (new ImmaginiServizi())->countImmagine($id);
        if (Input::hasPost("immagini_servizi")) {

            if (Utils::maxPostCheck()) {
                $servizi = (new Servizi())->find($id);
                $data = array(
                    "nome_servizi" => $servizi->nome,
                    "nome_immagine" => Input::post("immagini_servizi.nome"),

                );
                if ($_FILES["file"]['error'] != UPLOAD_ERR_NO_FILE) {
                    $nombre_archivo = Utils::slug($data["nome_immagine"]);
                    $carpeta = Utils::slug($data["nome_servizi"]);
                    $archivos = new Archivos();
                    $ruta =  "servizi/".$carpeta . '/';


                    $_archivo = $archivos->uploadArchivo("file", $ruta, "image", $nombre_archivo);

                    if ($_archivo != NULL) {


                        $immagine_servizi = new ImmaginiServizi();
                        $immagine_servizi->nome = Input::post("immagini_servizi.nome");
                        $immagine_servizi->descrizione = Input::post("immagini_servizi.descrizione");
                        $immagine_servizi->servizi_id = $servizi->id;

                        if ($immagine_servizi->create()) {
                            $immagine_servizi->files_id = $_archivo->id;


                            if ($immagine_servizi->update()) {

                                Flash::valid("l'immagine <b>" . $immagine_servizi->nome . "</b> 
                                è stato caricato con successo.");
                            }
                        }
                    }


                }
            }


            Redirect::toAction("mostrare/" . $id);
        }
    }

    public function modificare($id)
    {
        $this->title = "Modificare immagine per il servizio ";
        $this->menu = "modificare_immagini";
        $this->tags = null;

        $this->immagini_servizi = (new ImmaginiServizi())->find($id);
        $this->servizio = (new Servizi())->find($this->immagini_servizi->servizi_id);
        $this->archivos = (new Archivos())->find($this->immagini_servizi->files_id);

        if (Input::hasPost("immagini_servizi")) {

            $immagini_servizi = (new ImmaginiServizi(Input::post("immagini_servizi")));
            try {
                if ($immagini_servizi->update()) {
                    //modificacion logica
                    $archivo = (new Archivos())->find($immagini_servizi->files_id);
                    $archivo_old_name = $archivo->nombre;
                    $archivo->nombre = $immagini_servizi->nome . "." . $archivo->extension;

                    $archivo->update();

                    //modificacion fisica
                    rename(ABSOLUTE_PATH . "img/servizi/" . Utils::slug($this->servizio->nome) . "/" .
                        $archivo_old_name,
                        ABSOLUTE_PATH . "img/servizi/" . Utils::slug($this->servizio->nome) . "/" .
                        $immagini_servizi->nome . "." . $archivo->extension
                    );
                    Flash::valid("L'immagine <b>" . $immagini_servizi->nome . "</b> è stata modificata");
                } else {
                    Flash::warning("Impossibile modificare l'immagine <b>" . $immagini_servizi->nome . "</b>");
                }
            } catch (KumbiaException $e) {
                Flash::error("Si è verificato un errore durante la modifica dell'immagine<b>" .
                    $immagini_servizi->nome . "</b>" . $e->getMessage());
            }


            Redirect::toAction("mostrare/" . $this->immagini_servizi->servizi_id);
        }
    }

    public function mostrare($id)
    {
        $this->title = "Mostrare immagini per il servizio ";
        $this->menu = "mostrare_immagini";
        $this->servizio = (new Servizi())->find($id);
        $this->immagini = (new ImmaginiServizi())->getImmagini($id);
        $this->tags = null;
        $this->breadcrumbs = array(array("link"=>"admin/dashboard",
            "text"=>"Dashboard "),
            array("link"=>"servizis/mostrare","text"=>"Servizi"),
            array("link"=>null,"text"=>$this->servizio->nome),
            array("link"=>"immagini_servizi/mostrare/$id",
            "text"=>"Immagini","active"=>true));
        if (Input::isAjax()) {
            echo json_encode($this->immagini);
            View::select(null, null);
        }
    }

    public function eliminare($immagine_id)
    {
        $immagini_servizi = (new ImmaginiServizi())->find($immagine_id);
        $immagini_servizi_old = $immagini_servizi;
        try {

            if ($immagini_servizi->eliminare($immagine_id)) {
                Flash::valid("Immagine <b>$immagini_servizi_old->nome </b> eliminata");
            } else {
                Flash::warning("Impossibile eliminare  l'immagine " . $immagini_servizi_old->nome);

            }
        } catch (KumbiaException $e) {
            Flash::error("Errore al eliminare  l'immagine " . $immagini_servizi_old->nome .
                "<br>" . $e->getMessage());
        }
        Redirect::toAction("mostrare/" . $immagini_servizi->servizi_id);
    }

    public function fare_principale($immagine_id)
    {
        $this->title = "fare_primaria immagini";
        $this->menu = "fare_primaria";
        $this->tags = null;
        $immagine = "";
//        if (Input::isAjax()) {
        $servizio_id = (new ImmaginiServizi())->find($immagine_id)->servizi_id;
        $immagini = (new ImmaginiServizi())->getImmagini($servizio_id);
        try {
            if (count($immagini) > 1) {
                foreach ($immagini as $immagine) {
                    $immagine->principale = FALSE;
                    $immagine->update();
                }

            }
            $immagine = (new ImmaginiServizi())->find($immagine_id);
            $immagine->principale = TRUE;
            if ($immagine->update()) {

                Flash::valid("Immagine <b>$immagine->nome</b> è la principale");
            } else {
                Flash::warning("Non poteva fare principale l'immagine <b>$immagine->nome</b>");

            }
        } catch (KumbiaException $e) {
            Flash::error("Errore al fare primaria l'immagine <b>$immagine->nome</b>" .
                "<br>" . $e->getMessage());
        }


//        }
        Redirect::toAction("mostrare/" . $servizio_id);
//        View::select(null,null); //si es ajax solo mostramos la vista
    }
}