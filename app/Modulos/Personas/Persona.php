<?php

namespace App\Modulos\Personas;

use Idrd\Usuarios\Repo\Persona as MPersona;

class Persona extends MPersona
{
    public function recreopersona()
	{
		return $this->hasOne('App\Modulos\Recreovia\Recreopersona', 'Id_Persona')
					->whereNull('deleted_at');
	}
}
