<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Modulos\Recreovia\Reporte;
use App\Modulos\Recreovia\Sesion;

class AjustarSesionesInformesDeJornadas extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ajustar:informes-de-jornadas-sesiones';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sincronizar las sesiones con los informes de jornadas antiguos';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $reportes = Reporte::whereDoesntHave('sesiones')->get();
        $reportes_actualizados = $reportes->count();
        $sesiones_actualizadas = 0;

        foreach ($reportes as $reporte) {
            $dias = explode(',', $reporte->Dias);
            $id_cronograma = $reporte->Id_Cronograma;
            $sesiones = Sesion::where('Id_Cronograma', $id_cronograma)
                                ->whereIn('Fecha', $dias)
                                ->whereIn('Estado', ['Finalizado', 'Cancelado'])
                                ->get();

            if($sesiones)
            {
                $sesiones_actualizadas += $sesiones->count();
                $reporte->sesiones()->sync($sesiones->pluck('Id')->toArray());
            }
        }

        echo 'Se actualizarón '.$reportes_actualizados.' reportes a los cuales se les asignó '.$sesiones_actualizadas.' sesiones.<br>';
    }
}
