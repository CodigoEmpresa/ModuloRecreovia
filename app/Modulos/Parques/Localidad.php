<?php

namespace App\Modulos\Parques;

use Idrd\Parques\Repo\Localidad as MLocalidad;

class Localidad extends MLocalidad
{
    public function punto()
    {
    	return $this->hasMany('App\Modulos\Recreovia\Punto', 'Id_Localidad');
    }

    public function personas()
    {
    	return $this->belongsToMany('App\Modulos\Personas\Persona', config('database.connections.mysql.database').'.ZonasPersonasRecreovia', 'Id_Localidad', 'Id_Persona')
    				->withPivot('tipo', 'Id_Zona');
    }

}
