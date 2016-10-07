<?php

namespace App\Modulos\Recreovia;

use Illuminate\Database\Eloquent\Model;

class Punto extends Model
{
	public $timestamps = false;
	protected $table = 'Puntos';
    protected $primaryKey = 'Id_Punto';
    protected $connection = 'mysql';

    public function __construct()
    {
    	$this->table = config('database.connections.mysql.database').'.Puntos';
    }

    public function zona()
    {
        return $this->belongsTo('App\Modulos\Recreovia\Zona', 'Id_Zona');
    }
}
