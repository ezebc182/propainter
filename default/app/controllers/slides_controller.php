<?php

/**
 * Created by PhpStorm.
 * User: Ezequiel
 * Date: 01/10/2016
 * Time: 09:27 PM
 */
class SlidesController extends AppController
{
    public function index()
    {
        Redirect::toAction("mostrare");
    }

    public function mostrare()
    {
        $this->title = "Slides";
        $this->menu = "slides";
        $this->tags = null;
        View::template("admin");
        $this->slides = (new Slides())->find();
        $this->breadcrumbs = array(
            array("link" => "admin/dashboard", "text" => "Dashboard"),
            array("link" => "slides/mostrare", "text" => "Slides", "active" => true));
    }

    public function aggiungere()
    {
        $this->title = "Slides";
        $this->menu = "slides";
        $this->tags = null;
        View::template("admin");
        $this->cantidad = (new Slides())->count();
        $this->breadcrumbs = array(
            array("link" => "admin/dashboard", "text" => "Dashboard"),
            array("link" => "slides/mostrare", "text" => "Slides"),
            array("link" => "slides/aggiungere", "text" => "Aggiungere", "active" => true));
        if (Input::hasPost("slides")) {
            try {


                if (Utils::maxPostCheck()) {


                    if ($_FILES["file"]['error'] != UPLOAD_ERR_NO_FILE) {
                        $nombre_archivo = Utils::slug(Input::post("slides.nome"));
                        $archivos = new Archivos();
                        $ruta = "slides/";


                        $_archivo = $archivos->uploadArchivo("file", $ruta, "image", $nombre_archivo);

                        if ($_archivo != NULL) {
                            $slides = (new Slides(Input::post("slides")));
                            $slides->create();
                            if ($_archivo->create()) {
                                $slides->archivos_id = $_archivo->id;


                                if ($slides->update()) {

                                    Flash::valid("Il slide <b>" . $slides->nome . "</b> 
                                è stato caricato con successo.");
                                } else {
                                    Flash::warning("No se pudo sincronizar el archivo y la imagen.");
                                }
                            } else {
                                Flash::warning("No se pudo crear el archivo en el servidor.");
                            }
                        } else {
                            Flash::warning("Ocurrió un error al crear el archivo en la base de datos.");
                        }


                    } else {
                        Flash::warning("Error en la subida del archivo");
                    }
                } else {
                    Flash::error("Error en el tamaño permitido por el server.");
                }

            } catch (KumbiaException $e) {
                Flash::error("Error. " . $e->getMessage());
            }

            Redirect::toAction("mostrare/");
        }

    }

    public function modificare($id)
    {
        $this->menu = "modificare_slide";
        $this->tags = null;
        View::template("admin");
        $this->slides = (new Slides())->find($id);
        $this->archivos = (new Archivos())->find($this->slides->archivos_id);
        $this->title = "Modificare il slide: <b>".$this->slides->nome."</b>";

        $this->breadcrumbs = array(
            array("link" => "admin/dashboard", "text" => "Dashboard"),
            array("link" => "slides/mostrare", "text" => "Slides"),
            array("link" => false, "text" => $this->slides->nome),
            array("link" => "slides/modificare/$id", "text" => "Modificare", "active" => true));
        if (Input::hasPost("slides")) {

            $slides = (new Slides(Input::post("slides")));
            try {
                if ($slides->update()) {
                    //modificacion logica
                    $archivo = (new Archivos())->find($slides->archivos_id);
                    $archivo_old_name = $archivo->nombre;
                    $archivo->nombre = $slides->nome . "." . $archivo->extension;

                    $archivo->update();

                    //modificacion fisica
                    rename(ABSOLUTE_PATH . "img/slides/" . $archivo_old_name,
                        ABSOLUTE_PATH . "img/slides/" . $slides->nome . "." . $archivo->extension
                    );
                    Flash::valid("Il slide <b>" . $slides->nome . "</b> è stata modificata");
                } else {
                    Flash::warning("Impossibile modificare l'immagine <b>" . $slides->nome . "</b>");
                }
            } catch (KumbiaException $e) {
                Flash::error("Si è verificato un errore durante la modifica del slide<b>" .
                    $slides->nome . "</b>" . $e->getMessage());
            }


            Redirect::toAction("mostrare/");
        }
    }

    public function eliminare($id)
    {
        View::template("admin");
        $slides = (new Slides())->find($id);
        $slide_old = $slides;
        try {

            if ($slides->eliminare($id)) {
                Flash::valid("Immagine <b>$slide_old->nome </b> eliminata");
            } else {
                Flash::warning("Impossibile eliminare  il slide " . $slide_old->nome);

            }
        } catch (KumbiaException $e) {
            Flash::error("Errore al eliminare  il slide " . $slide_old->nome .
                "<br>" . $e->getMessage());
        }
        Redirect::toAction("mostrare/");
    }
}