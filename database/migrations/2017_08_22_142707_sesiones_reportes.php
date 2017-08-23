<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class SesionesReportes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ReportesSesiones', function($table)
        {
            $table->integer('Id_Reporte')->unsigned();
            $table->integer('Id_Sesion')->unsigned();

            $table->foreign('Id_Reporte')->references('Id')->on('Reportes');
            $table->foreign('Id_Sesion')->references('Id')->on('Sesiones');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('ReportesSesiones', function(Blueprint $table)
        {
            $table->dropForeign(['Id_Reporte']);
            $table->dropForeign(['Id_Sesion']);
        });

        Schema::drop('ReportesSesiones');
    }
}
