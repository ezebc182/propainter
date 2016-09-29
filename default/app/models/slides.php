<?php

/**
 * Created by PhpStorm.
 * User: Ezequiel
 * Date: 02/09/2016
 * Time: 08:01 PM
 */
class Slides
{
    public function getSlides()
    {
        return $this->slides = array(
            (object)array("id" => "1", "img" => "slides/slide1.jpg", "titulo" => "Esempi dei nostri lavori",
                "mensaje" => "Esempi dei nostri lavori!",
                "direccion"=>" left-align"),
            (object)array("id" => "2", "img" => "slides/slide2.jpg", "titulo" => "Esempi dei nostri lavori",
                "mensaje" => "Esempi dei nostri lavori",
                "direccion"=>"left-align"),
            (object)array("id" => "3", "img" => "slides/slide3.jpg",
                "titulo" => "Esempi dei nostri lavori",
                "mensaje" => "Esempi dei nostri lavori",
                "direccion"=>"left-align"),
            (object)array("id" => "4", "img" => "slides/slide4.jpg", "titulo" => "Esempi dei nostri lavori",
                "mensaje" => "Esempi dei nostri lavori",
                "direccion"=>" left-align"),

        );
    }
}