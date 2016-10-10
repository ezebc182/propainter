<?php
/**
 * @see Controller nuevo controller
 */
require_once CORE_PATH . 'kumbia/controller.php';

/**
 * Controlador principal que heredan los controladores
 *
 * Todas las controladores heredan de esta clase en un nivel superior
 * por lo tanto los metodos aqui definidos estan disponibles para
 * cualquier controlador.
 *
 * @category Kumbia
 * @package Controller
 */
class AppController extends Controller
{

    final protected function initialize()
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        

        $this->utenti = (new Utenti())->getUtenti();
        $this->prodotti = (new Prodotti())->find();
        View::template("material");
    }
    final protected function before_filter()
    {
        if(in_array(Router::get("controller"),array("slides","lavori","servizis","prodotti") )){
            if(!in_array(Router::get("action"),array("index") )){
                $auth = Auth2::factory("model");

                if($auth->isValid()){

                    View::template("admin");
                }else{
                    Redirect::to("login");
                }
            }
        }
    }

    final protected function finalize()
    {
        
    }

}
