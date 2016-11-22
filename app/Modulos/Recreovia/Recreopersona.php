<?php

namespace App\Modulos\Recreovia;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Iatstuti\Database\Support\CascadeSoftDeletes;

class Recreopersona extends Model
{
	protected $table = 'Recreopersonas';
    protected $primaryKey = 'Id_Recreopersona';
    protected $connection = 'mysql';
    protected $dates = ['deleted_at'];

    public function __construct()
    {
    	$this->table = config('database.connections.mysql.database').'.Recreopersonas';
    }

    public function persona()
    {
    	return $this->belongsTo('App\Modulos\Personas\Persona', 'Id_Persona');
    }

    public function localidades()
    {
        return $this->belongsToMany('App\Modulos\Parques\Localidad', 'LocalidadesPersonas', 'Id_Recreopersona', 'Id_Localidad')
                    ->withPivot('tipo');
    }

    use SoftDeletes, CascadeSoftDeletes;
}
