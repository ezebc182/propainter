<?php

/**
 * Created by PhpStorm.
 * User: Ezequiel
 * Date: 04/10/2016
 * Time: 12:23 AM
 */
class Prodotti extends ActiveRecord
{
    public function getExtension($prodotti_id)
    {
        return $this->find_by_sql("SELECT A.extension FROM archivos A 
INNER JOIN prodotti P ON P.archivos_id = A.id 
          WHERE P.id=" . $prodotti_id);
    }
    public function getImmagine($prodotti_id){
        return $this->find_by_sql("SELECT A.ruta,A.nombre,A.extension FROM archivos A INNER JOIN prodotti P ON P.archivos_id = A.id 
          WHERE P.id=" . $prodotti_id);
    }

    public function eliminare($prodotti_id)
    {
        try {
            $prodotto = (new Prodotti())->find($prodotti_id);
            $archivo = (new Archivos())->find($prodotto->archivos_id);

            if ($prodotto->delete()) {

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

    public function renombrarRutaImagenes($prodotto_id, $new_path)
    {

        try {
            //Busco las imágenes asociadas al servicio.
            $immagini = (new Prodotti())->find("id=" . $prodotto_id);


            if (count($immagini) > 0) {

                foreach ($immagini as $item) {
                    $archivo_id = $item->archivos_id;

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

        return rename(Utils::slug($old_folder_name),Utils::slug($new_folder_name));

    }

    public function countImmagini()
    {
        return $this->count_by_sql("
        select count(id) from prodotti");
    }
}