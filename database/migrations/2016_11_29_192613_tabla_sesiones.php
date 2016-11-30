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
            $table->string('Numero');
            $table->text('Objetivo_General');
            $table->text('Objetivos_Especificos');
            $table->text('Metodologia_Aplicar');
            $table->text('Fase_Inicial');
            $table->text('Fase_Central');
            $table->text('Fase_Final');
            $table->text('Recursos');
            $table->time('Inicio');
            $table->time('Fin');
            $table->boolean('Aprovado')->default(0);
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
