<?php

namespace App\Modulos\Recreovia;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Novedad extends Model
{
    protected $primaryKey = 'Id';
	protected $table = 'ReporteNovedad';
    protected $connection = 'mysql';
    protected $fillable = ['Id_Reporte', 'Cod_514_523', 'Cod_514_541', 'Cod_514_542', 'Novedades'];

    public function __construct()
    {
        $this->table = config('database.connections.mysql.database').'.ReporteNovedad';
    }

    public function reporte()
    {
    	return $this->belongsTo('App\Modulos\Recreovia\Reporte', 'Id_Reporte');
    }

    use SoftDeletes;
}