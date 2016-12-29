<?php

namespace App\Modulos\Recreovia;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Iatstuti\Database\Support\CascadeSoftDeletes;

class Sesion extends Model
{
	protected $table = 'Sesiones';
    protected $primaryKey = 'Id';
    protected $connection = 'mysql';

    public function cronograma()
    {
    	return $this->belongsTo('App\Modulos\Recreovia\Cronograma', 'Id_Cronograma');
    }

    public function profesor()
    {
    	return $this->belongsTo('App\Modulos\Recreovia\Recreopersona', 'Id_Recreopersona');
    }

    public function gruposPoblacionales()
    {
        return $this->belongsToMany('App\Modulos\Recreovia\GrupoPoblacional', 'Participaciones', 'Id_Sesion', 'Id_Grupo')
                    ->withPivot('Genero', 'Grupo_Asistencia', 'Cantidad');
    }

    public function toString()
    {
        return $this->Objetivo_General.' programada para el dia '.$this->Fecha.' de '.$this->Inicio.' a '.$this->Fin;
    }

    use SoftDeletes;
}