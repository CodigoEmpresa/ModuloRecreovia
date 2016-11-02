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
        Schema::create('Puntos', function(Blueprint $table)
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
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('Id_Zona')->references('Id_Zona')->on('Zonas')->onDelete('cascade');
        });

        Schema::create('Jornadas', function(Blueprint $table)
        {
            $table->increments('Id_Jornada');
            $table->integer('Id_Punto')->unsigned();
            $table->string('Jornada');
            $table->string('Dias');
            $table->time('Inicio');
            $table->time('Fin');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('Id_Punto')->references('Id_Punto')->on('Puntos')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('Jornadas', function(Blueprint $table)
        {
            $table->dropForeign(['Id_Punto']);
        });

        Schema::table('Puntos', function(Blueprint $table)
        {
            $table->dropForeign(['Id_Zona']);
        });

        Schema::drop('Jornadas');
        Schema::drop('Puntos');
    }
}
