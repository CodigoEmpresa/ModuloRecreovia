<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CampoEstadoTablaReportes extends Migration
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
            $table->enum('Estado', ['Pendiente', 'Aprobado', 'Rechazado'])->after('Observaciones')->nullable();
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
            $table->dropColumn('Estado');
        });
    }
}
