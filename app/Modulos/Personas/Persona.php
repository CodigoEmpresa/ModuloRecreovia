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

	public function toFriendlyString()
	{
		return trim(strtoupper($this->Primer_Nombre.' '.$this->Primer_Apellido));
	}

	public function toString()
	{
		return trim(strtoupper($this->Primer_Apellido.' '.$this->Segundo_Apellido.' '.$this->Primer_Nombre.' '.$this->Segundo_Nombre));
	}
}
