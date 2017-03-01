<?php

namespace App\Modulos\Recreovia;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Iatstuti\Database\Support\CascadeSoftDeletes;

class CalificacionDelServicio extends Model
{
	protected $table = 'CalificacionesDelServicio';
    protected $primaryKey = 'Id';
    protected $connection = 'mysql';
    protected $fillable = ['Puntualidad_PAF', 'Tiempo_De_La_Sesión', 'Escenario_Y_Montaje', 'Cumplimiento_Del_Objetivo', 'Variedad_Y_Creatividad', 'Imagen_Institucional', 'Divulgacion', 'Seguridad', 'Nombre', 'Telefono', 'Correo'];

    public function sesion()
    {
    	return $this->belongsTo('App\Modulos\Recreovia\Sesion', 'Id_Sesion');
    }

    use SoftDeletes;
}