<?php

namespace App\Modulos\Recreovia;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Iatstuti\Database\Support\CascadeSoftDeletes;

class Punto extends Model
{
	protected $table = 'Puntos';
    protected $primaryKey = 'Id_Punto';
    protected $connection = 'mysql';
    protected $cascadeDeletes = ['jornadas'];

    public function __construct()
    {
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
        return $this->belongsToMany('App\Modulos\Recreovia\Punto', 'JornadasPuntos', 'Id_Punto', 'Id_Jornada');
    }

    use SoftDeletes, CascadeSoftDeletes;
}
