<?php

namespace App\Modulos\Recreovia;

use Illuminate\Database\Eloquent\Model as Eloquent;
use Illuminate\Database\Eloquent\SoftDeletes;
use Idrd\Usuarios\Seguridad\TraitSeguridad;

class Punto extends Eloquent
{
	protected $table = 'Puntos';
    protected $primaryKey = 'Id_Punto';
    protected $connection = 'mysql';
    protected $softDelete = true;

    public function __construct()
    {
        parent::__construct();
    	$this->table = config('database.connections.mysql.database').'.Puntos';
    }

    public function localidad()
    {
        return $this->belongsTo('App\Modulos\Parques\Localidad', 'Id_Localidad');
    }

    public function upz()
    {
        return $this->belongsTo('App\Modulos\Parques\Upz', 'Id_Upz', 'Id_Upz');
    }

    public function jornadas()
    {
        return $this->belongsToMany('App\Modulos\Recreovia\Jornada', 'JornadasPuntos', 'Id_Punto', 'Id_Jornada');
    }

    public function cronogramas()
    {
        return $this->hasMany('App\Modulos\Recreovia\Cronograma', 'Id_Punto');
    }

    public function reportes()
    {
        return $this->hasMany('App\Modulos\Recreovia\Reporte', 'Id_Punto');
    }

    public function toString()
    {
        return strtoupper($this->Escenario);
    }

    public function getCode()
    {
        return 'P'.str_pad($this->Id_Punto, 4, '0', STR_PAD_LEFT);
    }

    use SoftDeletes, TraitSeguridad;
}
