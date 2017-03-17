<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CambioTablaReportes extends Migration
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
            $table->string('Dias', 500)->after('Dia')->nullable();
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
            $table->dropColumn('Dias');
        });
    }
}
