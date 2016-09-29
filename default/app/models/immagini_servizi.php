<?php

/**
 * Created by PhpStorm.
 * User: Ezequiel
 * Date: 23/09/2016
 * Time: 04:25 PM
 */
class ImmaginiServizi extends ActiveRecord
{
    public function getImmaginiServizi($servizi_id)
    {
        return $this->find_all_by_sql("SELECT A.*,I_S.* FROM immagini_servizi I_S INNER JOIN archivos A 
ON I_S.files_id = A.id INNER JOIN servizi S ON I_S.servizi_id = S.id WHERE S.id = " . $servizi_id);

    }
    public function obtenerUltimoID($servizi_id){
        return $this->find_by_sql("SELECT COUNT(id) FROM immagini_servizi WHERE servizi_id=".$servizi_id);

    }


    public function eliminare($immagine_id)
    {
        try {
            $immagine = (new ImmaginiServizi())->find($immagine_id);
            $archivo = (new Archivos())->find($immagine->files_id);

            if ($immagine->delete()) {

                unlink(ABSOLUTE_PATH . $archivo->ruta ."/". $archivo->nombre);

                //Borrado logico
                $archivo->delete();

                return true;
            }
            return false;
        } catch (KumbiaException $e) {
            return false;
        }

    }


}