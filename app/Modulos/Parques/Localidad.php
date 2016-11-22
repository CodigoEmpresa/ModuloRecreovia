<?php

namespace App\Modulos\Parques;

use Idrd\Parques\Repo\Localidad as MLocalidad;

class Localidad extends MLocalidad
{
    public function puntos()
    {
    	return $this->hasMany('App\Modulos\Recreovia\Punto', 'Id_Localidad');
    }

    public function recreopersonas()
    {
    	return $this->belongsToMany('App\Modulos\Recreovia\Recreopersona', 'LocalidadesPersonas', 'Id_Localidad', 'Id_Recreopersona')
    				->withPivot('tipo');
    }
}
