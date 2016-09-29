<?php

/**
 * Created by PhpStorm.
 * User: Ezequiel
 * Date: 30/08/2016
 * Time: 05:32 PM
 */
class Archivos extends ActiveRecord
{
    public function uploadCsv($field, $ruta)
    {

        $file = Upload::factory($field, "file");


        if (!file_exists($ruta)) {
            $oldumask = umask(0);
            mkdir($ruta, 0755, true);
            umask($oldumask);
        }

        $file->setPath($ruta);

        // extensiones permitidas
        $file->setTypes(array('csv'));
        $file->setExtensions(array('csv'));

        if ($name = $file->save()) {
            return $name;
        }

        return false;
    }

    /* Subir archivos del tipo foto o imagen
     * @param string $field Campo del formulario. (nombre del elemento $_FILES)
     * @param sring $ruta Ruta donde se va a subir el archivo
     * @param sring $tipo image | file
     * @param string $archivo_anterior_id Id del archivo anterior si es que existe.
     *              Se borrara en caso de subir uno nuevo
     * @return string Nombre random del archivo subido
     */
    public function uploadArchivo($field, $ruta = "", $tipo = "", $nombre_archivo = "", $datos = "")
    {

        if ($tipo == "") {
            $tipo = $this->getTipoByMime($_FILES[$field]["type"]);
        }
        $upload = Upload::factory($field, $tipo);
        $upload->overwrite(true);

        $image_sizes = "";
        if ($tipo == "image") {
            $upload->setExtensions(array('jpg', 'png', 'gif', 'jpeg'));
            $ruta = "img/" . $ruta;
            $image_sizes = getimagesize($_FILES[$field]['tmp_name']);
        } elseif ($tipo == "file") {
            $upload->setExtensions(array('doc', 'docx', 'pdf', 'zip', 'rar'));
            $ruta = "files/" . $ruta;

        }


        $ruta_absoluta = $ruta;
        $extension = self::_getExtension($_FILES[$field]['name']);

        $archivos = new Archivos();
        $archivos->extension = $extension;
        $archivos->nombre = $nombre_archivo . "." . $extension;
        $archivos->ruta = $ruta;
        $archivos->filesize = $_FILES[$field]["size"];
        $archivos->width = (isset($image_sizes[0])) ? $image_sizes[0] : "";
        $archivos->height = (isset($image_sizes[1])) ? $image_sizes[1] : "";
//        if (!file_exists($ruta_absoluta)){ Utils::crear_directorio($ruta_absoluta); }

        if (!file_exists($ruta_absoluta)) {
            Utils::crear_directorio($ruta_absoluta);

        }
        $upload->setPath($ruta_absoluta);




        if ($upload->isUploaded()) {
            if ($nombre = $upload->save($nombre_archivo)) {

                // si existe foto la borramos
//                if ($archivo_anterior_id != "" && !empty($archivo_anterior_id)){
//                    $archivo_anterior = $this->find($archivo_anterior_id);
//                    $archivo_anterior->del();
//                }

                $archivos->mime = $this->getMimeType($ruta_absoluta . "/" . $archivos->nombre);
                $archivos->mime = "";

                if ($archivos->create()) {
                    return $archivos;
                } else {
                    // si no se puede guardar el archivo, lo borramos
                    unlink($ruta_absoluta . $archivos->nombre);
                    return false;
                }


            } else {
                Flash::error("Impossibile caricare l'immagine <b>" . $archivos->nombre . "</b>");
            }
        }

        return false;


    }

    public function del($ruta_al_archivo)
    {
        $archivo = ABSOLUTE_PATH . $ruta_al_archivo;

        // si existe el archivo lo borramos
        if (file_exists($archivo)) {
            unlink($archivo);
        }

        return $this->delete();
    }

    /**
     * Subimos multiples imagenes
     * @param string $field_prefix
     * @param string $tipos Tipo de imagen
     */
    public function subirMultiple($field_prefix, $tipo = "")
    {
        // ordenamos los elementos
        $this->fixFilesArray($_FILES[$field_prefix]);

        $i = 0;
        if (count($_FILES[$field_prefix]) > 0) {
            // buscamos tipo de imagen

            foreach ($_FILES[$field_prefix] as $file) {
                $prefix = $field_prefix . '_' . $i;
                $_FILES[$prefix] = $file;

                if ($file['name'] != "") {
                    $archivos = new Archivos();
                    if ($archivos = $this->uploadArchivo($prefix)) {

                    }
                }

                $i++;
            }
        }
    }

    /* Obtenemos el tipo de archivo a utilizar en el adapter de la clase Upload
     * segun el mime type del archivo subido
     * @param string $mime Mime type del archivo subido
     * @return string Devolvemos "file" o "image" para el adapter de la clase Upload
     */
    public function getTipoByMime($mime)
    {
        switch ($mime) {
            case "image/jpeg":
            case "image/pjpeg":
            case "image/png":
            case "image/gif":
                return "image";
                break;

            case "application/pdf":
            case "application/msword":
            case "application/vnd.openxmlformats-officedocument.wordprocessingml.document":
                return "file";
                break;

            default:
                return "file";
                break;
        }


    }

    // TODO: Deprecated
    /* Obtenemos el mime a traves del nombre del archivo
     * segun el mime type del archivo subido
     * @param string $filename Nombre del archivo a obtener el mime
     * @return string Mime type
     */
    public function getMimeType($filename)
    {
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mime = finfo_file($finfo, $filename);
        finfo_close($finfo);
        return $mime;
    }

    /*
     * Funcion que me devuelve el nombre unicamente (sin extension) de un archivo
     * @param string $file : Nombre de archivo
     * return string Nombre del archivo
     */
    private function _getName($file)
    {
        return pathinfo($file, PATHINFO_FILENAME);
    }

    /*
     * Funcion que me devuelve el ruta unicamente de un archivo
     * @param string $file : Nombre de archivo
     * return string Nombre del archivo
     */
    private function _getRuta($file)
    {
        return pathinfo($file, PATHINFO_DIRNAME);
    }

    private function _getExtension($file)
    {
        return pathinfo($file, PATHINFO_EXTENSION);
    }

    /**
     * Fixes the odd indexing of multiple file uploads from the format:
     *
     * $_FILES['field']['key']['index']
     *
     * To the more standard and appropriate:
     *
     * $_FILES['field']['index']['key']
     *
     * @param array $files
     * @author Corey Ballou
     * @link http://www.jqueryin.com
     */
    static public function fixFilesArray(&$files)
    {
        $names = array('name' => 1, 'type' => 1, 'tmp_name' => 1, 'error' => 1, 'size' => 1);

        if (count($files) > 0) {
            foreach ($files as $key => $part) {
                // only deal with valid keys and multiple files
                $key = (string)$key;
                if (isset($names[$key]) && is_array($part)) {
                    foreach ($part as $position => $value) {
                        $files[$position][$key] = $value;
                    }
                    // remove old key reference
                    unset($files[$key]);
                }
            }
        }
    }
}