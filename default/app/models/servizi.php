<?php

/**
 * Created by PhpStorm.
 * User: Ezequiel
 * Date: 16/09/2016
 * Time: 04:05 PM
 */
class Servizi extends ActiveRecord
{
//    public function getServizi(){
//        return $this->servizis = array(
//            (object)array("id"=>1,"nombre"=>"Tinteggiature in generale","imagen"=>"servizis/tinteggiature.jpg"),
//            (object)array("id"=>2,"nombre"=>"Rivestimenti decorativi","imagen"=>"servizis/rivestimenti_decorativi.jpg"),
//            (object)array("id"=>3,"nombre"=>"Pavimenti continui in resina","imagen"=>"servizis/resina.jpg"),
//            (object)array("id"=>4,"nombre"=>"Strutture in cartongesso","imagen"=>"servizis/cartongesso.jpg"),
//            (object)array("id"=>5,"nombre"=>"Rimodellazioni","imagen"=>"servizis/rimodellazioni.jpg"),
//            (object)array("id"=>6,"nombre"=>"Recupero e restauro strutture","imagen"=>"servizis/strutture.jpg"),
//            (object)array("id"=>7,"nombre"=>"Patine","imagen"=>"servizis/patine.jpg"),
//            (object)array("id"=>8,"nombre"=>"Stucco veneziano","imagen"=>"servizis/stucco.jpg"),
//            (object)array("id"=>9,"nombre"=>"Progettazione e allestimento","imagen"=>"servizis/allestimento.jpg"),
//
//
//        );
//    }
    public function getImmagini($servizio_id)
    {
        return $this->find_all_by_sql("SELECT I_S.*,A.* FROM immagini_servizi I_S INNER JOIN archivos A 
ON I_S.files_id = A.id INNER JOIN servizi S ON I_S.servizi_id = S.id 
WHERE S.id = " . $servizio_id);
    }

    public function getImmaginePrincipale($servizio_id)
    {
        return $this->find_by_sql("SELECT A.nombre, A.ruta
                                        FROM immagini_servizi I_S INNER JOIN servizi S
                                        ON I_S.servizi_id = S.id
                                        INNER JOIN archivos A ON A.id = I_S.files_id
                                        WHERE  I_S.principale=1 AND S.id=" . $servizio_id);
    }

    public function getServizi()
    {
        return $this->find_all_by_sql("SELECT S.*, F.*,I_S.* 
                                        FROM immagini_servizi I_S INNER JOIN servizi S
                                        ON I_S.servizi_id = S.id
                                        INNER JOIN archivos F ON F.id = I_S.files_id");
    }

    public function eliminare($servizio_id, $path)
    {
        $servizio = $this->find($servizio_id);
        try {
            //Busco las imágenes asociadas al servicio.
            $immagini = (new ImmaginiServizi())->find("servizi_id=" . $servizio->id);
            if (count($immagini) > 0) {

                foreach ($immagini as $item) {
                    $archivo_id = $item->files_id;
                    $item->delete();
                    $archivo = (new Archivos())->find($archivo_id);

                    //Borrado fisico

                    unlink(ABSOLUTE_PATH . $archivo->ruta . "/" . $archivo->nombre);

                    //Borrado logico
                    $archivo->delete();
                }
                //hardcodeado.
                rmdir(ABSOLUTE_PATH . $path . $servizio->nome);
            }
            if ($servizio->delete()) {
                return true;
            }
            return false;
        } catch (KumbiaException $e) {
            return false;
        }
    }

    public function renombrarRutaImagenes($servizio_id, $new_path)
    {

        try {
            //Busco las imágenes asociadas al servicio.
            $immagini = (new ImmaginiServizi())->find("servizi_id=" . $servizio_id);


            if (count($immagini) > 0) {

                foreach ($immagini as $item) {
                    $archivo_id = $item->files_id;

                    $archivo = (new Archivos())->find($archivo_id);

                    //actualizo la ruta del archivo
                    $archivo->ruta = $new_path;
                    $archivo->update();
                }

            }


            return true;
        } catch (KumbiaException $e) {
            return false;
        }
    }

    public function renombrarCarpetaImagenes($old_folder_name, $new_folder_name)
    {

        return rename($old_folder_name, $new_folder_name);

    }

}