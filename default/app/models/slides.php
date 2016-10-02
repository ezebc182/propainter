<?php

/**
 * Created by PhpStorm.
 * User: Ezequiel
 * Date: 02/09/2016
 * Time: 08:01 PM
 */
class Slides extends ActiveRecord
{
    public function getExtension($slide_id)
    {
        return $this->find_by_sql("SELECT A.extension FROM archivos A INNER JOIN slides S ON S.archivos_id = A.id 
          WHERE S.id=" . $slide_id);
    }
    public function getImmagine($slide_id){
        return $this->find_by_sql("SELECT A.ruta,A.nombre,A.extension FROM archivos A INNER JOIN slides S ON S.archivos_id = A.id 
          WHERE S.id=" . $slide_id);
    }

    public function eliminare($slide_id)
    {
        try {
            $slide = (new Slides())->find($slide_id);
            $archivo = (new Archivos())->find($slide->archivos_id);

            if ($slide->delete()) {

                unlink(ABSOLUTE_PATH . $archivo->ruta . "/" . $archivo->nombre);

                //Borrado logico
                $archivo->delete();

                return true;
            }
            return false;
        } catch (KumbiaException $e) {
            return false;
        }

    }


    public function countImmagini()
    {
        return $this->count_by_sql("
        select count(id) from slides");
    }
}