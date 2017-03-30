<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TablaPafAcompanantes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('Sesiones_Acompanantes', function($table)
        {
            $table->increments('Id');
            $table->integer('Id_Sesion')->unsigned();
            $table->integer('Id_Recreopersona')->unsigned();

            $table->foreign('Id_Sesion')->references('Id')->on('Sesiones');
            $table->foreign('Id_Recreopersona')->references('Id_Recreopersona')->on('Recreopersonas');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('Sesiones_Acompanantes', function($table)
        {
            $table->dropForeign(['Id_Sesion']);
            $table->dropForeign(['Id_Recreopersona']);
        });

        Schema::drop('Sesiones_Acompanantes');
    }
}
