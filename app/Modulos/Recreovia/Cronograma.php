<?php

namespace App\Modulos\Recreovia;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Iatstuti\Database\Support\CascadeSoftDeletes;

class Cronograma extends Model
{
	protected $table = 'Cronograma';
    protected $primaryKey = 'Id';
    protected $connection = 'mysql';

    public function sesiones()
    {
    	return $this->hasMany('App\Modulos\Recreovia\Sesion', 'Id_Cronograma');
    }

    public function jornada()
    {
    	return $this->belongsTo('App\Modulos\Recreovia\Jornada', 'Id_Jornada');
    }

    public function gestor()
    {
    	return $this->belongsTo('App\Modulos\Recreovia\Recreopersona', 'Id_Recreopersona');
    }

    public function punto()
    {
    	return $this->belongsTo('App\Modulos\Recreovia\Punto', 'Id_Punto');
    }
}