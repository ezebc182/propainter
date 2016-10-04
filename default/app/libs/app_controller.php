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
        View::template("material");
    }

    final protected function finalize()
    {
        
    }

}
