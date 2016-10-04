<?php

/**
 * Created by PhpStorm.
 * User: Ezequiel
 * Date: 31/08/2016
 * Time: 11:24 PM
 */
class AdminController extends AppController
{
    public function index()
    {
        $this->tags = null;
        $this->menu = null;
        $this->title = "Index";


    }

    public function dashboard()
    {
        $auth = Auth2::factory("model");

        if($auth->isValid()){
//            Redirect::to("admin/dashboard");
            $this->title="Dashboard";
            View::template("admin");
        }else{
            Redirect::to("login");
        }


    }

    public function getMaxFileSize($val)
    {
        if (Input::isAjax()) {
            if (Utils::returnBytes(ini_get('upload_max_filesize')) < $val) {
                Flash::warning("El archivo supera el tamaño permitido por el servidor: "
                    . ini_get("upload_max_filesize"));
            }
        }
        View::select(null, null);

    }

    public function getFileType()
    {

        if (Input::isAjax()) {

            if (!in_array(Input::post("fileType"), array("image/jpeg", "image/png", "image/png"))) {
                Flash::warning("El archivo no es un formato válido. Sólo se permiten <b>.JPG</b>,<b>.JPEG</b> o <b>.PNG</b>");

            }
            //Flash::info("El tipo de archivo es: " . Input::post("fileType"));


        }
        View::select(null, null);
    }

}