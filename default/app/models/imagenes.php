<?php

/**
 * Created by PhpStorm.
 * User: Ezequiel
 * Date: 06/09/2016
 * Time: 03:53 AM
 */
class Imagenes
{


    public function get_galeria(){

        return $this->imagenes =  array(
            (object)array("id" => "1", "path" => "lavoro/1.jpg", "titulo" => "Alcuni esempi dei nostri lavori",
                "clase"=>"instalaciones"),

            (object)array("id" => "3", "path" => "lavoro/3.jpg",
                "titulo" => "Consultorios","clase"=>"consultorios"),
            (object)array("id" => "4", "path" => "lavoro/4.jpg", "titulo" => "Alcuni esempi dei nostri lavori",
                "clase"=>"instalaciones"),
            (object)array("id" => "5", "path" => "lavoro/5.jpg", "titulo" => "Alcuni esempi dei nostri lavori",
                "clase"=>"instalaciones"),
            (object)array("id" => "6", "path" => "lavoro/6.jpg", "titulo" => "Alcuni esempi dei nostri lavori",
                "clase"=>"instalaciones"),
            (object)array("id" => "7", "path" => "lavoro/7.jpg", "titulo" => "Alcuni esempi dei nostri lavori" ,
                "clase"=>"instalaciones"),
            (object)array("id" => "8", "path" => "lavoro/8.jpg", "titulo" => "Alcuni esempi dei nostri lavori" ,
                "clase"=>"instalaciones"),
//            (object)array("id" => "9", "path" => "lavoro/gimnasio3-min.jpg", "titulo" => "Gimnasio" , "clase"=>"instalaciones"),
//            (object)array("id" => "10", "path" => "lavoro/gimnasio4-min.jpg", "titulo" => "Gimnasio" , "clase"=>"instalaciones"),
//            (object)array("id" => "11", "path" => "lavoro/gimnasio5-min.jpg", "titulo" => "Gimnasio" , "clase"=>"instalaciones"),
//            (object)array("id" => "12", "path" => "lavoro/exterior2-min.jpg", "titulo" => "Exterior",
//                "clase"=>"instalaciones"),
//            (object)array("id" => "13", "path" => "lavoro/noche-min.png", "titulo" => "Exterior de noche",
//                "clase"=>"instalaciones"),
//            (object)array("id" => "14", "path" => "lavoro/gerencia-min.jpg", "titulo" => "Gerencia",
//                "clase"=>"eventos"),
//            (object)array("id" => "15", "path" => "lavoro/evento2-min.jpg", "titulo" => "Aniversario 5 años CRM",
//                "clase"=>"eventos"),
//            (object)array("id" => "16", "path" => "lavoro/folletos-min.jpg", "titulo" => "Aniversario 5 años CRM",
//                "clase"=>"eventos"),
//            (object)array("id" => "17", "path" => "lavoro/evento-min.jpg", "titulo" => " Aniversario 5 años CRM",
//                "clase"=>"eventos"),
//            (object)array("id" => "18", "path" => "lavoro/evento3-min.jpg", "titulo" => " Aniversario 5 años CRM",
//                "clase"=>"eventos"),
//            (object)array("id" => "19", "path" => "lavoro/evento4-min.jpg", "titulo" => " Aniversario 5 años CRM",
//                "clase"=>"eventos"),
//            (object)array("id" => "20", "path" => "lavoro/consultorio3-min.jpg", "titulo" => " Consultorio",
//                "clase"=>"instalaciones"),
        );

    }
}