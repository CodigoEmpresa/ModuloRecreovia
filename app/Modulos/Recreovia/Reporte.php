<?php

namespace App\Modulos\Recreovia;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Iatstuti\Database\Support\CascadeSoftDeletes;

class Reporte extends Model
{
	protected $table = 'Reportes';
    protected $primaryKey = 'Id';
    protected $connection = 'mysql';

    public function __construct()
    {
        $this->table = config('database.connections.mysql.database').'.Reportes';
    }

    public function cronograma()
    {
    	return $this->belongsTo('App\Modulos\Recreovia\Cronograma', 'Id_Cronograma');
    }

    public function punto()
    {
    	return $this->belongsTo('App\Modulos\Recreovia\Punto', 'Id_Punto');
    }

    public function servicios()
    {
    	return $this->hasMany('App\Modulos\Recreovia\Servicio', 'Id_Reporte');
    }

    public function novedad()
    {
    	return $this->hasOne('App\Modulos\Recreovia\Novedad', 'Id_Reporte');
    }

    public function profesores()
    {
    	return $this->belongsToMany('App\Modulos\Recreovia\Recreopersona', 'ReportesProfesores', 'Id_Reporte', 'Id_Profesor')
                    ->withPivot('Hora_Llegada', 'Hora_Salida', 'Sesiones_Realizadas', 'Planificacion', 'Sistema_De_Datos', 'Novedades');
    }

    public function toString()
    {
        return $this->Punto->toString().' para el dia '.$this->Dia;
    }

    use SoftDeletes, CascadeSoftDeletes;
}