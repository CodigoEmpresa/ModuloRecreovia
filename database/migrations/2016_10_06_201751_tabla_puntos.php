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
            $table->integer('Id_Localidad')->unsigned();
            $table->integer('Id_Upz')->unsigned();
            $table->string('Cod_IDRD', 30);
            $table->string('Cod_Recreovia', 30);
            $table->date('Anio_Vigencia');
            $table->string('Escenario');
            $table->string('Direccion');
            $table->decimal('Latitud', 10, 8);
            $table->decimal('Longitud', 11, 8);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('Jornadas', function(Blueprint $table)
        {
            $table->increments('Id_Jornada');
            $table->string('Jornada');
            $table->string('Dias')->nullable();
            $table->time('Inicio');
            $table->time('Fin');
            $table->enum('Tipo', ['Periodico', 'Eventual']);
            $table->date('Fecha_Evento_Inicio')->nullable();
            $table->date('Fecha_Evento_Fin')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('JornadasPuntos', function(Blueprint $table)
        {
            $table->increments('Id');
            $table->integer('Id_Jornada')->unsigned();
            $table->integer('Id_Punto')->unsigned();

            $table->foreign('Id_Jornada')->references('Id_Jornada')->on('Jornadas')->onDelete('cascade');
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
        Schema::table('JornadasPuntos', function(Blueprint $table)
        {
            $table->dropForeign(['Id_Punto']);
            $table->dropForeign(['Id_Jornada']);
        });

        Schema::drop('JornadasPuntos');
        Schema::drop('Jornadas');
        Schema::drop('Puntos');
    }
}
