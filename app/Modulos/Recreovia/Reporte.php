<?php

namespace App\Modulos\Recreovia;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Iatstuti\Database\Support\CascadeSoftDeletes;
use Idrd\Usuarios\Seguridad\TraitSeguridad;

class Reporte extends Model
{
	protected $table = 'Reportes';
    protected $primaryKey = 'Id';
    protected $connection = 'mysql';

    public function __construct()
    {
        parent::__construct();
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

    public function sesiones()
    {
        return $this->belongsToMany('App\Modulos\Recreovia\Sesion', 'ReportesSesiones', 'Id_Reporte', 'Id_Sesion');
    }

    public function historialCronogramas()
    {
        return $this->belongsToMany('App\Modulos\Recreovia\Cronograma', 'HistorialCronogramasReportes', 'Id_Reporte', 'Id_Cronograma');
    }

    public function toString()
    {
        return $this->cronograma->jornada->toString().' <br> Día(s) '.$this->Dias;
    }

    public function getCode()
    {
        return 'R'.str_pad($this->Id, 5, '0', STR_PAD_LEFT);
    }

    use SoftDeletes, CascadeSoftDeletes, TraitSeguridad;
}
