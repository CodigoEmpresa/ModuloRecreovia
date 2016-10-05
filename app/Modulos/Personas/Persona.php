<?php

namespace App\Modulos\Personas;

use Idrd\Usuarios\Repo\Persona as MPersona;

class Persona extends MPersona
{
    public function zonas()
    {
    	return $this->belongsToMany('App\Modulos\Recreovia\Zona', config('database.connections.mysql.database').'.ZonasPersonasRecreovia', 'Id_Persona', 'Id_Zona')
    				->withPivot('tipo');
    }
}
