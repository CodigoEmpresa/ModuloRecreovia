<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Modulos\Recreovia\Reporte;

class AjustarFechasInformesDeJornadas extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ajustar:informes-de-jornadas-fechas';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Ajustar las fechas de los informes que quedaron sin fecha por cambio a selección individual de sesiones';

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
        $reportes = Reporte::with('sesiones')->where('Dias', '')->get();

        foreach ($reportes as $reporte)
        {
            $dias=[];

            foreach ($reporte->sesiones as $sesion)
            {
                if (!in_array($sesion['Fecha'], $dias))
                {
                    $dias[] = $sesion['Fecha'];
                }
            }

            usort($dias, function($a, $b) {
                return strcmp($a, $b);
            });

            $reporte->Dias = implode(',', $dias);

            $reporte->save();
        }

        echo 'Se actualizarón '.$reportes->count().' reportes<br>';
    }
}
