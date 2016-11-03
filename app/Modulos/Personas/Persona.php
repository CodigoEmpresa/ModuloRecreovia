<?php

namespace App\Modulos\Personas;

use Idrd\Usuarios\Repo\Persona as MPersona;

class Persona extends MPersona
{
    public function zonas()
    {
    	return $this->belongsToMany('App\Modulos\Recreovia\Zona', config('database.connections.mysql.database').'.ZonasPersonasRecreovia', 'Id_Persona', 'Id_Zona')
    				->withPivot('tipo', 'Id_Localidad');
    }

    public function localidades()
    {
    	return $this->belongsToMany('App\Modulos\Parques\Localidad', config('database.connections.mysql.database').'.ZonasPersonasRecreovia', 'Id_Persona', 'Id_Localidad')
    				->withPivot('tipo', 'Id_Zona');
    }
}
