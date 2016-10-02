<?php

/**
 * Created by PhpStorm.
 * User: Ezequiel
 * Date: 02/10/2016
 * Time: 02:59 AM
 */
class StatosLavori extends ActiveRecord
{
    public function getStatos(){
        return $this->find();
    }
}