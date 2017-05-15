<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class NuevosCamposUsuariosRecreovia extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('Recreopersonas', function(Blueprint $table)
        {
            $table->string('numero_contrato', 500)->after('correo')->nullable();
            $table->string('expediente_virtual', 500)->after('contrato')->nullable();
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('Recreopersonas', function(Blueprint $table)
        {
            $table->dropColumn('numero_contrato');
            $table->dropColumn('expediente_virtual');
        });
    }
}
