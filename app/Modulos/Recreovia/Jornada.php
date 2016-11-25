<?php

namespace App\Modulos\Recreovia;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Iatstuti\Database\Support\CascadeSoftDeletes;

class Jornada extends Model
{
	protected $table = 'Jornadas';
    protected $primaryKey = 'Id_Jornada';
    protected $connection = 'mysql';

    public function __construct()
    {
    	$this->table = config('database.connections.mysql.database').'.Jornadas';
    }

    public function puntos()
    {
        return $this->belongsToMany('App\Modulos\Recreovia\Punto', 'JornadasPuntos', 'Id_Jornada', 'Id_Punto');
    }

    use SoftDeletes, CascadeSoftDeletes;
}
