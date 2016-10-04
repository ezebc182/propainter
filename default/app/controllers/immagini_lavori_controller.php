<?php

/**
 * Created by PhpStorm.
 * User: Ezequiel
 * Date: 23/09/2016
 * Time: 12:54 PM
 */
class ImmaginiLavoriController extends AppController
{
    public function index()
    {
        Redirect::toAction("aggiungere");

    }

    public function aggiungere($id)
    {
        View::template("admin");
        $this->lavoro = (new Lavori())->find($id);

        $this->title = "Aggiungere immagini per il lavoro: <b>" . $this->lavoro->nome . "</b>";
        $this->menu = "aggiungere_immagini";
        $this->tags = null;

        $this->breadcrumbs = array(array("link" => "admin/dashboard", "text" => "Dashboard "),
            array("link" => "lavori/mostrare", "text" => "lavori"),
            array("link" => false, "text" => $this->lavoro->nome),
            array("link" => "immagini_lavori/mostrare", "text" => "Immagini"),
            array("link" => "immagini_lavori/aggiungere/$id", "text" => "Aggiungere", "active" => true));

        $this->cantidad = (new ImmaginiLavori())->countImmagine($id);
        if (Input::hasPost("immagini_lavori")) {
            try {
                if (Utils::maxPostCheck()) {
                    $lavori = (new Lavori())->find($id);
                    $data = array(
                        "nome_lavori" => strtolower($lavori->nome),
                        "nome_lavoro" => Input::post("immagini_lavori.nome"),

                    );
                    if ($_FILES["file"]['error'] != UPLOAD_ERR_NO_FILE) {
                        $nombre_archivo = Utils::slug($data["nome_lavoro"]);
                        $carpeta = Utils::slug($data["nome_lavori"]);
                        $archivos = new Archivos();
                        $ruta = "lavori/" . $carpeta . '/';


                        $_archivo = $archivos->uploadArchivo("file", $ruta, "image", $nombre_archivo);

                        if ($_archivo != NULL) {


                            $lavoro_lavori = new ImmaginiLavori();
                            $lavoro_lavori->nome = Input::post("immagini_lavori.nome");
                            $lavoro_lavori->descrizione = Input::post("immagini_lavori.descrizione");
                            $lavoro_lavori->lavori_id = $lavori->id;

                            if ($lavoro_lavori->create()) {
                                $lavoro_lavori->files_id = $_archivo->id;


                                if ($lavoro_lavori->update()) {

                                    Flash::valid("Il lavoro <b>" . $lavoro_lavori->nome . "</b> 
                                è stato caricato con successo.");
                                } else {
                                    Flash::warning("No se pudo actualizar el registro con el archivo");
                                }
                            } else {
                                Flash::warning("No se pudo crear el archivo");
                            }
                        } else {
                            Flash::warning("No se pudo subir la imagen");
                        }


                    } else {
                        Flash::warning("Error al subir el archivo");
                    }
                } else {
                    Flash::warning("La imagen que intenta subir es superior al
                     tamaño máximo permitido por el servidor.");
                }

            } catch (KumbiaException $e) {
                Flash::error("Si è verificato un errore durante la caricata dell'lavoro<b>" .
                    $lavori->nome . "</b>" . $e->getMessage());
            }


            Redirect::toAction("mostrare/" . $id);
        }
    }

    public function modificare($id)
    {
        $this->immagini_lavori = (new ImmaginiLavori())->find($id);

        $this->title = "Modificare immagini: <b>" . $this->immagini_lavori->nome . "</b>";
        $this->menu = "modificare_immagini";
        $this->tags = null;
        View::template("admin");

        $this->lavoro = (new Lavori())->find($this->immagini_lavori->lavori_id);
        $this->archivos = (new Archivos())->find($this->immagini_lavori->files_id);
        $this->breadcrumbs = array(array("link" => "admin/dashboard",
            "text" => "Dashboard "),
            array("link" => "lavori/mostrare", "text" => "lavori"),
            array("link" => false, "text" => $this->lavoro->nome),
            array("link" => "immagini_lavori/mostrare", "text" => "Immagini"),
            array("link" => "immagini_lavori/modificare/$id",
                "text" => "Modificare", "active" => true));
        if (Input::hasPost("immagini_lavori")) {

            $immagini_lavori = (new ImmaginiLavori(Input::post("immagini_lavori")));
            try {
                if ($immagini_lavori->update()) {
                    //modificacion logica
                    $archivo = (new Archivos())->find($immagini_lavori->files_id);
                    $archivo_old_name = $archivo->nombre;
                    $archivo->nombre = Utils::slug($immagini_lavori->nome) . "." . $archivo->extension;

                    $archivo->update();

                    //modificacion fisica
                    rename(ABSOLUTE_PATH . "img/lavori/" . Utils::slug($this->lavoro->nome) . "/" .
                        $archivo_old_name,
                        ABSOLUTE_PATH . "img/lavori/" . Utils::slug($this->lavoro->nome) . "/" .
                        Utils::slug($immagini_lavori->nome) . "." . $archivo->extension
                    );
                    Flash::valid("L'lavoro <b>" . $immagini_lavori->nome . "</b> è stata modificata");
                } else {
                    Flash::warning("Impossibile modificare l'lavoro <b>" . $immagini_lavori->nome . "</b>");
                }
            } catch (KumbiaException $e) {
                Flash::error("Si è verificato un errore durante la modifica dell'lavoro<b>" .
                    $immagini_lavori->nome . "</b>" . $e->getMessage());
            }


            Redirect::toAction("mostrare/" . $this->immagini_lavori->lavori_id);
        }
    }

    public function mostrare($id)
    {
        View::template("admin");

        $this->title = "Mostrare immagini per il lavoro ";
        $this->menu = "mostrare_immagini";
        $this->lavoro = (new Lavori())->find($id);
        $this->immagini = (new ImmaginiLavori())->getImmagini($id);
        $this->tags = null;
        $this->breadcrumbs = array(array("link" => "admin/dashboard",
            "text" => "Dashboard "),
            array("link" => "lavori/mostrare", "text" => "lavori"),
            array("link" => false, "text" => $this->lavoro->nome),
            array("link" => "immagini_lavori/mostrare/$id",
                "text" => "Immagini", "active" => true));
        if (Input::isAjax()) {
            echo json_encode($this->lavori);
            View::select(null, null);
        }
    }

    public function eliminare($lavoro_id)
    {
        View::template("admin");

        $immagini_lavori = (new ImmaginiLavori())->find($lavoro_id);
        $immagini_lavori_old = $immagini_lavori;
        try {

            if ($immagini_lavori->eliminare($lavoro_id)) {
                Flash::valid("lavoro <b>$immagini_lavori_old->nome </b> eliminata");
            } else {
                Flash::warning("Impossibile eliminare  l'lavoro " . $immagini_lavori_old->nome);

            }
        } catch (KumbiaException $e) {
            Flash::error("Errore al eliminare  l'lavoro " . $immagini_lavori_old->nome .
                "<br>" . $e->getMessage());
        }
        Redirect::toAction("mostrare/" . $immagini_lavori->lavori_id);
    }

    public function fare_principale($immagine_id)
    {
        View::template("admin");

        $this->title = "fare_primaria immagini";
        $this->menu = "fare_primaria";
        $this->tags = null;
        $immagine = "";
//        if (Input::isAjax()) {
        $lavoro_id = (new ImmaginiLavori())->find($immagine_id)->lavori_id;
        $immagini = (new ImmaginiLavori())->getImmagini($lavoro_id);
        try {
            if (count($immagini) > 1) {
                foreach ($immagini as $immagine) {
                    $immagine->principale = FALSE;
                    $immagine->update();
                }

            }
            $immagine = (new ImmaginiLavori())->find($immagine_id);
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
        Redirect::toAction("mostrare/" . $lavoro_id);
//        View::select(null,null); //si es ajax solo mostramos la vista
    }


}