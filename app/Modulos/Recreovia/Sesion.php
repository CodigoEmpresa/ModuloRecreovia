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
    protected $cascadeDeletes = ['gruposPoblacionales', 'productosNoConformes'];

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

    public function productosNoConformes()
    {
        return $this->hasMany('App\Modulos\Recreovia\ProductoNoConforme', 'Id_Sesion');
    }

    public function toString()
    {
        return $this->Objetivo_General.' programada para el dia '.$this->Fecha.' de '.$this->Inicio.' a '.$this->Fin;
    }

    public function toSuccessString()
    {
        return $this->Objetivo_General.' realizada el dia '.$this->Fecha.' de '.$this->Inicio.' a '.$this->Fin;
    }

    use SoftDeletes;
}