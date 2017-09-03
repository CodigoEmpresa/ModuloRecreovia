<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ReportesCronogramasHistorial extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('HistorialCronogramasReportes', function($table)
        {
            $table->integer('Id_Reporte')->unsigned();
            $table->integer('Id_Cronograma')->unsigned();

            $table->foreign('Id_Reporte')->references('Id')->on('Reportes');
            $table->foreign('Id_Cronograma')->references('Id')->on('Cronogramas');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('HistorialCronogramasReportes', function(Blueprint $table)
        {
            $table->dropForeign(['Id_Reporte']);
            $table->dropForeign(['Id_Cronograma']);
        });

        Schema::drop('HistorialCronogramasReportes');
    }
}
