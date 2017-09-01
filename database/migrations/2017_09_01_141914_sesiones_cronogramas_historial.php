<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class SesionesCronogramasHistorial extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('HistorialCronogramasSesiones', function($table)
        {
            $table->integer('Id_Sesion')->unsigned();
            $table->integer('Id_Cronograma')->unsigned();

            $table->foreign('Id_Sesion')->references('Id')->on('Sesiones');
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
        Schema::table('HistorialCronogramasSesiones', function(Blueprint $table)
        {
            $table->dropForeign(['Id_Sesion']);
            $table->dropForeign(['Id_Cronograma']);
        });

        Schema::drop('HistorialCronogramasSesiones');
    }
}
