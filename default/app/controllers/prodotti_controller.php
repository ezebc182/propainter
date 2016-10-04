<?php

/**
 * Created by PhpStorm.
 * User: Ezequiel
 * Date: 04/10/2016
 * Time: 12:17 AM
 */
class ProdottiController extends AppController
{
    public function index()
    {
        Redirect::toAction("mostrare");
    }

    public function mostrare()
    {
        $this->title = "prodotti";
        $this->menu = "prodotti";
        $this->tags = null;
        View::template("admin");
        $this->prodotti = (new Prodotti())->find();
        $this->breadcrumbs = array(
            array("link" => "admin/dashboard", "text" => "Dashboard"),
            array("link" => "prodotti/mostrare", "text" => "prodotti", "active" => true));
    }

    public function aggiungere()
    {
        $this->title = "Aggiungere un nouvo prodotto";
        $this->menu = "prodotti";
        $this->tags = null;
        View::template("admin");
        $this->cantidad = (new Prodotti())->count();
        $this->breadcrumbs = array(
            array("link" => "admin/dashboard", "text" => "Dashboard"),
            array("link" => "prodotti/mostrare", "text" => "prodotti"),
            array("link" => "prodotti/aggiungere", "text" => "Aggiungere", "active" => true));
        if (Input::hasPost("prodotti")) {
            try {


                if (Utils::maxPostCheck()) {


                    if ($_FILES["file"]['error'] != UPLOAD_ERR_NO_FILE) {
                        $nombre_archivo = Utils::slug(Input::post("prodotti.nome"));
                        $archivos = new Archivos();
                        $ruta = "prodotti/" . $nombre_archivo . "/";


                        $_archivo = $archivos->uploadArchivo("file", $ruta, "image", $nombre_archivo);

                        if ($_archivo != NULL) {
                            $prodotti = (new Prodotti(Input::post("prodotti")));
                            $prodotti->create();

                            $prodotti->archivos_id = $_archivo->id;


                            if ($prodotti->update()) {

                                Flash::valid("Il prodotto <b>" . $prodotti->nome . "</b> 
                                è stato caricato con successo.");
                            } else {
                                Flash::warning("No se pudo sincronizar el archivo y la imagen.");
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
        $this->menu = "modificare_prodotto";
        $this->tags = null;
        View::template("admin");
        $this->prodotti = (new Prodotti())->find($id);
        $this->archivos = (new Archivos())->find($this->prodotti->archivos_id);
        $this->title = "Modificare il prodotto: <b>" . $this->prodotti->nome . "</b>";

        $this->breadcrumbs = array(
            array("link" => "admin/dashboard", "text" => "Dashboard"),
            array("link" => "prodotti/mostrare", "text" => "Prodotti"),
            array("link" => false, "text" => $this->prodotti->nome),
            array("link" => "prodotti/modificare/$id", "text" => "Modificare", "active" => true));
        if (Input::hasPost("prodotti")) {

            $prodotti = (new Prodotti(Input::post("prodotti")));
            try {
                if ($prodotti->update()) {
                    //modificacion logica
                    $archivo = (new Archivos())->find($prodotti->archivos_id);
                    $archivo_old_name = $archivo->nombre;
                    $arhivo_old_ruta = $archivo->ruta;
                    $archivo_old_extension = $archivo->extension;
                    $archivo->nombre = $prodotti->nome . "." . $archivo->extension;

                    $archivo->update();

                    //modificacion fisica
                    $prodotti->renombrarCarpetaImagenes(ABSOLUTE_PATH . "img/prodotti/". Utils::slug($this->prodotti->nome),
                        ABSOLUTE_PATH . "img/prodotti/" . Utils::slug($prodotti->nome));

                    rename(ABSOLUTE_PATH."img/prodotti/".Utils::slug($prodotti->nome)."/".
                        $archivo_old_name.".".$archivo_old_extension,
                        ABSOLUTE_PATH."img/prodotti/".Utils::slug($prodotti->nome)."/".
                        Utils::slug($prodotti->nome).".".$prodotti->extension
                        );
                    $prodotti->renombrarRutaImagenes($prodotti->id,"img/prodotti/" . Utils::slug($prodotti->nome));


                    Flash::valid("Il prodotto <b>" . $prodotti->nome . "</b> è stata modificata");
                } else {
                    Flash::warning("Impossibile modificare l'immagine del prodotto <b>" . $prodotti->nome . "</b>");
                }
            } catch (KumbiaException $e) {
                Flash::error("Si è verificato un errore durante la modifica del prodotto<b>" .
                    $prodotti->nome . "</b>" . $e->getMessage());
            }


            Redirect::toAction("mostrare/");
        }
    }

    public function eliminare($id)
    {
        View::template("admin");
        $prodotti = (new Prodotti())->find($id);
        $prodotto_old = $prodotti;
        try {

            if ($prodotti->eliminare($id)) {
                Flash::valid("Immagine <b>$prodotto_old->nome </b> eliminata");
            } else {
                Flash::warning("Impossibile eliminare  il slide " . $prodotto_old->nome);

            }
        } catch (KumbiaException $e) {
            Flash::error("Errore al eliminare  il slide " . $prodotto_old->nome .
                "<br>" . $e->getMessage());
        }
        Redirect::toAction("mostrare/");
    }
}