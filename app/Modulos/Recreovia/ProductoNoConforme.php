<?php

namespace App\Modulos\Recreovia;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Iatstuti\Database\Support\CascadeSoftDeletes;

class ProductoNoConforme extends Model
{
	protected $table = 'ProductosNoConformes';
    protected $primaryKey = 'Id';
    protected $connection = 'mysql';
    protected $fillable = ['Requisito', 'Requisito_Detalle', 'Descripcion_De_La_No_Conformidad', 'Descripcion_De_La_Accion_Tomada', 'Tratamiento'];

    public function sesion()
    {
    	return $this->belongsTo('App\Modulos\Recreovia\Sesion', 'Id_Sesion');
    }

    use SoftDeletes;
}