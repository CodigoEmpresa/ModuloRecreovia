<?php

namespace App\Modulos\Parques;

use Idrd\Parques\Repo\Localidad as MLocalidad;

class Localidad extends MLocalidad
{
    public function puntos()
    {
    	return $this->hasMany('App\Modulos\Recreovia\Punto', 'Id_Localidad');
    }
}
