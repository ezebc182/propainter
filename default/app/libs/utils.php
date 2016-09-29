<?php
/**
 * Created by PhpStorm.
 * User: Ezequiel
 * Date: 31/08/2016
 * Time: 05:37 PM
 */
class Utils
{
    public static function slug ($string, $separator = '-', $length = 100)
    {
        $search = explode(',', 'ç,Ç,ñ,Ñ,æ,Æ,œ,á,Á,é,É,í,Í,ó,Ó,ú,Ú,à,À,è,È,ì,Ì,ò,Ò,ù,Ù,ä,ë,ï,Ï,ö,ü,ÿ,â,ê,î,ô,û,å,e,i,ø,u,Š,Œ,Ž,š,¥');
        $replace = explode(',', 'c,C,n,N,ae,AE,oe,a,A,e,E,i,I,o,O,u,U,a,A,e,E,i,I,o,O,u,U,ae,e,i,I,oe,ue,y,a,e,i,o,u,a,e,i,o,u,s,o,z,s,Y');
        $string = str_replace($search, $replace, $string);
        $string = strtolower($string);
        $string = preg_replace('/[^a-z0-9_]/i', $separator, $string);
        $string = preg_replace('/\\' . $separator . '[\\' . $separator . ']*/', $separator, $string);
        if (strlen($string) > $length) {
            $string = substr($string, 0, $length);
        }
        $string = preg_replace('/\\' . $separator . '$/', '', $string);
        $string = preg_replace('/^\\' . $separator . '/', '', $string);
        return $string;
    }
    /**
     * Truncates text.
     *
     * Cuts a string to the length of $length and replaces the last characters
     * with the ending if the text is longer than length.
     *
     * @param string  $text String to truncate.
     * @param integer $length Length of returned string, including ellipsis.
     * @param mixed   $ending If string, will be used as Ending and appended to the trimmed string. Can also be an associative array that can contain the last three params of this method.
     * @param boolean $exact If false, $text will not be cut mid-word
     * @param boolean $considerHtml If true, HTML tags would be handled correctly
     * @return string Trimmed string.
     */
    public static function truncate($text, $length = 100, $dot = '...', $exact = true, $considerHtml = false) {

        if ($considerHtml) {
            if (mb_strlen(preg_replace('/<.*?>/', '', $text)) <= $length) {
                return $text;
            }
            $totalLength = mb_strlen($dot);
            $openTags = array();
            $truncate = '';
            preg_match_all('/(<\/?([\w+]+)[^>]*>)?([^<>]*)/', $text, $tags, PREG_SET_ORDER);
            foreach ($tags as $tag) {
                if (!preg_match('/img|br|input|hr|area|base|basefont|col|frame|isindex|link|meta|param/s', $tag[2])) {
                    if (preg_match('/<[\w]+[^>]*>/s', $tag[0])) {
                        array_unshift($openTags, $tag[2]);
                    } else if (preg_match('/<\/([\w]+)[^>]*>/s', $tag[0], $closeTag)) {
                        $pos = array_search($closeTag[1], $openTags);
                        if ($pos !== false) {
                            array_splice($openTags, $pos, 1);
                        }
                    }
                }
                $truncate .= $tag[1];

                $contentLength = mb_strlen(preg_replace('/&[0-9a-z]{2,8};|&#[0-9]{1,7};|&#x[0-9a-f]{1,6};/i', ' ', $tag[3]));
                if ($contentLength + $totalLength > $length) {
                    $left = $length - $totalLength;
                    $entitiesLength = 0;
                    if (preg_match_all('/&[0-9a-z]{2,8};|&#[0-9]{1,7};|&#x[0-9a-f]{1,6};/i', $tag[3], $entities, PREG_OFFSET_CAPTURE)) {
                        foreach ($entities[0] as $entity) {
                            if ($entity[1] + 1 - $entitiesLength <= $left) {
                                $left--;
                                $entitiesLength += mb_strlen($entity[0]);
                            } else {
                                break;
                            }
                        }
                    }

                    $truncate .= mb_substr($tag[3], 0 , $left + $entitiesLength);
                    break;
                } else {
                    $truncate .= $tag[3];
                    $totalLength += $contentLength;
                }
                if ($totalLength >= $length) {
                    break;
                }
            }

        } else {
            if (mb_strlen($text) <= $length) {
                return $text;
            } else {
                $truncate = mb_substr($text, 0, $length - strlen($dot));
            }
        }
        if (!$exact) {
            $spacepos = mb_strrpos($truncate, ' ');
            if (isset($spacepos)) {
                if ($considerHtml) {
                    $bits = mb_substr($truncate, $spacepos);
                    preg_match_all('/<\/([a-z]+)>/', $bits, $droppedTags, PREG_SET_ORDER);
                    if (!empty($droppedTags)) {
                        foreach ($droppedTags as $closingTag) {
                            if (!in_array($closingTag[1], $openTags)) {
                                array_unshift($openTags, $closingTag[1]);
                            }
                        }
                    }
                }
                $truncate = mb_substr($truncate, 0, $spacepos);
            }
        }

        $truncate .= $dot;

        if ($considerHtml) {
            foreach ($openTags as $tag) {
                $truncate .= '</'.$tag.'>';
            }
        }
        return $truncate;
    }

    /**
     * Normaliza el nombre de un archivo
     *
     * @param $string cadena a convertir
     * @param $separador texto a usar de separador , default = "_"
     * @param $length largo del nombre
     * @return String
     */
    public static function normalizar_nombre_archivo ($string, $separator = '_', $length = 100){
        $search = explode(',', 'ç,Ç,ñ,Ñ,æ,Æ,œ,á,Á,é,É,í,Í,ó,Ó,ú,Ú,à,À,è,È,ì,Ì,ò,Ò,ù,Ù,ä,ë,ï,Ï,ö,ü,ÿ,â,ê,î,ô,û,å,e,i,ø,u,Š,Œ,Ž,š,¥');
        $replace = explode(',', 'c,C,n,N,ae,AE,oe,a,A,e,E,i,I,o,O,u,U,a,A,e,E,i,I,o,O,u,U,ae,e,i,I,oe,ue,y,a,e,i,o,u,a,e,i,o,u,s,o,z,s,Y');
        $string = str_replace($search, $replace, $string);
        //$string = strtolower($string);
        $string = preg_replace('/[^a-z0-9_.]/i', $separator, $string);
        $string = preg_replace('/\\' . $separator . '[\\' . $separator . ']*/', $separator, $string);
        if (strlen($string) > $length) {
            $string = substr($string, 0, $length);
        }
        $string = preg_replace('/\\' . $separator . '$/', '', $string);
        $string = preg_replace('/^\\' . $separator . '/', '', $string);
        return $string;
    }

    /*
    * Crea un directorio
    * @param string $ruta Ruta para crear el directorio
    */
    public static function crear_directorio($ruta){
        $oldumask = umask(0);
        $operacion = mkdir($ruta, 0755, true);
        umask($oldumask);
        return $operacion;
    }


    // http://php.net/manual/en/function.ini-get.php#96996
    public static function returnBytes ($size_str){
        switch (substr ($size_str, -1))	{
            case 'M': case 'm': return (int)$size_str * 1048576;
            case 'K': case 'k': return (int)$size_str * 1024;
            case 'G': case 'g': return (int)$size_str * 1073741824;
            default: return $size_str;
        }
    }

    /* Funcion para checkear si se ha excedido el máximo a enviar por formulario */
    public static function maxPostCheck(){
        $max_post = ini_get('post_max_size');
        // transformamos el valor en bytes para comprobarlo frente a
        // la variable $_SERVER["CONTENT_LENGTH"] que es en bytes
        $max_post_bytes = self::returnBytes($max_post);

        if (isset($_SERVER["CONTENT_LENGTH"]) &&
            $_SERVER["CONTENT_LENGTH"] > $max_post_bytes){

            Flash::error("Ha excedido el límite del servidor de enviar " . $max_post . "B por formulario.
			 Trate de subir menos cantidad de archivos. <br />
			 En el caso que esté subiendo un solo archivo, vea de comprimirlo, si no, contactese con el administrador del sistema." );
            return false;
        }

        return true;
    }
    public static function limpiar($s) {

        $s = str_replace('�', 'a', $s);
        $s = str_replace('�', 'A', $s);
        $s = str_replace('�', 'e', $s);
        $s = str_replace('�', 'e', $s);
        $s = str_replace('�', 'E', $s);
        $s = str_replace('�', 'i', $s);
        $s = str_replace('�', 'I', $s);
        $s = str_replace('�', 'o', $s);
        $s = str_replace('�', 'O', $s);
        $s = str_replace('�', 'U', $s);
        $s= str_replace('�', 'u', $s);

        return $s;
    }

    public static function acentos($a){
        $a = str_replace('�', '�', $a);
        $a = str_replace('�', '�', $a);
        $a = str_replace('�', '�', $a);
        $a = str_replace('�', '�', $a);
        $a = str_replace('�', '�', $a);
        $a = str_replace('�', '�', $a);
        $a = str_replace('�', '�', $a);
        $a = str_replace('�', '�', $a);
        $a = str_replace('�', '�', $a);
        $a = str_replace('�', '�', $a);

        return $a;
    }




    public static function puntos($d){
        $d= str_replace('\"', '',$d);
        $d= str_replace(':', '', $d);
        $d= str_replace('.', '', $d);
        $d= str_replace(',', '', $d);
        $d= str_replace(';', '', $d);
        $d= str_replace('�', '', $d);
        $d= str_replace('`', '', $d);

        return $d;
    }

    public static function puntos2($d){
        $d= str_replace('\"', '',$d);
        $d= str_replace(':', '', $d);
        $d= str_replace(',', '', $d);
        $d= str_replace(';', '', $d);

        return $d;
    }

    public static function normalizar_oracion($texto){
        $output = preg_replace_callback('/([.!?])\s*(\w)/', function ($matches) {
            return strtoupper($matches[1] . ' ' . $matches[2]);
        }, ucfirst(strtolower($texto)));

        return $output;
    }

    public static function quitarAcentos($texto){
        $search = explode(',', 'ç,Ç,ñ,Ñ,æ,Æ,œ,á,Á,é,É,í,Í,ó,Ó,ú,Ú,à,À,è,È,ì,Ì,ò,Ò,ù,Ù,ä,ë,ï,Ï,ö,ü,ÿ,â,ê,î,ô,û,å,e,i,ø,u,Š,Œ,Ž,š,¥');
        $replace = explode(',', 'c,C,n,N,ae,AE,oe,a,A,e,E,i,I,o,O,u,U,a,A,e,E,i,I,o,O,u,U,ae,e,i,I,oe,ue,y,a,e,i,o,u,a,e,i,o,u,s,o,z,s,Y');
        $string = str_replace($search, $replace, $texto);
        return $string;
    }

}