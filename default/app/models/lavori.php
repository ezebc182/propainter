<?php

/**
 * Created by PhpStorm.
 * User: Ezequiel
 * Date: 30/09/2016
 * Time: 11:46 AM
 */
class Lavori extends ActiveRecord
{
    public function getImmagini($lavoro_id)
    {
        return $this->find_all_by_sql("SELECT I_L.*,A.* FROM immagini_lavori I_L INNER JOIN archivos A 
ON I_L.files_id = A.id INNER JOIN lavori L ON I_L.lavori_id = L.id 
WHERE L.id = " . $lavoro_id);
    }
    public function getImmaginePrincipale($lavoro_id)
    {
        return $this->find_by_sql("SELECT A.nombre, A.ruta
                                        FROM immagini_lavori I_L INNER JOIN lavori L
                                        ON I_L.lavori_id = L.id
                                        INNER JOIN archivos A ON A.id = I_L.files_id
                                        WHERE  I_L.principale=1 AND L.id=" . $lavoro_id);
    }

    public function getLavori()
    {
        return $this->find_all_by_sql("SELECT S.*, F.*,I_L.* 
                                        FROM immagini_lavori I_L INNER JOIN lavori L
                                        ON I_L.lavori_id = L.id
                                        INNER JOIN archivos F ON F.id = I_L.files_id");
    }

    public function eliminare($lavoro_id, $path)
    {
        $lavoro = $this->find($lavoro_id);
        try {
            //Busco las imágenes asociadas al servicio.
            $immagini = (new ImmaginiLavori())->find("lavori_id=" . $lavoro->id);
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
                rmdir(ABSOLUTE_PATH . $path . Utils::slug($lavoro->nome));
            }
            if ($lavoro->delete()) {
                return true;
            }
            return false;
        } catch (KumbiaException $e) {
            return false;
        }
    }
    

    public function renombrarRutaImagenes($lavoro_id, $new_path)
    {

        try {
            //Busco las imágenes asociadas al servicio.
            $immagini = (new ImmaginiLavori())->find("lavori_id=" . $lavoro_id);


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