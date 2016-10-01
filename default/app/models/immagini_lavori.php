<?php

/**
 * Created by PhpStorm.
 * User: Ezequiel
 * Date: 30/09/2016
 * Time: 11:32 AM
 */
class ImmaginiLavori extends ActiveRecord
{
    public function getImmagini($lavori_id)
    {
        return $this->find_all_by_sql("SELECT A.*,I_L.* FROM immagini_lavori I_L INNER JOIN archivos A 
ON I_L.files_id = A.id INNER JOIN lavori S ON I_L.lavori_id = S.id WHERE S.id = " . $lavori_id);

    }
    public function obtenerUltimoID($lavori_id){
        return $this->find_by_sql("SELECT COUNT(id) FROM immagini_lavori WHERE lavori_id=".$lavori_id);

    }
    public function countImmagine($id){
        return $this->count_by_sql("
        select count(id) from immagini_lavori where lavori_id=" . $id . " group by lavori_id");
    }

    public function eliminare($immagine_id)
    {
        try {
            $immagine = (new Immaginilavori())->find($immagine_id);
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