<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class NuevosCamposTablaReportes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('Reportes', function(Blueprint $table)
        {
            $table->text('Observaciones')->after('Condiciones_Climaticas')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('Reportes', function(Blueprint $table)
        {
            $table->dropColumn('Observaciones');
        });
    }
}
