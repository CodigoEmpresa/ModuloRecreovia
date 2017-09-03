<?php

namespace App\Modulos\Recreovia;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Iatstuti\Database\Support\CascadeSoftDeletes;
use Idrd\Usuarios\Seguridad\TraitSeguridad;

class Sesion extends Model
{
	protected $table = 'Sesiones';
    protected $primaryKey = 'Id';
    protected $connection = 'mysql';
    protected $cascadeDeletes = ['gruposPoblacionales', 'productosNoConformes', 'calificacionDelServicio'];

    public function __construct()
    {
        parent::__construct();
        $this->table = config('database.connections.mysql.database').'.Sesiones';
    }
    
    public function cronograma()
    {
    	return $this->belongsTo('App\Modulos\Recreovia\Cronograma', 'Id_Cronograma');
    }

    public function profesor()
    {
    	return $this->belongsTo('App\Modulos\Recreovia\Recreopersona', 'Id_Recreopersona');
    }

	public function acompanantes()
	{
		return $this->belongsToMany('App\Modulos\Recreovia\Recreopersona', 'Sesiones_Acompanantes', 'Id_Sesion', 'Id_Recreopersona');
	}

    public function gruposPoblacionales()
    {
        return $this->belongsToMany('App\Modulos\Recreovia\GrupoPoblacional', 'Participaciones', 'Id_Sesion', 'Id_Grupo')
                    ->withPivot('Genero', 'Grupo_Asistencia', 'Cantidad');
    }

    public function productoNoConforme()
    {
        return $this->hasOne('App\Modulos\Recreovia\ProductoNoConforme', 'Id_Sesion');
    }

    public function calificacionDelServicio()
    {
        return $this->hasOne('App\Modulos\Recreovia\CalificacionDelServicio', 'Id_Sesion');
    }

    public function reportes()
    {
        return $this->belongsToMany('App\Modulos\Recreovia\Reporte', 'ReportesSesiones', 'Id_Sesion', 'Id_Reporte');
    }

    public function historialCronogramas()
    {
        return $this->belongsToMany('App\Modulos\Recreovia\Cronograma', 'HistorialCronogramasSesiones', 'Id_Sesion', 'Id_Cronograma');
    }

    public function toString()
    {
        return $this->Objetivo_General.' programada para el dia '.$this->Fecha.' de '.$this->Inicio.' a '.$this->Fin;
    }

    public function toSuccessString()
    {
        return $this->Objetivo_General.' realizada el dia '.$this->Fecha.' de '.$this->Inicio.' a '.$this->Fin;
    }

    public function getCode()
    {
        return 'S'.str_pad($this->Id, 5, '0', STR_PAD_LEFT);
    }

	public function getPending()
	{
		$gruposPoblacionales = '<span class="pointer" title="Asistencia">'.(($this->gruposPoblacionales()->count() > 0) ? 'A' : '-').'</span>';
		$productoNoConforme = '<span class="pointer" title="Producto no conforme">'.((!!$this->productoNoConforme) ? 'P' : '-').'</span>';
		$calificacionDelServicio = '<span class="pointer" title="Calificación del servicio">'.((!!$this->calificacionDelServicio) ? 'C' : '-').'</span>';

		return $gruposPoblacionales.' '.$productoNoConforme.' '.$calificacionDelServicio;
	}

    use SoftDeletes, TraitSeguridad;
}
