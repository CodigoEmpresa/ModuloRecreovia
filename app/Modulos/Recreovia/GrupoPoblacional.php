<?php

namespace App\Modulos\Recreovia;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Idrd\Usuarios\Seguridad\TraitSeguridad;

class GrupoPoblacional extends Model
{
	protected $table = 'GruposPoblacionales';
    protected $primaryKey = 'Id';
    protected $connection = 'mysql';

    public function __construct()
    {
        parent::__construct();
        $this->table = config('database.connections.mysql.database').'.GruposPoblacionales';
    }

    public function sesiones()
    {
        return $this->belongsToMany('App\Modulos\Recreovia\Sesion', 'Participaciones', 'Id_Grupo', 'Id_Sesion')
        			->withPivot('Genero', 'Grupo_Asistencia', 'Cantidad');
    }

    use SoftDeletes, TraitSeguridad;
}