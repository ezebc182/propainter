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
        $this->slides = (new Slides)->find();



    }

    public function chi_siamo()
    {
        $this->title = "Chi siamo";
        $this->menu = "chi_siamo";
        $this->tags = (new Tags)->getTags();
        $this->prodotti = (new Prodotti())->find();


    }

}