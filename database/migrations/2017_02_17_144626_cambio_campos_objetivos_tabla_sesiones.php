<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CambioCamposObjetivosTablaSesiones extends Migration
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
            $table->text('Objetivos_Especificos_1')->after('Objetivos_Especificos')->nullable();
            $table->text('Objetivos_Especificos_2')->after('Objetivos_Especificos_1')->nullable();
            $table->text('Objetivos_Especificos_3')->after('Objetivos_Especificos_2')->nullable();
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
            $table->dropColumn('Objetivos_Especificos_1');
            $table->dropColumn('Objetivos_Especificos_2');
            $table->dropColumn('Objetivos_Especificos_3');
        });
    }
}
