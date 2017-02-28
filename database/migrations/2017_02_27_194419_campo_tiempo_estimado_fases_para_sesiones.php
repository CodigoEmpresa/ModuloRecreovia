<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CampoTiempoEstimadoFasesParaSesiones extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('Sesiones', function(Blueprint $table)
        {
            $table->smallInteger('Tiempo_Inicial')->after('Fase_Inicial')->unsigned()->nullable();
            $table->smallInteger('Tiempo_Central')->after('Fase_Central')->unsigned()->nullable();
            $table->smallInteger('Tiempo_Final')->after('Fase_Final')->unsigned()->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('Sesiones', function(Blueprint $table)
        {
            $table->dropColumn('Tiempo_Inicial');
            $table->dropColumn('Tiempo_Central');
            $table->dropColumn('Tiempo_Final');
        });
    }
}
