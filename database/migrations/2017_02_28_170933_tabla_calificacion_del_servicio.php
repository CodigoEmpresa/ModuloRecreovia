<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TablaCalificacionDelServicio extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('CalificacionesDelServicio', function(Blueprint $table)
        {
            $table->increments('Id');
            $table->integer('Id_Sesion')->unsigned();
            $table->smallInteger('Puntualidad_PAF')->unsigned()->nullable();
            $table->smallInteger('Tiempo_De_La_Sesion')->unsigned()->nullable();
            $table->smallInteger('Escenario_Y_Montaje')->unsigned()->nullable();
            $table->smallInteger('Cumplimiento_Del_Objetivo')->unsigned()->nullable();
            $table->smallInteger('Variedad_Y_Creatividad')->unsigned()->nullable();
            $table->smallInteger('Imagen_Institucional')->unsigned()->nullable();
            $table->smallInteger('Divulgacion')->unsigned()->nullable();
            $table->smallInteger('Seguridad')->unsigned()->nullable();
            $table->string('Nombre', 100)->nullable();
            $table->string('Telefono', 100)->nullable();
            $table->string('Correo', 100)->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('Id_Sesion')->references('Id')->on('Sesiones');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('CalificacionesDelServicio', function(Blueprint $table)
        {
            $table->dropForeign(['Id_Sesion']);
        });

        Schema::drop('CalificacionesDelServicio');
    }
}
