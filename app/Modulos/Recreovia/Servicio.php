<?php

namespace App\Modulos\Recreovia;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Servicio extends Model
{
	protected $table = 'ReportesServicios';
    protected $connection = 'mysql';

    public function __construct()
    {
        $this->table = config('database.connections.mysql.database').'.ReportesServicios';
    }

    public function reporte()
    {
    	return $this->belongsTo('App\Modulos\Recreovia\Reporte', 'Id_Reporte');
    }

    use SoftDeletes;
}