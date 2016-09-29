<?php

/**
 * Controller por defecto si no se usa el routes
 *
 */
class IndexController extends AppController
{
    
    public function index()
    {

        $this->title = "Home";
        $this->menu = "home";
        $this->tags = (new Tags)->getTags();
        $this->slides = (new Slides)->getSlides();

     
    }

    public function chi_siamo()
    {
        $this->title = "Chi siamo";
        $this->menu = "chi_siamo";
        $this->tags = (new Tags)->getTags();


    }
    public function nostri_lavori(){
        $this->title = "Nostri lavori";
        $this->menu = "nostri_lavori";
        $this->tags = (new Tags)->getTags();
        $this->imagenes_galeria = (new Imagenes())->get_galeria();
    }

//    public function servizis(){
//        $this->title = "Servizi";
//        $this->menu = "servizis";
//        $this->tags = (new Tags)->getTags();
//        $this->servizis = (new Servizi())->getServizi();
//
//    }


}