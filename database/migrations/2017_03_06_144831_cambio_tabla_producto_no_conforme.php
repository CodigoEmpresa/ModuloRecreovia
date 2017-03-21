<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CambioTablaProductoNoConforme extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ProductosNoConformes', function(Blueprint $table)
        {
            $table->dropForeign(['Id_Sesion']);
        });

        Schema::drop('ProductosNoConformes');

        Schema::create('ProductoNoConforme', function(Blueprint $table)
        {
            $table->increments('Id');
            $table->integer('Id_Sesion')->unsigned();
            $table->boolean('Requisito_1')->default(0)->nullable();
            $table->boolean('Requisito_2')->default(0)->nullable();
            $table->boolean('Requisito_3')->default(0)->nullable();
            $table->boolean('Requisito_4')->default(0)->nullable();
            $table->boolean('Requisito_5')->default(0)->nullable();
            $table->boolean('Requisito_6')->default(0)->nullable();
            $table->boolean('Requisito_7')->default(0)->nullable();
            $table->boolean('Requisito_8')->default(0)->nullable();
            $table->boolean('Requisito_9')->default(0)->nullable();
            $table->boolean('Requisito_10')->default(0)->nullable();
            $table->boolean('Requisito_11')->default(0)->nullable();
            $table->boolean('Requisito_12')->default(0)->nullable();
            $table->boolean('Requisito_13')->default(0)->nullable();
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

        Schema::table('ProductoNoConforme', function(Blueprint $table)
        {
            $table->dropForeign(['Id_Sesion']);
        });

        Schema::drop('ProductoNoConforme');
    }
}
