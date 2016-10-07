<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TablaPuntos extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        return Schema::create('Puntos', function(Blueprint $table)
        {
            $table->increments('Id_Punto');
            $table->integer('Id_Zona')->unsigned();
            $table->integer('Id_Localidad')->unsigned();
            $table->integer('Id_Upz')->unsigned();
            $table->string('Cod_IDRD', 30);
            $table->string('Cod_Recreovia', 30);
            $table->date('Anio_Vigencia');
            $table->string('Escenario');
            $table->string('Direccion');

            $table->foreign('Id_Zona')->references('Id_Zona')->on('Zonas')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('Puntos', function(Blueprint $table)
        {
            $table->dropForeign(['Id_Zona']);
        });

        Schema::drop('Puntos');
    }
}
