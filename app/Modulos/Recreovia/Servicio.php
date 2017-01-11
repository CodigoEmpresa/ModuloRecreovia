<?php

namespace App\Modulos\Recreovia;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Servicio extends Model
{

    protected $primaryKey = 'Id';
	protected $table = 'ReportesServicios';
    protected $connection = 'mysql';
    protected $fillable = ['Cod_514_523', 'Cod_514_541', 'Cod_514_542', 'tipo', 'Empresa', 'Placa_Camion', 'Operarios', 'Observaciones_Generales'];

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