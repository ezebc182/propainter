<?php

/**
 * Created by PhpStorm.
 * User: Ezequiel
 * Date: 01/10/2016
 * Time: 09:27 PM
 */
class SlidesController extends AppController
{
    public function index(){}
    public function mostrare(){
        $this->title="Slides";
        $this->menu = "slides";
        $this->tags = null;
        $this->slides = (new Slides())->find();
    }
    public function aggiungere(){
        $this->title="Slides";
        $this->menu = "slides";
        $this->tags = null;
        //$this->slides = (new Slides())->find();
    }
    public function modificare($id){

    }
    public function eliminare($id){

    }
}