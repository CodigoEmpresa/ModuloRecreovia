<?php

namespace App\Modulos\Recreovia;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Iatstuti\Database\Support\CascadeSoftDeletes;
use Idrd\Usuarios\Seguridad\TraitSeguridad;

class ProductoNoConforme extends Model
{
	protected $table = 'ProductoNoConforme';
    protected $primaryKey = 'Id';
    protected $connection = 'mysql';
    protected $fillable = ['Requisito', 'Requisito_Detalle', 'Descripcion_De_La_No_Conformidad', 'Descripcion_De_La_Accion_Tomada', 'Tratamiento'];

    public function __construct()
    {
        parent::__construct();
        $this->table = config('database.connections.mysql.database').'.ProductoNoConforme';
    }
    
    public function sesion()
    {
    	return $this->belongsTo('App\Modulos\Recreovia\Sesion', 'Id_Sesion');
    }

    use SoftDeletes, TraitSeguridad;
}