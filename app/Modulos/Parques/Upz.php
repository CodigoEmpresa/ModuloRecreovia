<?php

namespace App\Modulos\Parques;

use Idrd\Parques\Repo\Upz as MUpz;

class Upz extends MUpz
{
    public function punto()
    {
    	return $this->hasMany('App\Modulos\Recreovia\Punto', 'Id_Upz', 'Id_Upz');
    }
}