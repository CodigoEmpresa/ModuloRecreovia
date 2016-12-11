<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TablaSesiones extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('Cronogramas', function(Blueprint $table)
        {
            $table->increments('Id');
            $table->integer('Id_Punto')->unsigned();
            $table->integer('Id_Jornada')->unsigned();
            $table->integer('Id_Recreopersona')->unsigned();
            $table->date('Desde');
            $table->date('Hasta');
            $table->enum('recreovia', ['RESD', 'RESN']);
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('Id_Recreopersona')->references('Id_Recreopersona')->on('Recreopersonas')->onDelete('cascade');
            $table->foreign('Id_Jornada')->references('Id_Jornada')->on('Jornadas')->onDelete('cascade');
            $table->foreign('Id_Punto')->references('Id_Punto')->on('Puntos')->onDelete('cascade');
        });

        Schema::create('Sesiones', function(Blueprint $table)
        {
            $table->increments('Id');
            $table->integer('Id_Cronograma')->unsigned();
            $table->integer('Id_Recreopersona')->unsigned();
            $table->text('Objetivo_General')->nullable();
            $table->text('Objetivos_Especificos')->nullable();
            $table->text('Metodologia_Aplicar')->nullable();
            $table->text('Fase_Inicial')->nullable();
            $table->text('Fase_Central')->nullable();
            $table->text('Fase_Final')->nullable();
            $table->text('Recursos')->nullable();
            $table->date('Fecha')->nullable();
            $table->time('Inicio')->nullable();
            $table->time('Fin')->nullable();
            $table->enum('Estado', ['Pendiente', 'Diligenciado', 'Aprovado', 'Rechazado'])->nullable();
            $table->enum('Estado_Ejecucion', ['Pendiente', 'Realizado', 'Cancelado', 'Reasignado'])->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('Id_Cronograma')->references('Id')->on('Cronogramas')->onDelete('cascade');
            $table->foreign('Id_Recreopersona')->references('Id_Recreopersona')->on('Recreopersonas')->onDelete('cascade');
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
            $table->dropForeign(['Id_Recreopersona']);
            $table->dropForeign(['Id_Cronograma']);
        });
        
        Schema::table('Cronogramas', function(Blueprint $table)
        {
            $table->dropForeign(['Id_Punto']);
            $table->dropForeign(['Id_Jornada']);
            $table->dropForeign(['Id_Recreopersona']);
        });

        Schema::drop('Sesiones');
        Schema::drop('Cronogramas');
    }
}
