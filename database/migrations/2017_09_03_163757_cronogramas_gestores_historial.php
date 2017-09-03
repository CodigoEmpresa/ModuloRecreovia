<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CronogramasGestoresHistorial extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('HistorialCronogramasGestores', function($table)
        {
            $table->integer('Id_Recreopersona')->unsigned();
            $table->integer('Id_Cronograma')->unsigned();

            $table->foreign('Id_Recreopersona')->references('Id_Recreopersona')->on('Recreopersonas');
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
        Schema::table('HistorialCronogramasGestores', function(Blueprint $table)
        {
            $table->dropForeign(['Id_Recreopersona']);
            $table->dropForeign(['Id_Cronograma']);
        });

        Schema::drop('HistorialCronogramasGestores');
    }
}
