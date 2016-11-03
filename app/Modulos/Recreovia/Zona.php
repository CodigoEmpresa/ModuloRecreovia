<?php

namespace App\Modulos\Recreovia;

use Illuminate\Database\Eloquent\Model;

class Zona extends Model
{
	public $timestamps = false;
	protected $table = 'Zonas';
    protected $primaryKey = 'Id_Zona';
    protected $connection = 'mysql';

    public function __construct()
    {
    	$this->table = config('database.connections.mysql.database').'.Zonas';
    }

    public function personas()
    {
    	return $this->belongsToMany('App\Modulos\Personas\Persona', config('database.connections.mysql.database').'.ZonasPersonasRecreovia', 'Id_Zona', 'Id_Persona')
    				->withPivot('tipo', 'Id_Localidad');
    }

    public function puntos()
    {
        return $this->hasMany('App\Modulos\Recreovia\Punto', 'Id_Zona');
    }
}
