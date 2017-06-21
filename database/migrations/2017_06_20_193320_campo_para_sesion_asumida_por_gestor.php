<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CampoParaSesionAsumidaPorGestor extends Migration
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
            $table->boolean('Asumida_Por_El_Gestor')->default(0)->after('Estado_Ejecucion')->nullable();
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
            $table->dropColumn('Asumida_Por_El_Gestor');
        });
    }
}
