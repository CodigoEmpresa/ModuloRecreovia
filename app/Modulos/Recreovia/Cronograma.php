<?php

namespace App\Modulos\Recreovia;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Iatstuti\Database\Support\CascadeSoftDeletes;
use Idrd\Usuarios\Seguridad\TraitSeguridad;

class Cronograma extends Model
{
	protected $table = 'Cronogramas';
    protected $primaryKey = 'Id';
    protected $connection = 'mysql';
    protected $cascadeDeletes = ['sesiones'];
    protected $dates = ['deleted_at'];

    public function __construct()
    {
        parent::__construct();
        $this->table = config('database.connections.mysql.database').'.Cronogramas';
    }

    public function sesiones()
    {
    	return $this->hasMany('App\Modulos\Recreovia\Sesion', 'Id_Cronograma');
    }

    public function jornada()
    {
    	return $this->belongsTo('App\Modulos\Recreovia\Jornada', 'Id_Jornada');
    }

    public function reportes()
    {
        return $this->hasMany('App\Modulos\Recreovia\Reporte', 'Id_Cronograma');
    }

    public function gestor()
    {
    	return $this->belongsTo('App\Modulos\Recreovia\Recreopersona', 'Id_Recreopersona');
    }

    public function punto()
    {
    	return $this->belongsTo('App\Modulos\Recreovia\Punto', 'Id_Punto');
    }

    public function historialSesiones()
    {
        return $this->belongsToMany('App\Modulos\Recreovia\Sesion', 'HistorialCronogramasSesiones', 'Id_Cronograma', 'Id_Sesion');
    }

    public function historialReportes()
    {
        return $this->belongsToMany('App\Modulos\Recreovia\Reporte', 'HistorialCronogramasReportes', 'Id_Cronograma', 'Id_Reporte');
    }

    public function historialGestores()
    {
        return $this->belongsToMany('App\Modulos\Recreovia\Recreopersona', 'HistorialCronogramasGestores', 'Id_Cronograma', 'Id_Recreopersona');
    }

    public function toString()
    {
        return 'Cronograma de sesiones desde '.$this->Desde.' hasta '.$this->Hasta;
    }

    public function getCode()
    {
        return 'C'.str_pad($this->Id, 5, '0', STR_PAD_LEFT);
    }

    use SoftDeletes, CascadeSoftDeletes, TraitSeguridad;
}
