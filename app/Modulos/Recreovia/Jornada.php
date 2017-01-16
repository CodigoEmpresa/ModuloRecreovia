<?php

namespace App\Modulos\Recreovia;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Iatstuti\Database\Support\CascadeSoftDeletes;

class Jornada extends Model
{
    use SoftDeletes, CascadeSoftDeletes;
    
	protected $table = 'Jornadas';
    protected $primaryKey = 'Id_Jornada';
    protected $connection = 'mysql';
    protected $cascadeDeletes = ['cronogramas'];
    protected $dates = ['deleted_at'];

    public function __construct()
    {
    	$this->table = config('database.connections.mysql.database').'.Jornadas';
    }

    public function puntos()
    {
        return $this->belongsToMany('App\Modulos\Recreovia\Punto', 'JornadasPuntos', 'Id_Jornada', 'Id_Punto');
    }

    public function cronogramas()
    {
        return $this->hasMany('App\Modulos\Recreovia\Cronograma', 'Id_Jornada');
    }

    public function validarDia($dia='')
    {
        $dias = explode(',', $this->Dias);
        return in_array($dia, $dias);
    }

    public function toString()
    {
        $label = '';
        $titulo = '';
        $periodo = '';
        $periodo_dias = '';

        if($this->Fecha_Evento_Fin)
            $periodo = 'del '.$this->Fecha_Evento_Inicio.' al '.$this->Fecha_Evento_Fin;
        else
            $periodo = 'el dia '.$this->Fecha_Evento_Inicio;
        
        if($this->Dias)
            $periodo_dias = (count(explode(',', $this->Dias)) > 1 ? 'los dias ' : 'el dia ').strrev(preg_replace(strrev("/,/"), strrev(' y '), strrev($this->Dias), 1));

        switch($this->Jornada)
        {
            case 'dia': 
                $label = 'Jornada diurna '.$periodo_dias.' de '.$this->Inicio.' a '.$this->Fin;
            break;
            case 'noche': 
                $label = 'Jornada nocturna '.$periodo_dias.' de '.$this->Inicio.' a '.$this->Fin;
            break;
            case 'fds': 
                $label = 'Jornada de fin de semana '.$periodo_dias.' de '.$this->Inicio.' a '.$this->Fin;
            break;
            case 'clases_grupales':
                $label = 'Clase grupal '.$periodo.' '.$periodo_dias.' de '.$this->Inicio.' a '.$this->Fin;
            break;
            case 'mega_eventos': 
                $label = 'Mega evento de actividad física '.$periodo.' '.$periodo_dias.' de '.$this->Inicio.' a '.$this->Fin;
            break;
        }

        return $label;
    }
}
