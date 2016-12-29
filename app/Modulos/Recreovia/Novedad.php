<?php

namespace App\Modulos\Recreovia;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Novedad extends Model
{
	protected $table = 'ReportesNovedades';
    protected $primaryKey = 'Id';
    protected $connection = 'mysql';

    public function __construct()
    {
        $this->table = config('database.connections.mysql.database').'.ReportesNovedades';
    }

    public function reporte()
    {
    	return $this->belongsTo('App\Modulos\Recreovia\Reporte', 'Id_Reporte');
    }

    use SoftDeletes;
}