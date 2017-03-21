<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TablaProductoNoConforme extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ProductosNoConformes', function(Blueprint $table)
        {
            $table->increments('Id');
            $table->integer('Id_Sesion')->unsigned();
            $table->smallInteger('Requisito');
            $table->string('Requisito_Detalle', 100);
            $table->text('Descripcion_De_La_No_Conformidad')->nullable();
            $table->text('Descripcion_De_La_Accion_Tomada')->nullable();
            $table->text('Tratamiento')->nullable();
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
        Schema::table('ProductosNoConformes', function(Blueprint $table)
        {
            $table->dropForeign(['Id_Sesion']);
        });

        Schema::drop('ProductosNoConformes');
    }
}
